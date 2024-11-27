<?php

namespace App\Http\Controllers\api;

use App\Services\PostService;
use App\Http\Controllers\Controller;
use App\Traits\JsonResponseTrait;
class HomeController extends Controller
{
    protected $postService;
    use JsonResponseTrait;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index()
    {
        $status="published";
        $post_list  =$this->postService->postList($status,1);
        return $this->successResponse($post_list, 'Posts fetched successfully');
    }


}
