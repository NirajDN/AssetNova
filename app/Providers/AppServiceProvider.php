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

        \Illuminate\Support\Facades\Blade::directive('inr', function ($expression) {
            return "<?php echo '₹' . App\Providers\AppServiceProvider::indian_format($expression); ?>";
        });
        
        \Illuminate\Support\Facades\Blade::directive('indianNumber', function ($expression) {
            return "<?php echo App\Providers\AppServiceProvider::indian_format($expression); ?>";
        });
    }

    public static function indian_format($num)
    {
        if (!is_numeric($num)) return $num;
        
        $num = (string)$num;
        $parts = explode('.', $num);
        $main = $parts[0];
        $decimal = isset($parts[1]) ? '.' . substr($parts[1], 0, 2) : '';
        
        $negative = false;
        if (str_starts_with($main, '-')) {
            $negative = true;
            $main = substr($main, 1);
        }

        if (strlen($main) <= 3) {
            return ($negative ? '-' : '') . $main . $decimal;
        }
        
        $last3 = substr($main, -3);
        $rest = substr($main, 0, -3);
        
        // Group remaining digits by 2
        $rest_formatted = strrev(implode(',', str_split(strrev($rest), 2)));
        
        return ($negative ? '-' : '') . $rest_formatted . ',' . $last3 . $decimal;
    }
}
