<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Models\Student;
use App\Models\Counselor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
   



    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate the user
        if (!auth()->attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Get the authenticated user
        $user = auth()->user();

        // Check the user's role and redirect accordingly
        if ($user->isStudent()) {
            return response()->json([
                'message' => 'Login successful',
                'redirect_to' => '/studentsdashboard', // Redirect to student dashboard
                'user' => $user,
            ]);
        } elseif ($user->isCounselor()) {
            return response()->json([
                'message' => 'Login successful',
                'redirect_to' => '/counselor', // Redirect to counselor dashboard
                'user' => $user,
            ]);
        }

        // If the role is not recognized, return an error
        return response()->json(['message' => 'User role not recognized'], 403);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'student_id' => 'required|digits:9|unique:students,student_id',
        ]);

        $student = Student::create($data);

        return response()->json([
            'message' => 'Registration successful',
            'student' => $student,
        ]);
    }
}
 // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email'    => ['required', 'email'],
    //         'password' => ['required'],
    //     ]);

    //     $user = User::where('email', $request->email)->first();

    //     if (! $user || ! Hash::check($request->password, $user->password)) {
    //         return response()->json(['message' => 'Invalid credentials'], 401);
    //     }

    //     // Generate token and attach user role as ability
    //     $token = $user->createToken('auth_token', [$user->role])->plainTextToken;

    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type'   => 'Bearer',
    //         'user'         => $user,
    //     ]);
    // }

    // public function logout(Request $request)
    // {
    //     $request->user()->currentAccessToken()->delete();

    //     return response()->json(['message' => 'Logged out successfully']);
    // }

    // public function me(Request $request)
    // {
    //     return response()->json($request->user());
    // }