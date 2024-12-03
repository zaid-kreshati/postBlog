<?php

namespace App\Http\Controllers\Admin\web;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Requests\Auth\loginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;
use App\Exceptions\PostBlogException;
class AdminController extends Controller
{
    protected $authService;


    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showRegisterForm()
    {
        return view('DashBoard.register');
    }

    public function showLoginForm()
    {
        return view('DashBoard.login');
    }


    public function login(loginRequest $request)
    {
        try{
            $response=$this->authService->login($request->only(['email', 'password']));
            if($response['status']=='success'){
            return redirect()->route('DashBoard.home');
        }else{
            return view('DashBoard.login',['error'=>$response['message']]);
        }
        }catch(PostBlogException $e){
                return view('DashBoard.login',['error'=>$e->getMessage()]);
        }

        return redirect()->route('DashBoard.home');
    }

    public function logout(Request $request)
    {
         Auth::logout();

        return view('DashBoard.login');

    }

}

