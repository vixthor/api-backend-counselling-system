<?php

return [
    'api_key' => env('AI_API_KEY'),
    'referer' => env('AI_REFERER', 'http://localhost'),
    'model' => 'openchat/openchat-3.5-0106',
    'url' => 'https://openrouter.ai/api/v1/chat/completions',
];
