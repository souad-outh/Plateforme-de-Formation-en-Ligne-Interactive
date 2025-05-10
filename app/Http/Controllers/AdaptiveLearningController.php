<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Question;
use App\Models\Quiz;
use App\Services\AdaptiveLearningService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdaptiveLearningController extends Controller
{
    protected $adaptiveLearningService;

    /**
     * Create a new controller instance.
     *
     * @param AdaptiveLearningService $adaptiveLearningService
     * @return void
     */
    public function __construct(AdaptiveLearningService $adaptiveLearningService)
    {
        $this->adaptiveLearningService = $adaptiveLearningService;
    }

    /**
     * Show personalized learning dashboard
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $student = Auth::user();
        $enrolledCourses = $student->enrolledCourses;

        $coursesData = [];

        foreach ($enrolledCourses as $course) {
            $coursesData[] = [
                'course' => $course,
                'learning_path' => $this->adaptiveLearningService->generateLearningPath($student, $course),
            ];
        }

        return view('adaptive-learning.dashboard', [
            'courses_data' => $coursesData,
        ]);
    }

    /**
     * Show personalized learning path for a course
     *
     * @param int $courseId
     * @return \Illuminate\View\View
     */
    public function courseLearningPath($courseId)
    {
        $student = Auth::user();
        $course = Course::findOrFail($courseId);

        // Check if student is enrolled in the course
        // In a real implementation, you would check enrollment status

        $learningPath = $this->adaptiveLearningService->generateLearningPath($student, $course);

        return view('adaptive-learning.course-path', [
            'course' => $course,
            'learning_path' => $learningPath,
        ]);
    }

    /**
     * Show interactive practice session
     *
     * @param int $courseId
     * @return \Illuminate\View\View
     */
    public function practiceSession($courseId)
    {
        $student = Auth::user();
        $course = Course::findOrFail($courseId);

        // Generate learning path to get recommended questions
        $learningPath = $this->adaptiveLearningService->generateLearningPath($student, $course);

        // Get question IDs from recommended questions
        $questionIds = array_column($learningPath['recommended_questions'], 'question_id');

        // If no recommended questions, get random questions from the course
        if (empty($questionIds)) {
            $questions = Question::whereHas('quiz', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })->inRandomOrder()->take(5)->get();
        } else {
            $questions = Question::whereIn('id', $questionIds)->get();
        }

        return view('adaptive-learning.practice-session', [
            'course' => $course,
            'questions' => $questions,
        ]);
    }

    /**
     * Process practice session answers and provide feedback
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processPracticeAnswers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'answers' => 'required|array',
            'answers.*' => 'required',
            'question_ids' => 'required|array',
            'question_ids.*' => 'required|exists:questions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $answers = $request->answers;
        $questionIds = $request->question_ids;

        $feedback = [];
        $correctCount = 0;

        foreach ($questionIds as $index => $questionId) {
            $question = Question::find($questionId);

            if (!$question) {
                continue;
            }

            $answer = $answers[$index] ?? null;
            $questionFeedback = $this->adaptiveLearningService->generateFeedback($question, $answer);

            $feedback[] = [
                'question_id' => $questionId,
                'question' => $question->question,
                'feedback' => $questionFeedback,
            ];

            if ($questionFeedback['is_correct']) {
                $correctCount++;
            }
        }

        $score = count($questionIds) > 0 ? ($correctCount / count($questionIds)) * 100 : 0;

        return response()->json([
            'success' => true,
            'feedback' => $feedback,
            'score' => round($score, 2),
            'correct_count' => $correctCount,
            'total_questions' => count($questionIds),
        ]);
    }

    /**
     * Show interactive question types demo
     *
     * @return \Illuminate\View\View
     */
    public function interactiveQuestionDemo()
    {
        return view('adaptive-learning.question-demo');
    }
}
