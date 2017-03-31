<?php

namespace Modules\News\Repositories\Cache;

use Modules\News\Repositories\CategoryRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheCategoryDecorator extends BaseCacheDecorator implements CategoryRepository
{
    public function __construct(CategoryRepository $category)
    {
        parent::__construct();
        $this->entityName = 'news.categories';
        $this->repository = $category;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findByIdInLocales($id)
    {
        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.findByIdInLocales.{$id}", $this->cacheTime,
                function () use ($id) {
                    return $this->repository->findByIdInLocales($id);
                }
            );
    }
}
