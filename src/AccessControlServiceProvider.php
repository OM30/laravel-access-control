<?php

namespace pierresilva\AccessControl;

use Blade;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AccessControlServiceProvider extends ServiceProvider
{
    /**
     * Indicates of loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the service provider.
     *
     * @return null
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/access-control.php' => config_path('access-control.php'),
        ]);

        if (version_compare(Application::VERSION, '5.3.0', '<')) {
            $this->publishes([
                __DIR__ . '/../migrations' => $this->app->databasePath() . '/migrations',
            ], 'migrations');
        } else {
            if (config('access-control.run-migrations', true)) {
                $this->loadMigrationsFrom(__DIR__ . '/../migrations');
            }
        }

        include __DIR__.'/Routes/api.php';

        $this->registerBladeDirectives();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/access-control.php', 'access-control'
        );

        $this->app->singleton('access-control', function ($app) {
            $auth = $app->make('Illuminate\Contracts\Auth\Guard');

            return new \pierresilva\AccessControl\AccessControl($auth);
        });

        $this->app->make('pierresilva\AccessControl\Controllers\PermissionsController');
        $this->app->make('pierresilva\AccessControl\Controllers\RolesController');
    }

    /**
     * Register the blade directives.
     *
     * @return void
     */
    protected function registerBladeDirectives()
    {
        Blade::directive('can', function ($expression) {
            return "<?php if (\\AccessControl::can({$expression})): ?>";
        });

        Blade::directive('endcan', function ($expression) {
            return '<?php endif; ?>';
        });

        Blade::directive('canatleast', function ($expression) {
            return "<?php if (\\AccessControl::canAtLeast({$expression})): ?>";
        });

        Blade::directive('endcanatleast', function ($expression) {
            return '<?php endif; ?>';
        });

        Blade::directive('role', function ($expression) {
            return "<?php if (\\AccessControl::isRole({$expression})): ?>";
        });

        Blade::directive('endrole', function ($expression) {
            return '<?php endif; ?>';
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['access-control'];
    }
}
