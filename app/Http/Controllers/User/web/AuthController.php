<?php

namespace App\Http\Controllers\User\web;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Requests\Auth\loginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Exceptions\PostBlogException;

class AuthController extends Controller
{
    protected $authService;


    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showRegisterForm()
    {
        return view('register');
    }

    public function showLoginForm()
    {
        return view('login');
    }


    public function login(loginRequest $request)
    {
        try{
            $response=$this->authService->login($request->only(['email', 'password']));
            if($response['status']=='success'){
            return redirect()->route('home');
        }else{
            return view('login',['error'=>$response['message']]);
        }
        }catch(PostBlogException $e){
            return view('login',['error'=>$e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
         Auth::logout();
        return view('login');
    }
}

