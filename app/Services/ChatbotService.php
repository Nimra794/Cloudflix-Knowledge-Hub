<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ChatbotService
{
    public function send($message)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('chatbot.api.key'),
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => config('chatbot.api.model', 'gpt-3.5-turbo'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful movie recommendation assistant. Provide concise, informative responses about movies, actors, and the platform. Focus on helping users find movies they might enjoy based on their preferences. Keep responses brief and engaging.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $message
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 150,
            ]);

            if ($response->successful()) {
                return $response->json()['choices'][0]['message']['content'];
            }

            return 'I apologize, but I encountered an error processing your request. Please try again.';
        } catch (\Exception $e) {
            return 'I apologize, but I encountered an error processing your request. Please try again.';
        }
    }
} 