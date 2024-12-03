<?php

namespace App\Http\Controllers\User\web;

use Illuminate\Http\Request;
use App\Services\PostService;
use App\Models\Category;
use App\Models\Media;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
class HomeController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function home()
    {
        $status="published";
        $post_list  =$this->postService->postList($status,1);
        $home=true;
        $user_id=Auth::id();
        return view('home', compact('post_list', 'home', 'status', 'user_id'));
    }

    public function search(){
        $categories=Category::all();
        return view('search', compact('categories'));
    }

}
