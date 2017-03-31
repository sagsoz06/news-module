<?php

namespace Modules\News\Http\Controllers;

use Modules\Sitemap\Http\Controllers\BaseSitemapController;
use Modules\News\Repositories\CategoryRepository;

class SitemapController extends BaseSitemapController
{
    /**
     * @var CategoryRepository
     */
    private $category;

    public function __construct(CategoryRepository $category)
    {
        parent::__construct();
        $this->category = $category;
        $this->sitemap->setCache('laravel.news.sitemap', $this->sitemapCachePeriod);
    }

    public function index()
    {
        foreach ($this->category->all() as $category) {
            if (!$category->sitemap_include) continue;
            $this->sitemap->add(
                $category->url,
                $category->updated_at,
                $category->sitemap_priority,
                $category->sitemap_frequency,
                [],
                null,
                $category->present()->languages('language')
            );
            if($category->posts()->exists()) {
                foreach ($category->posts()->get() as $post) {
                    if (!$post->sitemap_include) continue;

                    $images = [];
                    if(isset($post->thumbnail))
                    {
                        $images[] = ['url' => $post->thumbnail, 'title' => $post->title];
                    }
                    $this->sitemap->add(
                        $post->url,
                        $post->updated_at,
                        $post->sitemap_priority,
                        $post->sitemap_frequency,
                        $images,
                        null,
                        $post->present()->languages('language')
                    );
                }
            }
        }

        return $this->sitemap->render('xml');
    }
}
