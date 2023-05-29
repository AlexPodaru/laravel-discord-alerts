<?php

namespace Spatie\DiscordAlerts;

use Illuminate\Support\Facades\RateLimiter;

class DiscordAlert
{
    protected string $webhookUrlName = 'default';

    public function to(string $webhookUrlName): self
    {
        $this->webhookUrlName = $webhookUrlName;

        return $this;
    }
    
    public function throttle(string $by, int $decay): self
    {
        if (!empty($by))
            $this->throttleBy = $by;

        if (!empty($decay))
            $this->throttleDecay = $decay;

        return $this;
    }

    public function message(string $text): void
    {
        $webhookUrl = Config::getWebhookUrl($this->webhookUrlName);

        $text = $this->parseNewline($text);

        $jobArguments = [
            'text' => $text,
            'webhookUrl' => $webhookUrl,
        ];

        $job = Config::getJob($jobArguments);

        $rateLimiterKey = 'sendDiscordMsg-'.$this->webhookUrlName.(!empty($this->throttleBy)?'-'.$this->throttleBy:'');
        RateLimiter::attempt(
            $rateLimiterKey,
            $maxAttempts = 1,
            function() use ($job) {
                dispatch($job);
            },
            $this->throttleDecay
        );
    }

    private function parseNewline(string $text): string
    {
        return str_replace('\n', PHP_EOL, $text);
    }
}
