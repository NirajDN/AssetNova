<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private string $apiKey;
    private string $model   = 'gemini-2.0-flash';
    private string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key', '');
    }

    /**
     * Send a prompt to Gemini and return the text response.
     */
    public function ask(string $prompt): string
    {
        if (empty($this->apiKey)) {
            return 'AI is not configured. Please set GEMINI_API_KEY in your environment.';
        }

        try {
            $response = Http::timeout(30)
                ->post("{$this->baseUrl}/{$this->model}:generateContent?key={$this->apiKey}", [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]]
                    ],
                    'generationConfig' => [
                        'temperature'     => 0.4,
                        'maxOutputTokens' => 1024,
                    ],
                ]);

            if ($response->failed()) {
                Log::error('Gemini API error', ['status' => $response->status(), 'body' => $response->body()]);
                return 'Sorry, I could not reach the AI service right now. Please try again later.';
            }

            $data = $response->json();
            return $data['candidates'][0]['content']['parts'][0]['text']
                ?? 'No response from AI.';

        } catch (\Exception $e) {
            Log::error('GeminiService exception', ['error' => $e->getMessage()]);
            return 'An error occurred while contacting the AI. Please try again.';
        }
    }
}
