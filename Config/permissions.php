<?php

return [
    'news.posts'      => [
        'index'      => 'news::post.list resource',
        'create'     => 'news::post.create resource',
        'edit'       => 'news::post.edit resource',
        'destroy'    => 'news::post.destroy resource',
        'sitemap'    => 'news::post.sitemap resource',
        'author'     => 'news::post.author resource',
    ],
    'news.categories' => [
        'index'   => 'news::category.list resource',
        'create'  => 'news::category.create resource',
        'edit'    => 'news::category.edit resource',
        'destroy' => 'news::category.destroy resource',
        'sitemap' => 'news::category.sitemap resource'
    ],
];
