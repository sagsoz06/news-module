<?php namespace Modules\News\Widgets;

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

    public function __construct(PostRepository $post, CategoryRepository $category)
    {

        $this->post = $post;
        $this->category = $category;
    }

    public function latest($limit=5, $view='latest-posts')
    {
        $posts = $this->post->latest($limit);
        return view('news::widgets.'.$view, compact('posts'));
    }

    public function categories($view='category')
    {
        $categories = $this->category->all();
        return view('news::widgets.'.$view, compact('categories'));
    }

    public function tags($posts, $limit=10, $view='tags')
    {
        if(count($posts)>1) {
            $tags = $posts->filter(function($post){
                return $post->tags->count() > 0;
            })->map(function($post){
                return $post->tags()->first();
            });
            $tags = $tags->take($limit);
        } else {
            $tags = $posts->tags()->take($limit)->get();
        }
        return view('news::widgets.'.$view, compact('tags'));
    }
}