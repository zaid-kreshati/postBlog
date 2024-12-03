<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Traits\ChecksModelExistence;

class CategoryRepository
{
    use ChecksModelExistence;

    public function getParentNullCategories()
    {
        return Category::whereNull('parent_id')->orderBy('id', 'desc')->paginate(7);

    }

    public function getParentNullCategorieswithoutpagination()
    {
        return Category::whereNull('parent_id')->orderBy('id', 'desc')->get();

    }

    public function getCategoriesByParent($parentId)
    {
        if($parentId==0){
            return Category::whereNull('parent_id')->orderBy('id', 'desc')->paginate(7);
        }
        else{
            return Category::where('parent_id', $parentId)->orderBy('id', 'desc')->paginate(7);
        }

    }

    public function createCategory(array $request)
    {
        if($request['id']==0){
          $Category=Category::create([
            'name' => $request['name'],
        ]);
        }
        else{

        $Category=Category::create([
            'name' => $request['name'],
            'parent_id' => $request['id'],
        ]);
    }


        return $Category;
    }




    public function updateCategory($request, $id)
    {
        Log::info($request);
        Log::info("idd");
        Log::info($id);

        $category = Category::find($id);



         return $category;
    }

    public function deleteCategory($id)
    {
        return Category::find($id)->delete();
    }

    public function searchCategories($search)
    {
        return Category::where('name', 'like', '%' . $search . '%')->orderBy('id','desc')->paginate(7);
    }

    public function getCategoriesByParentHtml($id)
    {
        return Category::where('parent_id', $id)->orderBy('id', 'desc')->get();
    }

    public function getChildren($parentId)
    {
        if($parentId==0){
        return Category::whereNull('parent_id')->orderBy('id', 'desc')->paginate(7);
        }
        else{
            $categoryCheck = $this->checkModelExists(Category::class, $parentId);
            if($categoryCheck)
        return Category::where('parent_id', $parentId)->orderBy('id', 'desc')->paginate(7);
        else
        return null;
        }
    }


}
