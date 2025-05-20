<?php

namespace App\Http\Controllers\API;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    //  public function conversation(Request $request, $userId)
    // {
    //     $authId = $request->user()->id;

    //     $messages = Message::where(function ($q) use ($authId, $userId) {
    //         $q->where('sender_id', $authId)->where('receiver_id', $userId);
    //     })->orWhere(function ($q) use ($authId, $userId) {
    //         $q->where('sender_id', $userId)->where('receiver_id', $authId);
    //     })->orderBy('created_at')->get();

    //     return response()->json($messages);
    // }
    public function index($counselor_id, Request $request)
    {
        $student_id = $request->query('student_id');
        
        if (!$student_id) {
            return response()->json(['error' => 'student_id query param required'], 422);
        }

        try {
            $messages = Message::where(function ($query) use ($counselor_id, $student_id) {
                $query->where('sender_id', $student_id)
                    ->where('receiver_id', $counselor_id);
            })->orWhere(function ($query) use ($counselor_id, $student_id) {
                $query->where('sender_id', $counselor_id)
                    ->where('receiver_id', $student_id);
            })->orderBy('created_at')->get();

            return response()->json($messages);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch messages',
                'details' => $e->getMessage(),
            ], 500);
        }
    }


    public function send(Request $request)
    {
        $request->validate([
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
        ]);

        return response()->json($message);
    }
}
