<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\QuizResult;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Reclamation;
use Illuminate\Support\Facades\DB;

class StudentController extends UserController
{
    public function index(){
        return view('student.LearnerDashboard');
    }

    public function showCourses(){
        $courses = Course::all();
        return view('student.courses', compact('courses'));
    }

    public function showMyCourses(){
        $userId = auth()->id();
        $results = QuizResult::with('quiz.course')
            ->where('user_id', $userId)
            ->get();

        $courses = $results
            ->groupBy(function($result) {
                return $result->quiz->course->id;
            })
            ->map(function($courseResults) {
                return $courseResults->sortByDesc('created_at')->first();
            });

        return view('student.myCourses', ['courses' => $courses]);
    }

    public function showProfile(){
        $user = auth()->user();
        return view('student.profile', compact('user'));
    }

    public function showLeaderboard(){
        $leaders = User::select('users.id', 'users.username')
            ->leftJoin('quiz_results', 'users.id', '=', 'quiz_results.user_id')
            ->selectRaw('COALESCE(SUM(quiz_results.score),0) as total_score')
            ->selectRaw('COUNT(quiz_results.id) as quizzes_count')
            ->groupBy('users.id', 'users.username')
            ->orderByDesc('total_score')
            ->limit(10)
            ->get();

        return view('student.leaderboard', compact('leaders'));
    }

    public function showSupport()
    {
        $user = auth()->user();
        $reclamations = Reclamation::where('user_id', $user->id)->orderByDesc('created_at')->get();
        return view('student.support', compact('reclamations'));
    }

    public function submitSupport(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);
        $user = auth()->user();
        Reclamation::create([
            'user_id' => $user->id,
            'message' => $request->message,
            'status' => 'pending',
        ]);
        return back()->with('success', 'Your request has been submitted!');
    }

    public function showQuiz(){
        return view('student.quiz');
    }

    public function showQuizResult(){
        return view('student.QuizResults');
    }

    public function showCourseContent(){
        return view('student.courseContent');
    }

    public function showAchievements()
    {
        $user = auth()->user();

        $bestResults = QuizResult::where('user_id', $user->id)
            ->select('quiz_id', \DB::raw('MAX(score) as max_score'))
            ->groupBy('quiz_id')
            ->get();

        $totalScore = $bestResults->sum('max_score');
        $quizzesTaken = $bestResults->count();

        $achievements = [
            [
                'title' => 'Getting Started',
                'desc' => 'Score at least 50 points in total.',
                'unlocked' => $totalScore >= 50,
                'tier' => 'bronze',
                'icon' => '🥉',
            ],
            [
                'title' => 'First Steps',
                'desc' => 'Complete your first quiz.',
                'unlocked' => $quizzesTaken >= 1,
                'tier' => 'bronze',
                'icon' => '🥉',
            ],
            [
                'title' => 'Scorer',
                'desc' => 'Score at least 200 points in total.',
                'unlocked' => $totalScore >= 200,
                'tier' => 'silver',
                'icon' => '🥈',
            ],
            [
                'title' => 'Quiz Explorer',
                'desc' => 'Complete 5 different quizzes.',
                'unlocked' => $quizzesTaken >= 5,
                'tier' => 'silver',
                'icon' => '🥈',
            ],
            [
                'title' => 'Gold Scorer',
                'desc' => 'Score at least 500 points in total.',
                'unlocked' => $totalScore >= 500,
                'tier' => 'gold',
                'icon' => '🥇',
            ],
            [
                'title' => 'Quiz Veteran',
                'desc' => 'Complete 10 different quizzes.',
                'unlocked' => $quizzesTaken >= 10,
                'tier' => 'gold',
                'icon' => '🥇',
            ],
            [
                'title' => 'Diamond Legend',
                'desc' => 'Score 1000+ points and complete 20 quizzes.',
                'unlocked' => $totalScore >= 1000 && $quizzesTaken >= 20,
                'tier' => 'diamond',
                'icon' => '💎',
            ],
        ];

        return view('student.achievements', compact('achievements', 'totalScore', 'quizzesTaken'));
    }
    
    public function showProgress(){
        return view('student.progress');
    }

    public function showQuizRules(){
        return view('student.quizRules');
    }

    public function showCourse($id) {
        $course = Course::with(['contents', 'quizzes'])->findOrFail($id);

        return view('student.courseDetails', compact('course'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);
        $user->username = $request->username;
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Old password is incorrect.'])->withInput();
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('password_success', 'Password updated successfully!');
    }
}
