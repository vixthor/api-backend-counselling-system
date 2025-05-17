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
}
