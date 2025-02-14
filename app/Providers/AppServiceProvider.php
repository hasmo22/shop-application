<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\PaymentProcessorInterface;
use App\Services\FakePaymentProcessor;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentProcessorInterface::class, FakePaymentProcessor::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
