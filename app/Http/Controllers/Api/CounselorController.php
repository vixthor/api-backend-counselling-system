<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Counselor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CounselorController extends Controller
{
    public function show($id)
    {
        $user = User::where('role', 'counselor')
            ->with('counselorProfile')
            ->findOrFail($id);

        return response()->json($user);
    }
    public function index()
    {
        return User::where('role', 'counselor')->with('counselorProfile')->get();
    }

    
    public function setAvailability(Request $request, Counselor $counselor)
    {
        $request->validate([
            'available_at' => 'required|date',
        ]);

        return $counselor->availability()->create([
            'available_at' => $request->available_at,
        ]);
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:Available,Unavailable',
        ]);

        $user = User::where('role', 'counselor')->findOrFail($id);
        $profile = $user->counselorProfile;
        if ($profile) {
            $profile->status = $request->status;
            $profile->save();
            return response()->json(['status' => $profile->status]);
        }
        return response()->json(['error' => 'Profile not found'], 404);
    }
}
