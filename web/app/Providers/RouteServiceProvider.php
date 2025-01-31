<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

use App\Helpers\DomainHelper;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/my/dashboard';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
			//
			// Domain: virtubrick.net
			//
			Route::domain(DomainHelper::TopLevelDomain())
                ->middleware('web')
				->namespace('App\Http\Controllers\Web')
                ->group(base_path('routes/web.php'));
			
			//
			// Domain: www.virtubrick.net
			//
            Route::domain('www.' . DomainHelper::TopLevelDomain())
                ->middleware('web')
				->namespace('App\Http\Controllers\Web')
                ->group(base_path('routes/web.php'));
			
			//
			// Domain: www.virtubrick.net
			//
            Route::domain('blog.' . DomainHelper::TopLevelDomain())
                ->middleware('web')
				->namespace('App\Http\Controllers\Blog')
				->name('blog.')
                ->group(base_path('routes/blog.php'));
			
			//
			// Domain: api.virtubrick.net
			//
            Route::domain('api.' . DomainHelper::TopLevelDomain())
                ->middleware('api')
				->namespace('App\Http\Controllers\Api')
                ->group(base_path('routes/api.php'));
			
			//
			// Domain: clientsettings.api.virtubrick.net
			//
            Route::domain('clientsettings.api.' . DomainHelper::TopLevelDomain())
                ->middleware('api')
				->namespace('App\Http\Controllers\ClientSettings')
                ->group(base_path('routes/clientsettings.php'));
				
			//
			// Domain: cdn.virtubrick.net
			//
            Route::domain('cdn.' . DomainHelper::TopLevelDomain())
                ->middleware('api')
				->namespace('App\Http\Controllers\Cdn')
                ->group(base_path('routes/cdn.php'));
			
			//
			// Domain: setup.virtubrick.net
			//
            Route::domain('setup.' . DomainHelper::TopLevelDomain())
                ->middleware('api')
				->namespace('App\Http\Controllers\Setup')
                ->group(base_path('routes/setup.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        //
    }
}
