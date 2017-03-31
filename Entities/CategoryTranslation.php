<?php

namespace Modules\News\Entities;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'slug', 'meta_title', 'meta_description'];
    protected $table = 'news__category_translations';
}
