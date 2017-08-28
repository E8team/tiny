<?php


namespace App\Http\Controllers\Backend\Api;


use App\Http\Controllers\ApiController;
use App\Http\Requests\Backend\CategoryCreateRequest;
use App\Http\Requests\Backend\CategoryUpdateRequest;
use App\Models\Category;
use App\Services\CategoryService;
use App\Transformers\Backend\CategoryTransformer;
use Illuminate\Http\Request;

class CategoriesController extends ApiController
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Category $category)
    {
        return $this->response()->item($category, new CategoryTransformer());
    }

    public function store(CategoryCreateRequest $request, CategoryService $categoryService)
    {
        $categoryService->create($request->validated());
        return $this->response()->noContent();
    }

    public function update(Category $category, CategoryUpdateRequest $request, CategoryService $categoryService)
    {
        $categoryService->update($category, $request->validated());
        return $this->response()->noContent();
    }

    public function index(Request $request)
    {
        $topicCategory = Category::byType($request->get('type'))->topCategories()->get();
        return $this->response()->collection($topicCategory, new CategoryTransformer());
    }

    public function destroy(Category $category)
    {
        // todo 考虑关联数据问题
        $category->delete();
        return $this->response()->noContent();
    }

}
