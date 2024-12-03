<?php

namespace App\Http\Controllers\Admin\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    use JsonResponseTrait;

    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    public function index()
    {
        $categories = $this->categoryService->getParentNullCategories();

        return $this->successResponse($categories, __('Categories fetched successfully')); // Return the collection directly, not wrapped in 'data'
    }


    public function store(Request $request)
    {
        $category=$this->categoryService->createCategory($request->all());
        return $this->successResponse($category, __('Category created successfully'));
    }

    public function update(Request $request, $id)
    {
        $category=$this->categoryService->updateCategory($request, $id);
        return $this->successResponse($category, __('Category updated successfully'));
    }

    public function destroy($id)
    {
        $this->categoryService->deleteCategory($id);
        return $this->successResponse(null, __('Category deleted successfully'));
    }
    public function search(Request $request)
    {
        $categories = $this->categoryService->searchCategories($request->search);
        return $this->successResponse($categories, __('Categories fetched successfully'));
    }


    public function getNestedCategories($parentId)
    {
        Log::info($parentId);
        $children=$this->categoryService->getChildren($parentId);

        return $this->successResponse($children, __('Categories fetched successfully'));
    }




}


