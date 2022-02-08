<?php

namespace Botble\Eurobank\Providers;

use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\ServiceProvider;

class EurobankServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * @throws FileNotFoundException
     */
    public function boot()
    {
        if (is_plugin_active('payment')) {
            $this->setNamespace('plugins/eurobank')
                ->loadHelpers()
                ->loadRoutes(['web'])
                ->loadAndPublishTranslations()
                ->loadAndPublishViews()
                ->loadMigrations()
                ->publishAssets();

            $this->app->booted(function () {
                $this->app->make('config')->set([
                    'eurobank.key' => get_payment_setting('api_key', EUROBANK_PAYMENT_METHOD_NAME),
                ]);

                $this->app->register(HookServiceProvider::class);
            });
             }
    }
}
