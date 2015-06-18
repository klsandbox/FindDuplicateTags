<?php

namespace Klsandbox\FindDuplicateTags;

use Illuminate\Support\ServiceProvider;

class FindDuplicateTagsServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton('command.klsandbox.bladefindduplicates', function($app) {
            return new FindDuplicateTags();
        });
        
        $this->commands('command.klsandbox.bladefindduplicates');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return [
            'command.klsandbox.bladefindduplicates',
        ];
    }

}
