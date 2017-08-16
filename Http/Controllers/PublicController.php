<?php

namespace Modules\News\Http\Controllers;

use Illuminate\Http\Request;
use Modules\News\Entities\Post;
use Modules\News\Repositories\CategoryRepository;
use Modules\News\Repositories\PostRepository;
use Modules\Core\Http\Controllers\BasePublicController;
use Modules\Tag\Repositories\TagRepository;
use Breadcrumbs;


class PublicController extends BasePublicController
{
    /**
     * @var PostRepository
     */
    private $post;

    /**
     * @var CategoryRepository
     */
    private $category;

    private $perPage;
    /**
     * @var TagRepository
     */
    private $tag;

    public function __construct(
        PostRepository $post,
        CategoryRepository $category,
        TagRepository $tag
    )
    {
        parent::__construct();
        $this->post = $post;
        $this->category = $category;
        $this->tag = $tag;

        $this->perPage = setting('news::posts-per-page') == '' ? 5 : setting('news::posts-per-page');

        /* Start Default Breadcrumbs */
        if(!app()->runningInConsole()) {
            Breadcrumbs::register('news', function ($breadcrumbs) {
                $breadcrumbs->push(trans('themes::news.title'), route('news.index'));
            });
        }
        /* End Default Breadcrumbs */
    }

    public function index()
    {
        $posts = $this->post->allTranslatedInPaginate($this->locale, $this->perPage);

        /* Start Seo */
        $this->seo()->setTitle(trans('themes::news.title'))
            ->setDescription(trans('themes::news.description'))
            ->meta()->setUrl(route('news.index'))
            ->addMeta('robots', "index, follow")
            ->addAlternates($this->getAlternateLanguages('news::routes.news.index'));
        /* End Seo */

        return view('news::index', compact('posts'));
    }

    public function show($slug)
    {
        $post = $this->post->findBySlug($slug);

        Post::whereId($post->id)->increment('counter');

        $this->throw404IfNotFound($post);

        /* Start Seo */
        $this->seo()->setTitle($post->present()->meta_title)
            ->setDescription($post->present()->meta_description)
            ->setKeywords($post->present()->meta_keywords)
            ->meta()->setUrl($post->url)
            ->addMeta('robots', $post->robots)
            ->addAlternates($post->present()->languages);

        $this->seoGraph()->setTitle($post->present()->og_title)
            ->setType($post->og_type)
            ->setDescription($post->present()->og_description)
            ->setImage($post->present()->og_image)
            ->setUrl($post->url);

        $this->seoCard()->setTitle($post->present()->og_title)
            ->setType('app')
            ->addImage($post->present()->og_image)
            ->setDescription($post->present()->og_description);
        /* End Seo */

        /* Start Breadcrumbs */
        Breadcrumbs::register('news.show', function ($breadcrumbs) use ($post) {
            $breadcrumbs->parent('news');
            if (isset($post->category->name)) {
                $breadcrumbs->push($post->category->name, $post->category->url);
            }
            $breadcrumbs->push($post->title, $post->url);
        });
        /* End Breadcrumbs */

        return view('news::show', compact('post'));
    }

    public function category($slug)
    {
        $category = $this->category->findBySlug($slug);

        $this->throw404IfNotFound($category);

        $posts = $category->posts()->orderBy('created_at', 'desc')->paginate($this->perPage);

        /* Start Seo */
        $this->seo()->setTitle($category->present()->meta_title)
            ->setDescription($category->present()->meta_description)
            ->meta()->setUrl($category->url)
            ->addMeta('robots', $category->robots)
            ->addAlternates($category->present()->languages);
        /* End Seo */

        /* Start Breadcrumbs */
        Breadcrumbs::register('news.category', function ($breadcrumbs) use ($category) {
            $breadcrumbs->parent('news');
            $breadcrumbs->push($category->name, $category->url);
        });
        /* End Breadcrumbs */

        return view('news::category', compact('category', 'posts'));
    }

    public function tagged($slug)
    {
        $tag = $this->tag->findBySlug($slug);

        $posts = $this->post->findByTagPaginate($slug, $this->perPage);

        $this->throw404IfNotFound($posts);

        if (isset($tag)) {
            /* Start Seo */
            $this->seo()->setTitle(trans('tag::tags.tag') . ' : ' . $tag->name)
                ->setDescription($tag->name)
                ->meta()->setUrl(route('news.tag', [$tag->slug]))
                ->addMeta('robots', "index, follow");
            /* End Seo */

            /* Start Breadcrumbs */
            Breadcrumbs::register('news.tag', function ($breadcrumbs) use ($tag) {
                $breadcrumbs->parent('news');
                $breadcrumbs->push(trans('tag::tags.tag') . ' : ' . $tag->name, route('news.tag', [$tag->slug]));
            });
            /* End Breadcrumbs */
        }

        return view('news::tag', compact('posts', 'tag'));
    }

    public function search(Request $request)
    {
        $title = $request->has('s') ? $request->get('s') : trans('themes::theme.search');

        $this->seo()->setTitle($title);

        $posts = $this->post->search($request->get('s'), $this->perPage);

        /* Start Breadcrumbs */
        Breadcrumbs::register('news.search', function ($breadcrumbs) use ($title) {
            $breadcrumbs->parent('news');
            $breadcrumbs->push($title);
        });
        /* End Breadcrumbs */

        return view('news::search', compact('posts', 'title'));
    }

    /**
     * Throw a 404 error page if the given page is not found
     * @param $page
     */
    private function throw404IfNotFound($post)
    {
        if (is_null($post)) {
            app()->abort('404');
        }
    }
}
