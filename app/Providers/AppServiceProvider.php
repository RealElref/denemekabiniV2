<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(UrlGenerator $url): void
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            $url->forceScheme('https');
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $url->forceRootUrl('https://' . $_SERVER['HTTP_X_FORWARDED_HOST']);
        }
    }
}
