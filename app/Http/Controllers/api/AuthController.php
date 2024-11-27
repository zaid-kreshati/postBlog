<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Requests\Auth\loginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Traits\JsonResponseTrait;

class AuthController extends Controller
{
    use JsonResponseTrait;
    protected $authService;


    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }


    public function login(loginRequest $request)
    {
        $user = $this->authService->login($request->only(['email', 'password']));
        return $this->successResponse($user, 'Login successful');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return $this->successResponse(null, 'Logout successful');
    }
}

