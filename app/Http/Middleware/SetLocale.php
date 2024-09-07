<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App;
use Config;
use Carbon\Carbon;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $available = ['en', 'ar'];
        // Get Language
        $locale    = $request->get('lang');
        // Check if request new language
        if ($locale) {

            // Check if language exists
            if (in_array($locale, $available)) {
                // Update Session
                Session::put('locale', $locale);

                if ($locale == 'ar') {

                    Config::set('app.rtl', true);
                }

                // Set Language
                App::setLocale($locale);

                // Change Carbon lang
                Carbon::setlocale($locale);

                // Return request
                return $next($request);
            } else {
                Session::put('locale', 'en');
                Config::set('app.rtl', false);
                // Return request
                return $next($request);
            }
        } else {

            // Check if session exists
            if (Session::has('locale')) {

                $locale = Session::get('locale', Config::get('app.locale'));

                if ($locale == 'ar') {

                    Config::set('app.rtl', true);
                }

                // Set Language
                App::setLocale($locale);

                // Change Carbon lang
                Carbon::setlocale($locale);

                // Return request
                return $next($request);
            } else {

                // Set Language
                App::setLocale('ar');
                Session::put('locale', 'ar');

                // Change Carbon lang
                Carbon::setlocale('ar');

                // Return request
                return $next($request);
            }
        }
    }
}
