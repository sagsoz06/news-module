<?php

namespace Modules\News\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class CreatePostRequest extends BaseFormRequest
{
    protected $translationsAttributesKey = 'news::post.form';

    public function rules()
    {
        return [
            'category_id' => 'required',
        ];
    }

    public function attributes()
    {
        return trans('news::post.form');
    }

    public function translationRules()
    {
        return [
            'title' => 'required',
            'intro' => 'required',
            'slug'  => "required|unique:news__post_translations,slug,null,post_id,locale,$this->localeKey",
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function translationMessages()
    {
        return trans('validation');
    }

    public function messages()
    {
        return trans('validation');
    }
}
