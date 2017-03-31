<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->group(['prefix' => '/news'], function (Router $router) {

    $router->bind('news', function ($id) {
        return app(\Modules\News\Repositories\PostRepository::class)->find($id);
    });

    $router->get('posts', [
        'as' => 'admin.news.post.index',
        'uses' => 'PostController@index',
        'middleware' => 'can:news.posts.index',
    ]);
    $router->get('posts/create', [
        'as' => 'admin.news.post.create',
        'uses' => 'PostController@create',
        'middleware' => 'can:news.posts.create',
    ]);
    $router->post('posts', [
        'as' => 'admin.news.post.store',
        'uses' => 'PostController@store',
        'middleware' => 'can:news.posts.create',
    ]);
    $router->get('posts/{news}/edit', [
        'as' => 'admin.news.post.edit',
        'uses' => 'PostController@edit',
        'middleware' => 'can:news.posts.edit',
    ]);
    $router->put('posts/{news}', [
        'as' => 'admin.news.post.update',
        'uses' => 'PostController@update',
        'middleware' => 'can:news.posts.edit',
    ]);
    $router->delete('posts/{news}', [
        'as' => 'admin.news.post.destroy',
        'uses' => 'PostController@destroy',
        'middleware' => 'can:news.posts.destroy',
    ]);

    $router->bind('newsCategory', function ($id) {
        return app(\Modules\News\Repositories\CategoryRepository::class)->find($id);
    });

    $router->get('categories', [
        'as' => 'admin.news.category.index',
        'uses' => 'CategoryController@index',
        'middleware' => 'can:news.categories.index',
    ]);
    $router->get('categories/create', [
        'as' => 'admin.news.category.create',
        'uses' => 'CategoryController@create',
        'middleware' => 'can:news.categories.create',
    ]);
    $router->post('categories', [
        'as' => 'admin.news.category.store',
        'uses' => 'CategoryController@store',
        'middleware' => 'can:news.categories.create',
    ]);
    $router->get('categories/{newsCategory}/edit', [
        'as' => 'admin.news.category.edit',
        'uses' => 'CategoryController@edit',
        'middleware' => 'can:news.categories.edit',
    ]);
    $router->put('categories/{newsCategory}', [
        'as' => 'admin.news.category.update',
        'uses' => 'CategoryController@update',
        'middleware' => 'can:news.categories.edit',
    ]);
    $router->delete('categories/{newsCategory}', [
        'as' => 'admin.news.category.destroy',
        'uses' => 'CategoryController@destroy',
        'middleware' => 'can:news.categories.destroy',
    ]);

});
