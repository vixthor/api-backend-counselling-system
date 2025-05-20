<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class DeepSeekController extends Controller
{
    public function chat(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('DEEPSEEK_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.deepseek.com/v1/chat/completions', [
            'model' => 'deepseek-chat', // Check DeepSeek's latest model
            'messages' => [
                ['role' => 'user', 'content' => $request->input('message')],
            ],
        ]);

        return $response->json();
    }
}
