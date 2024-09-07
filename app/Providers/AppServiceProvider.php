<?php

namespace App\Providers;

use App\Choice;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('valid_choice_for_poll', function ($attribute, $value, $parameters, $validator) {
            
            $pollId = $parameters[0] ?? null;

            if (!$pollId)
                return false;

            return  Choice::where('id', $value)
                ->where('poll_id', $pollId)
                ->exists();
        });
    }
}
