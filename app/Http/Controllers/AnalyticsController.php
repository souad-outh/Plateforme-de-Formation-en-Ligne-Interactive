<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\User;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use PDF;

class AnalyticsController extends Controller
{
    protected $analyticsService;

    /**
     * Create a new controller instance.
     *
     * @param AnalyticsService $analyticsService
     * @return void
     */
    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Show student dashboard with analytics
     *
     * @return \Illuminate\View\View
     */
    public function studentDashboard()
    {
        $student = Auth::user();
        $analytics = $this->analyticsService->getStudentPerformance($student);

        return view('analytics.student-dashboard', compact('analytics'));
    }

    /**
     * Show instructor dashboard with analytics
     *
     * @return \Illuminate\View\View
     */
    public function instructorDashboard()
    {
        $instructor = Auth::user();
        $analytics = $this->analyticsService->getInstructorAnalytics($instructor);

        return view('analytics.instructor-dashboard', compact('analytics'));
    }

    /**
     * Show detailed course analytics
     *
     * @param int $courseId
     * @return \Illuminate\View\View
     */
    public function courseAnalytics($courseId)
    {
        $course = Course::with(['quizzes', 'contents'])->findOrFail($courseId);

        // Get quiz results for this course
        $quizResults = QuizResult::whereHas('quiz', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->with(['quiz', 'user'])->get();

        // Calculate course statistics
        $totalStudents = $quizResults->pluck('user_id')->unique()->count();
        $totalQuizzes = $course->quizzes->count();
        $totalAttempts = $quizResults->count();
        $averageScore = $quizResults->avg('score') ?? 0;

        // Calculate quiz performance
        $quizPerformance = $quizResults
            ->groupBy('quiz_id')
            ->map(function ($results, $quizId) {
                $quiz = $results->first()->quiz;
                return [
                    'quiz_id' => $quizId,
                    'quiz_name' => $quiz->name,
                    'average_score' => round($results->avg('score'), 2),
                    'attempt_count' => $results->count(),
                    'is_ai_generated' => $quiz->is_ai_generated,
                ];
            })
            ->values()
            ->toArray();

        // Calculate student performance
        $studentPerformance = $quizResults
            ->groupBy('user_id')
            ->map(function ($results, $userId) {
                $user = $results->first()->user;
                return [
                    'user_id' => $userId,
                    'username' => $user->username,
                    'average_score' => round($results->avg('score'), 2),
                    'attempt_count' => $results->count(),
                    'highest_score' => round($results->max('score'), 2),
                ];
            })
            ->sortByDesc('average_score')
            ->values()
            ->toArray();

        $analytics = [
            'course' => $course,
            'overall' => [
                'total_students' => $totalStudents,
                'total_quizzes' => $totalQuizzes,
                'total_attempts' => $totalAttempts,
                'average_score' => round($averageScore, 2),
            ],
            'quiz_performance' => $quizPerformance,
            'student_performance' => $studentPerformance,
        ];

        return view('analytics.course-analytics', compact('analytics'));
    }

    /**
     * Show detailed quiz analytics
     *
     * @param int $quizId
     * @return \Illuminate\View\View
     */
    public function quizAnalytics($quizId)
    {
        $quiz = Quiz::with(['course', 'questions'])->findOrFail($quizId);

        // Get quiz results for this quiz
        $quizResults = QuizResult::where('quiz_id', $quizId)
            ->with(['user'])
            ->get();

        // Calculate quiz statistics
        $totalAttempts = $quizResults->count();
        $uniqueStudents = $quizResults->pluck('user_id')->unique()->count();
        $averageScore = $quizResults->avg('score') ?? 0;
        $highestScore = $quizResults->max('score') ?? 0;
        $lowestScore = $quizResults->min('score') ?? 0;

        // Calculate performance distribution
        $ranges = [
            '0-20' => 0,
            '21-40' => 0,
            '41-60' => 0,
            '61-80' => 0,
            '81-100' => 0,
        ];

        foreach ($quizResults as $result) {
            $score = $result->score;

            if ($score <= 20) {
                $ranges['0-20']++;
            } elseif ($score <= 40) {
                $ranges['21-40']++;
            } elseif ($score <= 60) {
                $ranges['41-60']++;
            } elseif ($score <= 80) {
                $ranges['61-80']++;
            } else {
                $ranges['81-100']++;
            }
        }

        $performanceDistribution = [
            'labels' => array_keys($ranges),
            'data' => array_values($ranges),
        ];

        // Calculate student performance
        $studentPerformance = $quizResults
            ->groupBy('user_id')
            ->map(function ($results, $userId) {
                $user = $results->first()->user;
                $bestResult = $results->sortByDesc('score')->first();

                return [
                    'user_id' => $userId,
                    'username' => $user->username,
                    'best_score' => round($bestResult->score, 2),
                    'attempt_count' => $results->count(),
                    'last_attempt' => $results->sortByDesc('created_at')->first()->created_at->format('Y-m-d H:i'),
                ];
            })
            ->sortByDesc('best_score')
            ->values()
            ->toArray();

        $analytics = [
            'quiz' => $quiz,
            'overall' => [
                'total_attempts' => $totalAttempts,
                'unique_students' => $uniqueStudents,
                'average_score' => round($averageScore, 2),
                'highest_score' => round($highestScore, 2),
                'lowest_score' => round($lowestScore, 2),
            ],
            'performance_distribution' => $performanceDistribution,
            'student_performance' => $studentPerformance,
        ];

        return view('analytics.quiz-analytics', compact('analytics'));
    }

    /**
     * Generate and download student performance report
     *
     * @param int $studentId
     * @return \Illuminate\Http\Response
     */
    public function downloadStudentReport($studentId = null)
    {
        $student = $studentId ? User::findOrFail($studentId) : Auth::user();

        // Check permissions
        if (Auth::id() !== $student->id && !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $analytics = $this->analyticsService->getStudentPerformance($student);

        // Generate PDF
        $pdf = PDF::loadView('analytics.reports.student-report', [
            'student' => $student,
            'analytics' => $analytics,
            'generated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        return $pdf->download('student_performance_report_' . $student->id . '.pdf');
    }

    /**
     * Generate and download course analytics report
     *
     * @param int $courseId
     * @return \Illuminate\Http\Response
     */
    public function downloadCourseReport($courseId)
    {
        // Check permissions
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $course = Course::with(['quizzes', 'contents'])->findOrFail($courseId);

        // Get quiz results for this course
        $quizResults = QuizResult::whereHas('quiz', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->with(['quiz', 'user'])->get();

        // Calculate course statistics
        $totalStudents = $quizResults->pluck('user_id')->unique()->count();
        $totalQuizzes = $course->quizzes->count();
        $totalAttempts = $quizResults->count();
        $averageScore = $quizResults->avg('score') ?? 0;

        // Calculate quiz performance
        $quizPerformance = $quizResults
            ->groupBy('quiz_id')
            ->map(function ($results, $quizId) {
                $quiz = $results->first()->quiz;
                return [
                    'quiz_id' => $quizId,
                    'quiz_name' => $quiz->name,
                    'average_score' => round($results->avg('score'), 2),
                    'attempt_count' => $results->count(),
                ];
            })
            ->values()
            ->toArray();

        $analytics = [
            'course' => $course,
            'overall' => [
                'total_students' => $totalStudents,
                'total_quizzes' => $totalQuizzes,
                'total_attempts' => $totalAttempts,
                'average_score' => round($averageScore, 2),
            ],
            'quiz_performance' => $quizPerformance,
            'generated_at' => now()->format('Y-m-d H:i:s'),
        ];

        // Generate PDF
        $pdf = PDF::loadView('analytics.reports.course-report', [
            'analytics' => $analytics,
        ]);

        return $pdf->download('course_analytics_report_' . $course->id . '.pdf');
    }
}
