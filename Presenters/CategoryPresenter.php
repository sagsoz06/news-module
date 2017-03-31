<?php namespace Modules\News\Presenters;

use Modules\Core\Presenters\BasePresenter;

class CategoryPresenter extends BasePresenter
{
    protected $zone           = 'categoryImage';
    protected $slug           = 'slug';
    protected $transKey       = 'news::routes.category.slug';
    protected $routeKey       = 'news.category';
    protected $titleKey       = 'name';
    protected $descriptionKey = 'name';
}