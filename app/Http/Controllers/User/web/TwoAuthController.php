<?php

namespace App\Http\Controllers\User\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\registerRequest;
use App\Services\TwoAuthService;
use App\Services\AuthService;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\Log;
use App\Exceptions\RegisterException;
use App\Services\PostService;

class TwoAuthController extends Controller
{
    use JsonResponseTrait;
    protected $twoAuthService;
    protected $authService;
    protected $postService;
    public function __construct(TwoAuthService $twoAuthService, AuthService $authService,PostService $postService)
    {
        $this->twoAuthService = $twoAuthService;
        $this->authService = $authService;
        $this->postService = $postService;
    }

    public function initiateRegistration(registerRequest $request)
    {
        try{
            $this->twoAuthService->initiateRegistration($request->only(['name', 'email', 'password','role']));
            return $this->successResponse('Registration initiated successfully');
        }catch(RegisterException $e){
            return $this->errorResponse($e->getMessage());
        }
    }


    public function verifyRegistration(Request $request)
    {
        $response=$this->twoAuthService->verifyRegistration($request->only(['two_factor_code']));

        if($response['status']==false){
            //return redirect()->back()->with('error', $response['error']);
            return $this->errorResponse($response['error']);
        }

        $this->authService->register($response);
        return $this->successResponse($response,'Registration successful!');
    }

    public function resendTwoFactorCode()
    {
        $this->twoAuthService->resendTwoFactorCode();

        return $this->successResponse('Verification code resent');
    }

}

