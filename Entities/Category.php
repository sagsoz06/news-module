<?php

namespace Modules\News\Entities;

use Carbon\Carbon;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use Modules\News\Presenters\CategoryPresenter;

class Category extends Model
{
    use Translatable, PresentableTrait;

    public $translatedAttributes = ['name', 'slug', 'meta_title', 'meta_description'];
    protected $fillable = ['name', 'slug', 'meta_title', 'meta_description', 'sitemap_frequency', 'sitemap_priority', 'updated_at', 'meta_robot_no_index', 'meta_robot_no_follow', 'ordering'];
    protected $table = 'news__categories';
    protected $presenter = CategoryPresenter::class;

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function getUrlAttribute()
    {
        return route('news.category', [$this->getAttribute('slug')]);
    }

    public function getRobotsAttribute()
    {
        return $this->meta_robot_no_index.', '.$this->meta_robot_no_follow;
    }

    public static function boot()
    {
        parent::boot();
        static::updating(function ($category){
           $category->updated_at = Carbon::now();
        });
    }
}
