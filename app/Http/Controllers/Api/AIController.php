<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\Message;

use Gemini\Laravel\Facades\Gemini;


class AIController extends Controller{
    function askGemini(Request $request)
    {
        $request->validate([
            'question' => 'required|string'
        ]);

        // Use Gemini 2.5 Flash Preview model
        $response = Gemini::geminiFlashPreview()->generateContent($request->question);

        return response()->json([
            'answer' => $response->text()
        ]);
    }

    // Add this function for OpenAI GPT chat integration
    public function askOpenAI(Request $request)
    {
        $request->validate([
            'messages' => 'required|array|min:1',
            'messages.*.role' => 'required|string|in:system,user,assistant',
            'messages.*.content' => 'required|string',
        ]);

        $apiKey = env('OPENAI_API_KEY');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => $request->messages,
            'max_tokens' => 512,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $aiReply = $data['choices'][0]['message']['content'] ?? '';

            // Store the AI message in the database
            // You need to know the student and counselor IDs (pass them in the request if needed)
            $studentId = $request->input('student_id');
            $counselorId = $request->input('counselor_id');

            if ($studentId && $counselorId) {
                Message::create([
                    'sender_id' => 'ai', // or a dedicated AI user ID
                    'receiver_id' => $studentId, // or $counselorId depending on your logic
                    'content' => $aiReply,
                    'student_id' => $studentId,
                    'counselor_id' => $counselorId,
                ]);
            }

            return response()->json([
                'answer' => $aiReply
            ]);
        }

        return response()->json([
            'message' => 'Failed to get OpenAI response',
            'error' => $response->json()
        ], 500);
    }

    public function gemini25Chat(Request $request)
    {
        $message = $request->input('message');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('ai.api_key'),
            'HTTP-Referer' => config('ai.referer'),
        ])->post(config('ai.api_url'), [
            'model' => config('ai.model'),
            'messages' => [
                ['role' => 'user', 'content' => $message]
            ]
        ]);

        return response()->json($response->json());
    }


    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo', // or 'gpt-4'
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful counselor assistant.'],
                    ['role' => 'user', 'content' => $request->message],
                ],
            ]);

        if ($response->successful()) {
            return response()->json([
                'reply' => $response['choices'][0]['message']['content'],
            ]);
        }

        return response()->json([
            'error' => 'AI response failed.',
            'details' => $response->json(),
        ], $response->status());
    }

    // public function chat(Request $request)
    // {
    //     $message = $request->input('message', 'Hello!');

    //     $response = Http::withHeaders([
    //         'HTTP-Referer' => config('ai.referer'),
    //     ])->post(config('ai.url'), [
    //         'model' => config('ai.model'),
    //         'messages' => [
    //             ['role' => 'user', 'content' => $message]
    //         ]
    //     ]);

    //     return response()->json($response->json());
    // }
}


