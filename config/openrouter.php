<?php

return [
    'api_key' => getenv('OPENROUTER_API_KEY') ?: '',
    'model'   => getenv('OPENROUTER_MODEL') ?: 'google/gemini-2.5-flash',
    'url'     => 'https://openrouter.ai/api/v1/chat/completions',
];
