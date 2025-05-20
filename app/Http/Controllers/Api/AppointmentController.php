<?php

namespace App\Http\Controllers\Api;

use App\Models\Student;
use App\Models\Counselor;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->role === 'student') {
            $appointments = Appointment::where('student_id', $user->id)->with(['counselor'])->get();
        } elseif ($user->role === 'counselor') {
            $appointments = Appointment::where('counselor_id', $user->id)->with(['student'])->get();
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($appointments);
    }

    public function indexById(Request $request, $id)
    {
        // Optionally, accept a ?role=student|counselor query param
        $role = $request->query('role', 'student'); // default to student

        if ($role === 'student') {
            $appointments = Appointment::where('student_id', $id)->with(['counselor'])->get();
        } elseif ($role === 'counselor') {
            $appointments = Appointment::where('counselor_id', $id)->with(['student'])->get();
        } else {
            return response()->json(['error' => 'Invalid role'], 400);
        }

        return response()->json($appointments);
    }

    public function confirm(Appointment $appointment)
    {
        return response()->json([
            'confirmed' => true,
            'data' => $appointment,
        ]);
    }
    public function show($id)
    {
        $appointment = Appointment::with(['student', 'counselor'])->findOrFail($id);

        return response()->json([
            'appointment_id' => $appointment->id,
            'scheduled_time' => $appointment->scheduled_time,
            'student_name' => $appointment->student->display_name,
            'counselor_name' => $appointment->counselor->name,
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'      => 'required|exists:users,id',
            'counselor_id'    => 'required|exists:users,id',
            'session_topic'   => 'required|string|max:255',
            'preferred_date'  => 'required|date',
            'preferred_time'  => 'required|string|max:50',
            'notes'           => 'nullable|string',
            'email'           => 'nullable|email|max:255',
        ]);
        // dd($validated);
        $appointment = Appointment::create([
            'student_id'      => $validated['student_id'],
            'counselor_id'    => $validated['counselor_id'],
            'session_topic'   => $validated['session_topic'] ?? null,
            'preferred_date'  => $validated['preferred_date'],
            'preferred_time'  => $validated['preferred_time'],
            'email'          => $validated['email'] ?? null,
            'notes'           => $validated['notes'] ?? null,
            'status'          => 'Pending', // default status
            
        ]);
        
        return response()->json([
            'message' => 'Appointment request sent successfully.',
            'data'    => $appointment,
        ], 201);
    }
}
