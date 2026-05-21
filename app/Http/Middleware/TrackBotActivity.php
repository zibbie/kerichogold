<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackBotActivity
{
    /**
     * Known bot user-agent patterns
     */
    private array $botPatterns = [
        'Googlebot'      => 'Googlebot',
        'Bingbot'        => 'bingbot',
        'GPTBot'         => 'GPTBot',
        'ChatGPT-User'   => 'ChatGPT-User',
        'OAI-SearchBot'  => 'OAI-SearchBot',
        'YandexBot'      => 'YandexBot',
        'Applebot'       => 'Applebot',
        'DuckDuckBot'    => 'DuckDuckBot',
        'Bytespider'     => 'Bytespider',
        'ClaudeBot'      => 'ClaudeBot',
        'PerplexityBot'  => 'PerplexityBot',
    ];

    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);

        $response = $next($request);

        $userAgent = $request->userAgent() ?? '';
        $botName = $this->detectBot($userAgent);

        if ($botName) {
            $responseTimeMs = (int) ((microtime(true) - $startTime) * 1000);

            try {
                DB::table('bot_visits')->insert([
                    'bot_name' => $botName,
                    'url' => $request->fullUrl(),
                    'status_code' => $response->getStatusCode(),
                    'response_time_ms' => $responseTimeMs,
                    'user_agent' => substr($userAgent, 0, 500),
                    'created_at' => now(),
                ]);
            } catch (\Throwable $e) {
                // Silently fail — don't break the response for logging errors
            }
        }

        return $response;
    }

    private function detectBot(string $userAgent): ?string
    {
        foreach ($this->botPatterns as $name => $pattern) {
            if (stripos($userAgent, $pattern) !== false) {
                return $name;
            }
        }

        return null;
    }
}
