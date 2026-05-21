<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CrawlLog;
use Carbon\Carbon;

class ParseCrawlLogs extends Command
{
    protected $signature = 'logs:parse-crawl';
    protected $description = 'Parse Nginx access logs to extract bot activity';

    protected $bots = [
        'Googlebot' => 'Google',
        'bingbot' => 'Bing',
        'YandexBot' => 'Yandex',
        'GPTBot' => 'OpenAI (GPT)',
        'ClaudeBot' => 'Anthropic (Claude)',
        'AhrefsBot' => 'Ahrefs',
        'SemrushBot' => 'Semrush',
        'PetalBot' => 'Petal',
        'facebookexternalhit' => 'Facebook',
        'Twitterbot' => 'Twitter',
    ];

    public function handle()
    {
        $logPath = storage_path('logs/nginx/access.log');

        if (!file_exists($logPath)) {
            $this->error("Log file not found at: {$logPath}");
            return;
        }

        $lines = file($logPath);
        $count = 0;

        foreach ($lines as $line) {
            if ($data = $this->parseLine($line)) {
                // Check if already exists to avoid duplicates (simplified check by URL and timestamp)
                $exists = CrawlLog::where('url', $data['url'])
                    ->where('crawled_at', $data['crawled_at'])
                    ->exists();

                if (!$exists) {
                    CrawlLog::create($data);
                    $count++;
                }
            }
        }

        $this->info("Parsed {$count} new bot entries.");
    }

    protected function parseLine($line)
    {
        // Standard Nginx Log Format
        $regex = '/^(\S+) \S+ \S+ \[(.+)\] "(\S+) (.*?) \S+" (\d{3}) (\d+) "(.*?)" "(.*?)"$/';
        
        if (preg_match($regex, $line, $matches)) {
            $ip = $matches[1];
            $time = $matches[2];
            $method = $matches[3];
            $url = $matches[4];
            $status = (int) $matches[5];
            $userAgent = $matches[8];

            $botName = null;
            foreach ($this->bots as $key => $name) {
                if (stripos($userAgent, $key) !== false) {
                    $botName = $name;
                    break;
                }
            }

            if ($botName) {
                return [
                    'bot_name' => $botName,
                    'url' => $url,
                    'status_code' => $status,
                    'ip_address' => $ip,
                    'user_agent' => $userAgent,
                    'crawled_at' => Carbon::createFromFormat('d/M/Y:H:i:s O', $time),
                ];
            }
        }

        return null;
    }
}
