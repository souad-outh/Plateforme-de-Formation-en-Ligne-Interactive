<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Reclamation;
use App\Models\User;
use App\Models\QuizResult;
use Illuminate\Support\Facades\DB;

class AdminController extends AgentController
{
    public function showAdmin(){
        $totalUsers = User::count();
        $activeCourses = Course::count();
        $quizzesTaken = QuizResult::count();

        $latestResults = QuizResult::select('id', 'user_id', 'quiz_id', 'score')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('quiz_results')
                    ->groupBy('user_id', 'quiz_id');
            });

        $leaders = User::select('users.id', 'users.username')
            ->leftJoinSub($latestResults, 'latest_results', function($join) {
                $join->on('users.id', '=', 'latest_results.user_id');
            })
            ->selectRaw('COALESCE(SUM(latest_results.score),0) as total_score')
            ->selectRaw('COUNT(DISTINCT latest_results.quiz_id) as quizzes_count')
            ->groupBy('users.id', 'users.username')
            ->orderByDesc('total_score')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('totalUsers', 'activeCourses', 'quizzesTaken', 'leaders'));
    }

    public function showUsers(){
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function showReclamations() {
        $reclamations = Reclamation::all();
        return view('admin.reclamations', compact('reclamations'));
    }

    public function showCourses() {
        $courses = Course::all();
        return view('admin.courses', compact('courses'));
    }

    public function createCourse() {
        $categories = Category::all();

        return view('admin.createCourse', compact('categories'));
    }

    public function showCourse($id)
    {
        $course = Course::with(['contents', 'quizzes.questions'])->findOrFail($id);
        return view('admin.courseDetails', compact('course'));
    }

    public function showQuizzes() {
        $quizzes = Quiz::with('course.category' )->get();

        return view('admin.quizzes', compact('quizzes'));
    }

    public function showQuizQuestions($id) {
        $quiz = Quiz::with('questions')->findOrFail($id);
        return view('admin.quizQuestions', compact('quiz'));
    }

    public function updateRole(Request $request, $id) {
        $request->validate([
            'role' => 'required|in:admin,agent,user',
        ]);

        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        return redirect()->route('admin.users')->with('success', 'User role updated successfully.');
    }

    public function BanUser($id) {
        $user = User::findOrFail($id);
        $user->is_banned = true;
        $user->save();

        return redirect()->route('admin.users')->with('success', 'User banned successfully.');
    }

    public function UnbanUser($id) {
        $user = User::findOrFail($id);
        $user->is_banned = false;
        $user->save();

        return redirect()->route('admin.users')->with('success', 'User unbanned successfully.');
    }

    public function respondReclamation($id)
    {
        $reclamation = Reclamation::with('user')->findOrFail($id);
        return view('admin.respondReclamation', compact('reclamation'));
    }
}
