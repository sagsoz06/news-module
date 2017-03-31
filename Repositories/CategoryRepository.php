<?php

namespace Modules\News\Repositories;

use Modules\Core\Repositories\BaseRepository;

/**
 * Interface CategoryRepository
 * @package Modules\News\Repositories
 */
interface CategoryRepository extends BaseRepository
{
    public function findByIdInLocales($id);
}
