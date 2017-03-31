<?php namespace Modules\News\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\News\Repositories\PostRepository as NewsRepository;

class NewsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return NewsRepository::class;
    }
}