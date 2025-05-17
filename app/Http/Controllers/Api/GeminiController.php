<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GeminiController extends Controller
{
    public function askGemini(Request $request)
    {
        $request->validate([
            'question' => 'required|string'
        ]);

        $response = Gemini::geminiPro()->generateContent($request->question);

        return response()->json([
            'answer' => $response->text()
        ]);
    }
}
