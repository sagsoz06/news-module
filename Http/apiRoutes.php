<?php

use Illuminate\Routing\Router;

$router->group(['prefix' => 'v1/news', 'middleware' => 'api.token'], function (Router $router) {
    $router->get('categories', [
        'as' => 'api.news.category.index',
        'uses' => 'V1\CategoryController@index',
        'middleware' => 'token-can:news.categories.index',
    ]);

    $router->post('categories', [
        'as' => 'api.news.category.store',
        'uses' => 'V1\CategoryController@store',
        'middleware' => 'token-can:news.categories.create',
    ]);

    $router->post('categories/{category}', [
        'as' => 'api.news.category.update',
        'uses' => 'V1\CategoryController@update',
        'middleware' => 'token-can:news.categories.edit',
    ]);

    $router->delete('categories/{category}', [
        'as' => 'api.news.category.destroy',
        'uses' => 'V1\CategoryController@destroy',
        'middleware' => 'token-can:news.categories.destroy',
    ]);
});
