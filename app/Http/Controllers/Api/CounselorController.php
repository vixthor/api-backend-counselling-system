<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Counselor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CounselorController extends Controller
{
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
}
