<?php

namespace nickknissen\QuickPay;

use Illuminate\Support\ServiceProvider;


class QuickPayServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/quickpay.php' => config_path('quickpay.php'),
        ], 'config');

        if (! class_exists('CreateCardsTable')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/../database/migrations/create_cards_tables.php.stub' => $this->app->databasePath()."/migrations/{$timestamp}_create_cards_tables.php",
            ], 'migrations');
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('quickpay', function ($app) {
            return new QuickPay();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['quickpay'];
    }
}
