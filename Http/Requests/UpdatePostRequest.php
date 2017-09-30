<?php

namespace Modules\News\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class UpdatePostRequest extends BaseFormRequest
{
    protected $translationsAttributesKey = 'news::post.form';

    public function rules()
    {
        return [
            'category_id' => 'required',
            "created_at"  => "required|date_format:d.m.Y H:i"
        ];
    }

    public function translationRules()
    {
        $id = $this->route()->parameter('newsPost')->id;

        return [
            "title" => "required",
            "intro" => "required",
            "slug" => "required|unique:news__post_translations,slug,$id,post_id,locale,$this->localeKey"
        ];
    }

    public function attributes()
    {
        return trans('news::post.form');
    }

    public function authorize()
    {
        return true;
    }
}
