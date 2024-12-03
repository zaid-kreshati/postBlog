<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;


class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getParentNullCategories()
    {
        return $this->categoryRepository->getParentNullCategories();
    }

    public function getParentNullCategorieswithoutpagination()
    {
        return $this->categoryRepository->getParentNullCategorieswithoutpagination();
    }

    public function getCategoriesByParent( $parentId)
    {
        return $this->categoryRepository->getCategoriesByParent($parentId);
    }

    public function createCategory(array $request)
    {
        return $this->categoryRepository->createCategory($request);
    }

    public function updateCategory($request, $id)
    {
         $category=$this->categoryRepository->updateCategory($request, $id);
         return $category;
    }

    public function deleteCategory($id)
    {
        return $this->categoryRepository->deleteCategory($id);
    }

    public function searchCategories($search)
    {
        return $this->categoryRepository->searchCategories($search);
    }

    public function getCategoriesByParentHtml($id)
    {
        return $this->categoryRepository->getCategoriesByParentHtml($id);
    }

    public function getChildren($parentId)
    {
        return $this->categoryRepository->getChildren($parentId);
    }

}

