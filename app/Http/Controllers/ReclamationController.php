<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Reclamation;
use Illuminate\Http\Request;

class ReclamationController extends Controller
{
    public function submitReclamationResponse(Request $request, $id)
    {
        $request->validate([
            'response' => 'required|string|max:2000',
        ]);
        $reclamation = Reclamation::findOrFail($id);
        $reclamation->response = $request->response;
        $reclamation->status = 'resolved';
        $reclamation->save();

        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.reclamations')->with('success', 'Response sent and reclamation marked as resolved.');
        }

        return redirect()->route('agent.reclamations')->with('success', 'Response sent and reclamation marked as resolved.');
    }

    
    public function deleteReclamation($id)
    {
        $reclamation = Reclamation::findOrFail($id);
        $reclamation->delete();

        return redirect()->route('admin.reclamations')->with('success', 'Reclamation deleted successfully.');
    }
}
