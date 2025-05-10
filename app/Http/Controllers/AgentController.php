<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use App\Models\User;
use App\Models\QuizResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentController extends UserController
{
    public function index()
    {
        $totalReclamations = Reclamation::count();
        $unresolvedReclamations = Reclamation::where('status', '!=', 'resolved')->count();
        $resolvedReclamations = Reclamation::where('status', 'resolved')->count();

        $statusCounts = Reclamation::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

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

        return view('agent.AgentDashboard', compact('totalReclamations', 'unresolvedReclamations', 'resolvedReclamations', 'statusCounts', 'leaders'));
    }

    public function showReclamations() {
        $reclamations = Reclamation::all();
        return view('agent.AgentReclamations', compact('reclamations'));
    }

    public function respondReclamation($id)
    {
        $reclamation = Reclamation::with('user')->findOrFail($id);
        return view('agent.respondReclamation', compact('reclamation'));
    }
}
