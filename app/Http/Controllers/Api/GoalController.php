<?php

namespace App\Http\Controllers\Api;

use App\Models\Goal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoalController extends Controller
{
    /**
     * Get all goals for the authenticated user with search and pagination.
     */
    public function index(Request $request)
    {
        $query = Goal::where('user_id', auth()->id());

        // Search by title
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Paginate results
        $goals = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($goals);
    }

    /**
     * Create a new goal.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'timeframe' => 'required|string',
            'preferred_time' => 'nullable|string',
        ]);

        $goal = auth()->user()->goals()->create($data);

        return response()->json([
            'message' => 'Goal created successfully',
            'goal' => $goal,
        ], 201);
    }

    /**
     * Update an existing goal.
     */
    public function update(Request $request, $id)
    {
        $goal = auth()->user()->goals()->findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'timeframe' => 'required|string',
            'preferred_time' => 'nullable|string',
            'completed' => 'boolean',
        ]);

        $goal->update($data);

        return response()->json([
            'message' => 'Goal updated successfully',
            'goal' => $goal,
        ]);
    }

    /**
     * Delete a goal.
     */
    public function destroy($id)
    {
        $goal = auth()->user()->goals()->findOrFail($id);
        $goal->delete();

        return response()->json(['message' => 'Goal deleted successfully']);
    }

    /**
     * Track progress for a goal.
     */
    public function trackProgress($id)
    {
        $goal = auth()->user()->goals()->findOrFail($id);

        return response()->json([
            'goal' => $goal,
            'progress' => [
                'completed' => $goal->completed,
                'description' => $goal->description,
            ],
        ]);
    }
}
