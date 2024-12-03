<?php

namespace App\Http\Controllers\Admin\web;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;

class AnalyticsController extends Controller
{
    public function home()
    {
        // Labels for the last 7 days
        $labels = collect();
        $postsData = collect();
        $usersData = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->format('Y-m-d');
            $labels->push($date);

            $postsData->push(Post::whereDate('created_at', $date)->count());
            $usersData->push(User::whereDate('created_at', $date)->count());
        }

        return view('DashBoard.analytics', [
            'labels' => $labels,
            'postsData' => $postsData,
            'usersData' => $usersData,
        ]);
    }
}
