<?php

namespace Modules\News\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class UpdateCategoryRequest extends BaseFormRequest
{
    protected $translationsAttributesKey = 'news::category.form';

    public function translationRules()
    {
        $id = $this->route()->parameter('newsCategory')->id;

        return [
            'name'     => 'required|max:200',
            'slug'     => "required|unique:news__category_translations,slug,$id,category_id,locale,$this->localeKey"
        ];
    }

    public function rules()
    {
        return [
            'ordering' => 'required|integer'
        ];
    }

    public function attributes()
    {
        return trans('news::category.form');
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [];
    }
}
