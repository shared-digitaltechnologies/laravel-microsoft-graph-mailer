<?php

namespace Shrd\Laravel\Azure\MicrosoftGraphMailer;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Shrd\Laravel\Azure\Identity\AzureCredentialService;
use Shrd\Laravel\Azure\Identity\ServiceProvider as AzureIdentityServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->app->register(AzureIdentityServiceProvider::class);
    }

    public function boot(): void
    {
        Mail::extend('microsoft-graph', function (array $config) {
            $credentialService = $this->app->get(AzureCredentialService::class);
            $credential = $credentialService->credential($config['credential'] ?? null);

            return new MicrosoftGraphTransport(
                credential: $credential,
                user: $config['user'] ?? null,
                saveToSentItems: boolval($config['save_to_sent_items'] ?? false),
            );
        });
    }
}
