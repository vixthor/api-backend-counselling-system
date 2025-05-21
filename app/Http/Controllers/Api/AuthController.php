<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Models\Student;
use App\Models\Counselor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    // public function login(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|string',
    //     ]);

    //     // Attempt to authenticate the user
    //     if (!Auth::attempt($credentials)) {
    //         return response()->json(['message' => 'Invalid credentials'], 401);
    //     }

    //     // Get the authenticated user
    //     $user = Auth::user();

    //     // Generate Sanctum token
    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     // Build the base response
    //     $response = [
    //         'message' => 'Login successful',
    //         'access_token' => $token,
    //         'token_type' => 'Bearer',
    //         'user' => $user,
    //     ];

    //     // Add redirect path based on role
    //     if ($user->isStudent()) {
    //         $response['redirect_to'] = '/studentsdashboard';
    //     } elseif ($user->isCounselor()) {
    //         $response['redirect_to'] = '/counselor';
    //     } else {
    //         return response()->json(['message' => 'User role not recognized'], 403);
    //     }

    //     return response()->json($response);
    // }



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
    
    public function updateStudentSettings(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            // Remove 'nationality' and 'timezone' if not used in your form
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6',
            'confirm_password' => 'nullable|string|same:new_password',
        ]);

        // Update basic info
        $user->name = $data['name'];
        $user->email = $data['email'];

        // Handle password change only if all fields are provided
        if (!empty($data['current_password']) && !empty($data['new_password']) && !empty($data['confirm_password'])) {
            if (!\Hash::check($data['current_password'], $user->password)) {
                return response()->json(['message' => 'Current password is incorrect'], 422);
            }
            $user->password = bcrypt($data['new_password']);
        }

        $user->save();

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    }    // public function me(Request $request)    // {    //     return response()->json($request->user());
    // }

    public function updateStudentSettingsById(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Validate and update fields as needed
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email',
            // Add other fields as needed
        ]);

        $user->update($validated);

        // Handle password change if provided
        if ($request->filled('current_password') && $request->filled('new_password')) {
            if (!\Hash::check($request->current_password, $user->password)) {
                return response()->json(['error' => 'Current password is incorrect'], 400);
            }
            $user->password = bcrypt($request->new_password);
            $user->save();
        }

        return response()->json(['message' => 'Profile updated successfully!', 'user' => $user]);
    }
}