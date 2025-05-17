<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

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
    function askOpenAI(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string'
        ]);

        $apiKey = env('OPENAI_API_KEY');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $request->prompt
                ]
            ],
            'max_tokens' => 512,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return response()->json([
                'answer' => $data['choices'][0]['message']['content'] ?? ''
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
        $message = $request->input('message', 'Hello!');

        $response = Http::withHeaders([
            'HTTP-Referer' => config('ai.referer'),
        ])->post(config('ai.url'), [
            'model' => config('ai.model'),
            'messages' => [
                ['role' => 'user', 'content' => $message]
            ]
        ]);

        return response()->json($response->json());
    }
}


