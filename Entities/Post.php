<?php

namespace Modules\News\Entities;

use Carbon\Carbon;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use Modules\News\Presenters\PostPresenter;
use Modules\Core\Traits\NamespacedEntity;
use Modules\Media\Entities\File;
use Modules\Media\Support\Traits\MediaRelation;
use Modules\Tag\Contracts\TaggableInterface;
use Modules\Tag\Traits\TaggableTrait;
use Modules\User\Contracts\Authentication;
use Modules\User\Entities\Sentinel\User;

class Post extends Model implements TaggableInterface
{
    use Translatable, MediaRelation, PresentableTrait, TaggableTrait, NamespacedEntity;

    public $translatedAttributes = ['title', 'slug', 'intro', 'content', 'meta_title', 'meta_description', 'og_title', 'og_description', 'og_type'];
    protected $fillable = ['category_id', 'user_id', 'status', 'title', 'slug', 'intro', 'content', 'meta_title', 'meta_description', 'og_title', 'og_description', 'og_type', 'meta_robot_no_index', 'meta_robot_no_follow', 'sitemap_frequency', 'sitemap_priority', 'created_at', 'updated_at'];
    protected $table = 'news__posts';
    protected $presenter = PostPresenter::class;
    protected $casts = [
        'status' => 'int',
    ];
    protected static $entityNamespace = 'asgardcms/news';

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the thumbnail image for the current blog post
     * @return File|string
     */
    public function getThumbnailAttribute()
    {
        if (isset($this->files()->first()->filename)) {
            return url(\Imagy::getThumbnail($this->files()->first()->filename, 'newsThumb'));
        }
        return null;
    }

    /**
     * @return string
     */
    public function getUrlAttribute()
    {
        return route('news.slug', [$this->slug]);
    }

    /**
     * @return string
     */
    public function getRobotsAttribute()
    {
        return $this->meta_robot_no_index.', '.$this->meta_robot_no_follow;
    }

    /**
     * Check if the post is in draft
     * @param Builder $query
     * @return bool
     */
    public function scopeDraft(Builder $query)
    {
        return (bool)$query->whereStatus(0);
    }

    /**
     * Check if the post is pending review
     * @param Builder $query
     * @return bool
     */
    public function scopePending(Builder $query)
    {
        return (bool)$query->whereStatus(1);
    }

    /**
     * Check if the post is published
     * @param Builder $query
     * @return bool
     */
    public function scopePublished(Builder $query)
    {
        return (bool)$query->whereStatus(2);
    }

    /**
     * Check if the post is unpublish
     * @param Builder $query
     * @return bool
     */
    public function scopeUnpublished(Builder $query)
    {
        return (bool)$query->whereStatus(3);
    }

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeMatch($query, $value)
    {
        return $query->whereHas('translations', function (Builder $q) use($value) {
            $q->whereRaw("MATCH(title, content) AGAINST(? IN BOOLEAN MODE)", array($value));
        })->with('translations');
    }

    /**
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        #i: Convert array to dot notation
        $config = implode('.', ['asgard.news.config.post.relations', $method]);

        #i: Relation method resolver
        if (config()->has($config)) {
            $function = config()->get($config);

            return $function($this);
        }

        #i: No relation found, return the call to parent (Eloquent) to handle it.
        return parent::__call($method, $parameters);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($post){
           $post->user_id = app(Authentication::class)->user()->id;
        });

        static::updating(function($post){
           $post->updated_at = Carbon::now();
        });
    }
}
