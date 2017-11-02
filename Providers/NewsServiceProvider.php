<?php

namespace Modules\News\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Traits\CanGetSidebarClassForModule;
use Modules\News\Entities\Category;
use Modules\News\Entities\Post;
use Modules\News\Events\Handlers\RegisterNewsSidebar;
use Modules\News\Facades\NewsCategoryFacade;
use Modules\News\Facades\NewsFacade;
use Modules\News\Repositories\Cache\CacheCategoryDecorator;
use Modules\News\Repositories\Cache\CachePostDecorator;
use Modules\News\Repositories\CategoryRepository;
use Modules\News\Repositories\Eloquent\EloquentCategoryRepository;
use Modules\News\Repositories\Eloquent\EloquentPostRepository;
use Modules\News\Repositories\PostRepository;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Media\Image\ThumbnailManager;
use Modules\Tag\Repositories\TagManager;

class NewsServiceProvider extends ServiceProvider
{
    use CanPublishConfiguration, CanGetSidebarClassForModule;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
        $this->registerFacades();

        $this->app->extend('asgard.ModulesList', function($app) {
            array_push($app, 'news');
            return $app;
        });

        $this->app['events']->listen(
          BuildingSidebar::class,
          $this->getSidebarClassForModule('news', RegisterNewsSidebar::class)
        );
    }

    public function boot()
    {
        $this->publishConfig('news', 'config');
        $this->publishConfig('news', 'permissions');
        $this->publishConfig('news', 'settings');
        $this->app[TagManager::class]->registerNamespace(new Post());
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        //$this->registerThumbnails();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    private function registerBindings()
    {
        $this->app->bind(PostRepository::class, function () {
            $repository = new EloquentPostRepository(new \Modules\News\Entities\Post());

            if (config('app.cache') === false) {
                return $repository;
            }

            return new CachePostDecorator($repository);
        });

        $this->app->bind(CategoryRepository::class, function () {
            $repository = new EloquentCategoryRepository(new Category());

            if (config('app.cache') === false) {
                return $repository;
            }

            return new CacheCategoryDecorator($repository);
        });
    }

    private function registerThumbnails()
    {
        $this->app[ThumbnailManager::class]->registerThumbnail('smallThumb', [
            'fit' => [
                'width' => '150',
                'height' => '150',
                'callback' => function ($constraint) {
                    $constraint->upsize();
                },
            ],
        ]);
    }

    private function registerFacades()
    {
        $aliasLoader = AliasLoader::getInstance();
        $aliasLoader->alias('News', NewsFacade::class);
        $aliasLoader->alias('NewsCategory', NewsCategoryFacade::class);
    }
}
