<?php namespace Modules\News\Widgets;

use Modules\News\Repositories\PostRepository;

class NewsWidgets
{
    /**
     * @var PostRepository
     */
    private $post;

    public function __construct(PostRepository $post)
    {

        $this->post = $post;
    }

    public function latestPosts($limit=5, $view='latestPosts')
    {
        if($posts = $this->post->latest($limit))
        {
            return view('news::widgets.'.$view, compact('posts'))->render();
        }
        return false;
    }
}