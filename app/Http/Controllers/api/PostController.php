<?php

namespace App\Http\Controllers\api;

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
use App\Http\Requests\Post\FilterPostsRequest;
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
        if(array_key_exists('user_ids',$request->all())){
            foreach($request['user_ids'] as $user_id){
                if($user_id==Auth::id()){
                    return $this->errorResponse('You cannot tag yourself.', 400);
                }
            }
        }
        try{
            $post = $this->postService->createPost($request);
            $result=$this->postService->getPost($post->id);
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500);
        }

        return $this->successResponse($result, 'Post created successfully');
    }


    public function update(UpdatePostRequest $request, $id): JsonResponse
    {
        Log::info($request->all());
        if(array_key_exists('user_ids',$request->all())){
            foreach($request['user_ids'] as $user_id){
                if($user_id==Auth::id()){
                    return $this->errorResponse('You cannot tag yourself.', 400);
                }
            }
        }
        try{
            $post = $this->postService->updatePost2($id, $request);
            $result=$this->postService->getPost($post->id);
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500);
        }

        return $this->successResponse($result, 'Post updated successfully');
    }

    public function archive($id): JsonResponse
    {
        $post=$this->postService->archive($id);
        $result=$this->postService->getPost($post->id);

        return $this->successResponse($result, 'Post archived successfully');
    }

    public function filterPosts(FilterPostsRequest $request)
    {
        $status = $request->input('status');
        // Get posts based on the status
        $post_list=$this->postService->postList($status,1);
        

        return $this->successResponse($post_list, 'Posts fetched successfully');
    }

    public function deleteMedia($id): JsonResponse
    {
        $data=$this->postService->deleteMedia($id);
        return $this->successResponse($data, 'Media deleted successfully');
    }

    public function deletePost($id): JsonResponse
    {
        $data=$this->postService->deletePost($id);
        return $this->successResponse($data, 'Post deleted successfully');
    }

    public function publishPost($id): JsonResponse
    {
        $data=$this->postService->publishPost($id);
        return $this->successResponse($data, 'Post published successfully');
    }


    public function loadMorePosts(FilterPostsRequest $request)
    {
        $status = $request->input('status');
        $post_list=$this->postService->postList($status, $request->page ?? 1);
        return $this->successResponse($post_list, 'Posts fetched successfully');
    }


}
