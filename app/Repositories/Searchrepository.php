<?php

namespace App\Repositories;
use App\Models\User;
use App\Models\Post;

class SearchRepository
{
    public function searchAll($query)
    {
        $users = User::where('name', 'like', '%' . $query . '%')->with('media')->get();
        $posts = Post::where('description', 'like', '%' . $query . '%')->with('user.media')->get();

        return [
            'users' => $users,
            'posts' => $posts
        ];
    }
}
