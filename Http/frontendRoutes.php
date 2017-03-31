<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->group(['prefix' => ''], function (Router $router) {
    $router->get(LaravelLocalization::transRoute('news::routes.news.index'), [
        'as' => 'news.index',
        'uses' => 'PublicController@index',
        'middleware' => config('asgard.news.config.middleware'),
    ]);
    $router->get(LaravelLocalization::transRoute('news::routes.news.search'), [
        'as' => 'news.search',
        'uses' => 'PublicController@search',
        'middleware' => config('asgard.news.config.middleware'),
    ]);
    $router->get(LaravelLocalization::transRoute('news::routes.news.slug'), [
        'as' => 'news.slug',
        'uses' => 'PublicController@show',
        'middleware' => config('asgard.news.config.middleware'),
    ]);
    $router->get(LaravelLocalization::transRoute('news::routes.category.slug'), [
        'as' => 'news.category',
        'uses' => 'PublicController@category',
        'middleware' => config('asgard.news.config.middleware'),
    ]);
    $router->get(LaravelLocalization::transRoute('news::routes.news.tag'), [
        'as' => 'news.tag',
        'uses' => 'PublicController@tagged',
        'middleware' => config('asgard.news.config.middleware'),
    ]);
});