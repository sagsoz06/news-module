<?php namespace Modules\News\Widgets;

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

    public function tags($posts, $view="tags")
    {
        try {
            if(count($posts)>1) {
                $tags = collect();
                foreach ($posts as $post) {
                    $tags->push($post->tags()->first());
                }
            } else {
                $tags = $posts->tags()->get();
            }
            return view('news::widgets.'.$view, compact('tags'));
        }
        catch (\Exception $exception) {
            return null;
        }
    }
}