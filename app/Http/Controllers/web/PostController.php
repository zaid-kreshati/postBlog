<?php

namespace App\Http\Controllers\web;

use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Services\PostService;
use App\Services\ProfileService;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Traits\JsonResponseTrait;
use App\Traits\ChecksModelExistence;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    use JsonResponseTrait, ChecksModelExistence;

    protected $postService;
    protected $profileService;
    protected $categoryService;
    public $userId;

    public function __construct(PostService $postService, ProfileService $profileService, CategoryService $categoryService)
    {
        $this->postService = $postService;
        $this->profileService = $profileService;
        $this->categoryService = $categoryService;
        $this->userId=Auth::id();
    }


    public function store(StorePostRequest $request): JsonResponse
    {
        Log::info($request->all());
        $post = $this->postService->createPost($request);
        $status="published";
        $post_list=$this->postService->postList($status,1);
        $home=false;
        $user_id=Auth::id();
        $is_owner=true;
        $Categories=$this->categoryService->getParentNullCategorieswithoutpagination();
        $html = view('partials.posts', compact('post_list', 'home', 'status', 'user_id', 'is_owner'))->render();
        $html2= view('partials.editPost', compact('Categories'))->render();
        return $this->successResponse(['html'=>$html, 'html2'=>$html2], 'Post created successfully');
    }


    public function update(UpdatePostRequest $request, $id): JsonResponse
    {
        Log::info($request->all());
        $post = $this->postService->updatePost($id, $request);
        $status=$post->status;
        $post_list=$this->postService->postList($status,$request->page ?? 1);
        $home=false;
        $user_id=Auth::id();
        $is_owner=true;
        $html = view('partials.posts', compact('post_list', 'home', 'status', 'user_id', 'is_owner'))->render();
        return $this->successResponse(['html'=>$html], 'Post updated successfully');
    }

    public function archive($id): JsonResponse
    {
        $status="published";
        $this->postService->archive($id);
        $post_list=$this->postService->postList($status,1);
        Log::info("post_list");
        Log::info($post_list);
        $home=false;
        $user_id=Auth::id();
        $is_owner=true;
        $html = view('partials.posts', compact('post_list', 'home', 'status', 'user_id', 'is_owner'))->render();
        return $this->successResponse(['html'=>$html], 'Post archived successfully');
    }

    public function filterPosts(Request $request)
    {
        Log::info($request->all());
        $status = $request->input('status');
        // Get posts based on the status
        $post_list=$this->postService->postList($status,1);
        Log::info($post_list);
        $is_owner=true;
        $home=false;
        $user_id=Auth::id();
        $html = view('partials.posts', compact('post_list', 'home', 'status', 'user_id', 'is_owner'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    public function deleteMedia($id): JsonResponse
    {
        $data=$this->postService->deleteMedia($id);
        return $this->successResponse($data, 'Media deleted successfully');
    }

    public function deletePost($id): JsonResponse
    {
        $status="published";

        $this->postService->deletePost($id);
        $post_list=$this->postService->postList($status,1);
        $home=false;
        $user_id=Auth::id();
        $is_owner=true;
        $html = view('partials.posts', compact('post_list', 'home', 'status', 'user_id', 'is_owner'))->render();
        return $this->successResponse(['html'=>$html], 'Post deleted successfully');
    }

    public function publishPost($id, Request $request): JsonResponse
    {
        $status = "published";
        $this->postService->publishPost($id, $status);
        $post_list=$this->postService->postList($status,1);
        $home=false;
        $user_id=Auth::id();
        $is_owner=true;
        $html = view('partials.posts', compact('post_list', 'home', 'status', 'user_id', 'is_owner'))->render();
        return $this->successResponse(['html'=>$html], 'Post published successfully');
    }

    public function loadMorePosts(Request $request)
    {
        $status = $request['status'];
        $post_list=$this->postService->postList($status, $request->page ?? 1);
        $user_id=Auth::id();
        if($request['home']=="false"){
            $home=false;
        }
        else $home=true;
        $is_owner=$request['is_owner'];
        $html = view('partials.posts', compact('post_list', 'home', 'status', 'user_id', 'is_owner'))->render();
        return $this->successResponse(['html'=>$html, 'hasMorePages'=>$post_list->hasMorePages()], 'Posts fetched successfully');
    }


}
