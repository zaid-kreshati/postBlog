<?php

namespace App\Http\Controllers\User\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SearchService;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\Log;
class SearchController extends Controller
{
    use JsonResponseTrait;
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function searchAll(Request $request)
    {
        Log::info($request->all());
        $query = $request->input('query');
        $resaults = $this->searchService->searchAll($query,1);
        $html = view('partials.search', ['posts' => $resaults['posts'], 'users' => $resaults['users']])->render();
        //return $resaults;
        return $this->successResponse($html, 'Search results fetched successfully');
    }

    public function searchAllPosts(Request $request)
    {
        $query = $request->input('query');
        $resaults = $this->searchService->searchAllPosts($query,1);
        $html = view('partials.search', ['posts' => $resaults['posts']])->render();
        return $this->successResponse($html, 'Search results fetched successfully');
    }

    public function searchUsers(Request $request)
    {
        $query = $request->input('query');
        $resaults = $this->searchService->searchUsers($query,1);
        $html = view('partials.search', ['users' => $resaults['users']])->render();
        return $this->successResponse($html, 'Search results fetched successfully');
    }

    public function searchPostswithphoto(Request $request)
    {
        $query = $request->input('query');
        $resaults = $this->searchService->searchPostswithphoto($query,1);
        $html = view('partials.search', ['posts' => $resaults['posts']])->render();
        return $this->successResponse($html, 'Search results fetched successfully');
    }

    public function searchPostswithvideo(Request $request)
    {
        $query = $request->input('query');
        $resaults = $this->searchService->searchPostswithvideo($query,1);
        $html = view('partials.search', ['posts' => $resaults['posts']])->render();
        return $this->successResponse($html, 'Search results fetched successfully');
    }

    public function loadMoreResults(Request $request)
    {
        Log::info($request->all());
        $query = $request->input('query');
        $status = $request->input('filter');
        $page = $request->input('page');

        if($status == 'all'){
            $resaults = $this->searchService->searchAll($query, $page);
            $html = view('partials.search', ['posts' => $resaults['posts'], 'users' => $resaults['users']])->render();
        }else if($status == 'posts'){
            $resaults = $this->searchService->searchAllPosts($query, $page);
            $html = view('partials.search', ['posts' => $resaults['posts']])->render();
        }else if($status == 'users'){
            $resaults = $this->searchService->searchUsers($query, $page);
            $html = view('partials.search', ['users' => $resaults['users']])->render();
        }else if($status == 'posts_with_photo'){
            $resaults = $this->searchService->searchPostswithphoto($query, $page);
            $html = view('partials.search', ['posts' => $resaults['posts']])->render();
         }else if($status == 'posts_with_video'){
            $resaults = $this->searchService->searchPostswithvideo($query, $page);
            $html = view('partials.search', ['posts' => $resaults['posts']])->render();
        }

        return $this->successResponse($html, 'Search results fetched successfully');
    }

    public function searchCategory(Request $request)
    {
        $category_id = $request->input('category_id');
        $resaults = $this->searchService->searchCategory($category_id, 1);
        $html = view('partials.search', ['posts' => $resaults['posts']])->render();
        return $this->successResponse($html, 'Search results fetched successfully');
    }
}
