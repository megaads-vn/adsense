<?php 

namespace Megaads\Adsense\Providers;

use Blade;
use Cookie;
use Illuminate\Support\ServiceProvider;
use Megaads\Adsense\Utils\Adsense;

class AdsenseServiceProvider extends ServiceProvider
{

    public function boot() 
    {
        $this->loadViewsFrom(__DIR__ . '/../Views', 'adsense');
        $this->registerMiddleware('Megaads\\Adsense\\Middlewares\\ResourceInjectionMiddleware');
    }

    public function register() 
    {

    }

    protected function registerMiddleware($middleware) {
        $kernel = $this->app['Illuminate\Contracts\Http\Kernel'];
        $kernel->pushMiddleware($middleware);
    }


}