<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

         $tmp = storage_path('tmp');
    if (!is_dir($tmp)) {
        @mkdir($tmp, 0775, true);
    }
    // PHP’nin geçici upload klasörünü Laravel storage/tmp olarak ayarla
    @ini_set('upload_tmp_dir', $tmp);
        
    }

}
