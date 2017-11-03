<?php

namespace Modules\News\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Modules\News\Entities\Post;
use Modules\News\Entities\Status;
use Modules\News\Events\PostWasCreated;
use Modules\News\Events\PostWasDeleted;
use Modules\News\Events\PostWasUpdated;
use Modules\News\Repositories\Collection;
use Modules\News\Repositories\PostRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\User\Contracts\Authentication;

class EloquentPostRepository extends EloquentBaseRepository implements PostRepository
{
    /**
     * @param  int    $id
     * @return object
     */
    public function find($id)
    {
        return $this->model->with('translations')->find($id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->model->with('translations','author','category')->orderBy('created_at', 'DESC')->get();
    }

    /**
     * Update a resource
     * @param $post
     * @param  array $data
     * @return mixed
     */
    public function update($post, $data)
    {
        if(isset($data['created_at'])) {
            $data['created_at'] = \Carbon::parse($data['created_at']);
        }

        $post->update($data);

        event(new PostWasUpdated($post, $data));

        $post->setTags(array_get($data, 'tags', []));

        return $post;
    }

    /**
     * Create a blog post
     * @param  array $data
     * @return Post
     */
    public function create($data)
    {
        $post = $this->model->create($data);

        event(new PostWasCreated($post, $data));

        $post->setTags(array_get($data, 'tags', []));

        return $post;
    }

    public function destroy($model)
    {
        event(new PostWasDeleted($model->id, get_class($model)));

        return $model->delete();
    }

    /**
     * Return all resources in the given language
     *
     * @param  string                                   $lang
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allTranslatedIn($lang)
    {
        return $this->model->whereHas('translations', function (Builder $q) use ($lang) {
            $q->where('locale', "$lang");
            $q->where('title', '!=', '');
        })->with('translations')->whereStatus(Status::PUBLISHED)->orderBy('created_at', 'DESC')->get();
    }

    /**
     * Return the latest x blog posts
     * @param int $amount
     * @return Collection
     */
    public function latest($amount = 5)
    {
        return $this->model->whereStatus(Status::PUBLISHED)->orderBy('created_at', 'desc')->take($amount)->get();
    }

    /**
     * Get the previous post of the given post
     * @param object $post
     * @return object
     */
    public function getPreviousOf($post)
    {
        return $this->model->where('created_at', '<', $post->created_at)
            ->whereStatus(Status::PUBLISHED)->orderBy('created_at', 'desc')->first();
    }

    /**
     * Get the next post of the given post
     * @param object $post
     * @return object
     */
    public function getNextOf($post)
    {
        return $this->model->where('created_at', '>', $post->created_at)
            ->whereStatus(Status::PUBLISHED)->first();
    }

    /**
     * Find a resource by the given slug
     *
     * @param  string $slug
     * @return object
     */
    public function findBySlug($slug)
    {
        return $this->model->whereHas('translations', function (Builder $q) use ($slug) {
            $q->where('slug', "$slug");
        })->with('translations')->whereStatus(Status::PUBLISHED)->firstOrFail();
    }

    /**
     * @param $tag
     * @return mixed
     */
    public function findByTag($tag)
    {
        return $this->model->whereTag($tag)->whereStatus(Status::PUBLISHED)->with('tags')->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findByIdInLocales($id)
    {
        return $this->model->whereHas('translations', function (Builder $q) use ($id) {
            $q->where('post_id', $id);
        })->with('translations')->whereStatus(Status::PUBLISHED)->first();
    }

    /**
     * @param $lang
     * @return mixed
     */
    public function allTranslatedInPaginate($lang, $per_page)
    {
        return $this->model->whereHas('translations', function (Builder $q) use ($lang) {
            $q->where('locale', "$lang");
            $q->where('title', '!=', '');
        })->with('translations')->whereStatus(Status::PUBLISHED)->orderBy('created_at', 'DESC')->paginate($per_page);
    }

    /**
     * @param $tag
     * @param $per_page
     * @return mixed
     */
    public function findByTagPaginate($tag, $per_page)
    {
        return $this->model->whereTag($tag)->whereStatus(Status::PUBLISHED)->with('tags')->paginate($per_page);
    }

    /**
     * @param int $amount
     * @return mixed
     */
    public function popular($amount = 5)
    {
        return $this->model->orderBy('counter', 'desc')->take($amount)->get();
    }

    /**
     * @param $query
     * @param $per_page
     * @return mixed
     */
    public function search($query, $per_page)
    {
        return $this->model->match($query)->paginate($per_page);
    }
}