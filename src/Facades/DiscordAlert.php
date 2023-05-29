<?php

namespace Spatie\DiscordAlerts\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static self to(string $text)
 * @method static self throttle(string $text, int $seconds)
 * @method static void message(string $text)
 *
 * @see \Spatie\DiscordAlerts\DiscordAlert
 */
class DiscordAlert extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-discord-alerts';
    }
}
