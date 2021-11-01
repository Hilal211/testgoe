<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Using class based composers...
        view()->composer(
            'admin.layout.default', 'App\Http\ViewComposers\AdminCounterComposer'
        );

        view()->composer(
            'store.layout.default', 'App\Http\ViewComposers\StoreCounterComposer'
        );

        view()->composer(
            'frontend.*', 'App\Http\ViewComposers\FrontEndComposer'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}