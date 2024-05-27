<?php

namespace App\Providers;

use App\Extensions\Validation\SpecValidator;
use Illuminate\Support\{ServiceProvider, Str, Stringable};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        Str::macro('sentence', 'toSentenceCase');
        Stringable::macro('sentence', function () {return new Stringable(toSentenceCase($this->value)); });
        $this->app->make('validator')->resolver(function ($translator, $data, $rules, $messages, $params) {
            return new SpecValidator($translator, $data, $rules, $messages, $params);
        });
    }
}
