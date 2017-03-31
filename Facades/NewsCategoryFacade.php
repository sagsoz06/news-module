<?php namespace Modules\News\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\News\Repositories\CategoryRepository;

class NewsCategoryFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CategoryRepository::class;
    }
}