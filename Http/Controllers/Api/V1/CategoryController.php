<?php

namespace Modules\News\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\News\Entities\Category;
use Modules\News\Http\Requests\StoreCategoryRequest;
use Modules\News\Repositories\CategoryRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class CategoryController extends AdminBaseController
{
    /**
     * @var CategoryRepository
     */
    private $category;

    public function __construct(CategoryRepository $category)
    {
        parent::__construct();
        $this->category = $category;
    }

    public function index()
    {
        $categories = $this->category->all();

        return response()->json([
            'errors' => false,
            'count' => $categories->count(),
            'data' => $categories,
        ]);
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = $this->category->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('news::messages.category created'),
            'data' => $category,
        ], Response::HTTP_CREATED);
    }

    public function update(Category $category, Request $request)
    {
        $category = $this->category->update($category, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('news::messages.category updated'),
            'data' => $category,
        ], Response::HTTP_CREATED);
    }

    public function destroy(Category $category)
    {
        $this->category->destroy($category);

        return response()->json([
            'errors' => false,
            'message' => trans('news::messages.category deleted'),
        ], Response::HTTP_ACCEPTED);
    }
}
