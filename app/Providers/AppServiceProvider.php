<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Blade;
use Illuminate\Support\Facades\View;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

      View()->composer('*', function ($view){
          $user = Auth::user();
          $view->with(['user'=>$user]);
      });

        // Inine an SVG file into the page
        Blade::directive('inlinesvg', function($expression) {
        return "
          <?php
            echo '<!-- SRC : ' . $expression . ' public_path() -->';
            if ( file_exists('/var/www/html/eat-kebab/public' . $expression) ) {
              echo file_get_contents( '/var/www/html/eat-kebab/public' . $expression, false);
            }
          ?>";
        });

        Blade::directive('money_format', function ($expression) {
          return "<?php echo number_format($expression , 2);?>";
        });

        Blade::directive('dd', function ($expression) {
            if (config('app.debug')) {
                return "<?php dd($expression); ?>";
            }
        });
        Blade::directive('log', function ($expression) {
            if (config('app.debug')) {
                return "<?php \Log::info($expression); ?>";
            }
        });
        Blade::directive('debug', function ($expression) {
            if (!config('app.debug')) {
                return "<?php if(false): ?>";
            }
        });
        Blade::directive('enddebug', function ($expression) {
            if (!config('app.debug')) {
                return '<?php endif; ?>';
            }
        });
        Blade::directive('dump', function($param) {
            if (config('app.debug')) {
                return "<?php echo (new Dumper)->dump($param); ?>";
            }
        });
    }
}
