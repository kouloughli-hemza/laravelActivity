<?php

namespace Kouloughli\UserActivity;

use Event;
use Kouloughli\UserActivity\Listeners\DirectionEventsSubscriber;
use Kouloughli\UserActivity\Listeners\FileEventsSubscriber;
use Route;
use Kouloughli\Plugins\Plugin;
use Kouloughli\Support\Sidebar\Item;
use Kouloughli\UserActivity\Http\View\Composers\ShowUserComposer;
use Kouloughli\UserActivity\Listeners\PermissionEventsSubscriber;
use Kouloughli\UserActivity\Listeners\RoleEventsSubscriber;
use Kouloughli\UserActivity\Listeners\UserEventsSubscriber;
use Kouloughli\UserActivity\Repositories\Activity\ActivityRepository;
use Kouloughli\UserActivity\Repositories\Activity\EloquentActivity;
use Illuminate\Database\Eloquent\Factory;
use View;

class UserActivity extends Plugin
{
    /**
     * {@inheritDoc}
     */
    public function sidebar()
    {
        return Item::create(__('Activity Log'))
            ->route('activity.index')
            ->icon('shuffle')
            ->active("activity*")
            ->permissions('users.activity');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ActivityRepository::class, EloquentActivity::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'user-activity');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'user-activity');
        $this->loadJsonTranslationsFrom(__DIR__.'/../resources/lang');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations')
        ], 'migrations');

        $this->app->booted(function () {
            $this->mapWebRoutes();

            if ($this->app['config']->get('auth.expose_api')) {
                $this->mapApiRoutes();
            }
        });

        $this->attachViewComposers();

        $this->registerEventListeners();

        $this->loadTestingFactories();
    }

    /**
     * Map web plugin related routes.
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'namespace' => 'Kouloughli\UserActivity\Http\Controllers\Web',
            'middleware' => 'web',
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });
    }

    /**
     * Map API plugin related routes.
     */
    protected function mapApiRoutes()
    {
        Route::group([
            'namespace' => 'Kouloughli\UserActivity\Http\Controllers\Api',
            'middleware' => 'api',
            'prefix' => 'api',
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        });
    }

    /**
     * Register event subscribers for the plugin.
     */
    private function registerEventListeners()
    {
        Event::subscribe(PermissionEventsSubscriber::class);
        Event::subscribe(RoleEventsSubscriber::class);
        Event::subscribe(UserEventsSubscriber::class);
        Event::subscribe(DirectionEventsSubscriber::class);
        Event::subscribe(FileEventsSubscriber::class);
    }

    /**
     * Attach view composers to add necessary data to the view.
     */
    private function attachViewComposers()
    {
        View::composer('user.view', ShowUserComposer::class);
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function loadTestingFactories()
    {
        if (! $this->app->environment('production') && $this->app->runningInConsole()) {
            $this->app->make(Factory::class)->load(__DIR__ . '/../database/factories');
        }
    }
}
