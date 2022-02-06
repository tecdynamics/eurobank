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
                ->loadAndPublishViews()
                ->publishAssets();

            $this->app->register(HookServiceProvider::class);
        }
    }
}
