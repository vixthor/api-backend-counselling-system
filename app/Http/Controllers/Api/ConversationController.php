<?php

namespace App\Http\Controllers\Api;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Student;
use App\Models\Counselor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConversationController extends Controller
{
    /**
     * Start a new conversation between a student and a counselor.
     */
    public function startConversation(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'counselor_id' => 'nullable|exists:counselors,id', // Optional for AI bot
        ]);

        // Check if a conversation already exists between the student and counselor
        $existingConversation = Conversation::where('participant1_id', $data['student_id'])
            ->where('participant2_id', $data['counselor_id'])
            ->first();

        if ($existingConversation) {
            return response()->json([
                'message' => 'Conversation already exists.',
                'conversation_id' => $existingConversation->id,
            ], 200);
        }

        // Create a new conversation
        $conversation = Conversation::create([
            'participant1_id' => $data['student_id'],
            'participant2_id' => $data['counselor_id'], // Null if AI bot
        ]);

        return response()->json([
            'message' => 'Conversation started successfully.',
            'conversation_id' => $conversation->id,
        ], 201);
    }

    /**
     * Fetch conversation details.
     */
    public function getConversation($id)
    {
        $conversation = Conversation::with(['participant1.user', 'participant2.user'])->findOrFail($id);

        return response()->json([
            'conversation_id' => $conversation->id,
            'student_name' => $conversation->participant1->getDisplayNameAttribute(),
            'counselor_name' => $conversation->participant2->user->name ?? 'AI Bot',
        ]);
    }

    /**
     * Send a message in a conversation.
     */
    public function sendMessage(Request $request)
    {
        $data = $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'sender_id' => 'required|integer',
            'message' => 'required|string',
        ]);

        $conversation = Conversation::findOrFail($data['conversation_id']);

        // Ensure the sender is part of the conversation
        if (
            $conversation->participant1_id !== $data['sender_id'] &&
            $conversation->participant2_id !== $data['sender_id']
        ) {
            return response()->json(['error' => 'Sender is not part of this conversation.'], 403);
        }

        // Create the message
        $message = Message::create([
            'conversation_id' => $data['conversation_id'],
            'sender_id' => $data['sender_id'],
            'message' => $data['message'],
        ]);

        return response()->json([
            'message' => 'Message sent successfully.',
            'data' => $message,
        ], 201);
    }

    /**
     * Fetch messages in a conversation.
     */
    public function getMessages($conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);

        $messages = Message::where('conversation_id', $conversation->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'conversation_id' => $conversation->id,
            'messages' => $messages,
        ]);
    }
}
