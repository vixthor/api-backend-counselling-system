<?php

namespace App\Providers;

use Google\Client as GoogleClient;
use Google\Service\AIPlatform as VertexAIService;
use Illuminate\Support\ServiceProvider;
class GeminiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('gemini', function ($app) {
            $client = new GoogleClient();
            $client->setAuthConfig([
                'key' => config('gemini.api_key')
            ]);

            return new VertexAIService($client);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
