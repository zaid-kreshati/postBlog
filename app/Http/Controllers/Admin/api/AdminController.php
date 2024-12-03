<?php

namespace App\Http\Controllers\Admin\api;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Requests\Auth\loginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    protected $authService;


    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }


    public function login(loginRequest $request)
    {
        $data=$this->authService->login($request->only(['email', 'password']));
        return $this->successResponse($data, 'Login successful');
    }




}

