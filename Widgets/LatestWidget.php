<?php namespace Modules\News\Widgets;


use Modules\News\Repositories\PostRepository;

class LatestWidget
{
    /**
     * @var PostRepository
     */
    private $post;

    public function __construct(PostRepository $post)
    {
        $this->post = $post;
    }

    public function register($limit=10)
    {
        $posts = $this->post->latest($limit);
        return view('news::widgets.latest', compact('posts'))->render();
    }
}