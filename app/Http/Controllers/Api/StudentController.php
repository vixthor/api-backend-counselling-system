<?php

namespace App\Http\Controllers\Api;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentController extends Controller
{
    public function scheduleAppointment(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'counselor_id' => 'required|exists:counselors,id',
            'scheduled_time' => 'required|date',
        ]);

        return Appointment::create($data);
    }
}
