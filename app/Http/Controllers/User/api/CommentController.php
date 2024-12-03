<?php

namespace App\Http\Controllers\User\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CommentService;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Requests\Comment\DeleteCommentRequest;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\Log;
use App\Models\Post;
use App\Models\User;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\JsonResponse;
use App\Models\Comment;

class CommentController extends Controller
{
    use JsonResponseTrait;
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index($postId)
    {
        $comments = $this->commentService->getPostComments($postId);
        return $this->successResponse($comments, 'Comments fetched successfully');
    }
    public function store(StoreCommentRequest $request): JsonResponse
    {
        $comment=$this->commentService->store($request->all());

        $personal_image=$comment->user->media->where('type', 'user_profile_image')->first();
        $name=$comment->user->name;
        $data=[
            'comment'=>$comment,
            'personal_image'=>$personal_image,
            'name'=>$name
        ];
        return $this->successResponse($data, 'Comment created successfully');
    }

    public function destroy(Request $request)
    {
        $this->commentService->destroy($request);
        return $this->successResponse(null, 'Comment deleted successfully');
    }

    public function storeNested(StoreCommentRequest $request)
    {
        $comment = $this->commentService->storeNested($request->only('text', 'parent_id', 'post_id'));
        $personal_image = $comment->user->media->where('type', 'user_profile_image')->first();

        $name = $comment->user->name;
        $data = [
            'comment' => $comment,
            'personal_image' => $personal_image,
            'name' => $name
        ];
        return $this->successResponse($data, 'Comment created successfully');
    }

    public function getNestedComments($parentId)
    {

        $nestedComments = $this->commentService->getNestedComments($parentId);


        return $this->successResponse($nestedComments, 'Nested comments fetched successfully');
    }
}
