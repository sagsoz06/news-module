<?php namespace Modules\News\Widgets;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\News\Entities\Post;
use Modules\News\Repositories\CategoryRepository;
use Modules\News\Repositories\PostRepository;

class NewsWidgets
{
    /**
     * @var PostRepository
     */
    private $post;
    /**
     * @var CategoryRepository
     */
    private $category;

    /**
     * NewsWidgets constructor.
     * @param PostRepository $post
     * @param CategoryRepository $category
     */
    public function __construct(PostRepository $post, CategoryRepository $category)
    {

        $this->post = $post;
        $this->category = $category;
    }

    /**
     * @param int $limit
     * @param string $view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function latest($limit=5, $view='latest-posts')
    {
        $posts = $this->post->latest($limit);
        return view('news::widgets.'.$view, compact('posts'));
    }

    /**
     * @param string $view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function categories($view='category')
    {
        $categories = $this->category->all();
        return view('news::widgets.'.$view, compact('categories'));
    }

    /**
     * @param Post $posts
     * @param int $limit
     * @param string $view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tags($posts, $limit=10, $view='tags')
    {
        if($posts instanceof LengthAwarePaginator) {
            $tags = $posts->filter(function($post){
                return $post->tags->count() > 0;
            })->map(function($post) use ($limit) {
                return $post->tags()->take($limit)->get();
            });
            $tags = $tags->flatten();
        } else {
            $tags = $posts->tags()->take($limit)->get();
        }
        return view('news::widgets.'.$view, compact('tags'));
    }

    public function findByCategory($category="", $limit=5, $view="category-posts")
    {
        $category = $this->category->findBySlug($category)->load(['posts', 'translations']);
        $posts    = $category->posts()->where('status', 2)->take($limit)->get();
        if($posts->count()>0) {
            return view('news::widgets.'.$view, compact('category', 'posts'));
        }
        return null;
    }
}