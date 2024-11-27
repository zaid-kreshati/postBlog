<?php

namespace App\Repositories;
use App\Models\User;
use App\Models\Post;

class SearchRepository
{
    public function searchAll($query, $page = 1)
    {
        $users = User::where('name', 'like', '%' . $query . '%')
            ->with('media')
            ->orderBy('created_at', 'desc')
            ->paginate(2, ['*'], 'page', $page);

        $posts = Post::where('description', 'like', '%' . $query . '%')
            ->where('status', 'published')
            ->with('user.media', 'media')
            ->orderBy('created_at', 'desc')
            ->paginate(2, ['*'], 'page', $page);

        return [
            'users' => $users,
            'posts' => $posts
        ];
    }

    public function searchAllPosts($query, $page = 1)
    {
        $posts = Post::where('description', 'like', '%' . $query . '%')
            ->where('status', 'published')
            ->with('user.media', 'media')
            ->orderBy('created_at', 'desc')
            ->paginate(4, ['*'], 'page', $page);

        return [
            'posts' => $posts,
            'users' => []
        ];
    }

    public function searchPostswithphoto($query, $page = 1)
    {
        $posts = Post::whereHas('media', function ($query) {
                $query->where('type', 'post_image');
            })
            ->where('description', 'like', '%' . $query . '%')
            ->where('status', 'published')
            ->with(['media', 'user.media'])
            ->orderBy('created_at', 'desc')
            ->paginate(4, ['*'], 'page', $page);

        return [
            'posts' => $posts,
            'users' => []
        ];
    }

    public function searchPostswithvideo($query, $page = 1)
    {
        $posts = Post::whereHas('media', function ($query) {
                $query->where('type', 'post_video');
            })
            ->where('description', 'like', '%' . $query . '%')
            ->where('status', 'published')
            ->with(['media', 'user.media'])
            ->orderBy('created_at', 'desc')
            ->paginate(4, ['*'], 'page', $page);

        return [
            'posts' => $posts,
            'users' => []
        ];
    }

    public function searchUsers($query, $page = 1)
    {
        $users = User::where('name', 'like', '%' . $query . '%')
            ->with('media')
            ->orderBy('created_at', 'desc')
            ->paginate(4, ['*'], 'page', $page);

        return [
            'posts' => [],
            'users' => $users
        ];
    }

    public function searchCategory($category_id, $page = 1)
    {
        $posts = Post::where('category_id', $category_id)
            ->where('status', 'published')
            ->with('user.media', 'media')
            ->paginate(4, ['*'], 'page', $page);

        return [
            'posts' => $posts,
            'users' => []
        ];
    }
}
