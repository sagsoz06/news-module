<?php

namespace Modules\News\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class UpdatePostRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            "created_at" => "required|date_format:d.m.Y H:i"
        ];
    }

    public function translationRules()
    {
        $id = $this->route()->parameter('news')->id;

        return [
            "title" => "required",
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

    public function translationMessages()
    {
        return [
            'title.required' => trans('news::messages.title is required'),
            'slug.required' => trans('news::messages.slug is required'),
            'slug.unique' => trans('news::messages.slug is unique'),
        ];
    }
}
