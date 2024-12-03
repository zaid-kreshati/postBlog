<?php

namespace App\Http\Controllers\User\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorCodeMail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\TwoAuthService;
use App\Services\AuthService;
use App\Traits\JsonResponseTrait;
class TwoAuthController extends Controller
{
    use JsonResponseTrait;
    protected $twoAuthService;
    protected $authService;
    public function __construct(TwoAuthService $twoAuthService, AuthService $authService)
    {
        $this->twoAuthService = $twoAuthService;
        $this->authService = $authService;
    }
    public function initiateRegistration(RegisterRequest $request)

    {
        $this->twoAuthService->initiateRegistration($request->only(['name', 'email', 'password','role']));
        return $this->successResponse(null, 'Registration initiated successfully please check your email for verification code');
    }


    public function verifyRegistration(Request $request)
    {
        $response=$this->twoAuthService->verifyRegistration($request->only(['two_factor_code']));

        if($response['status']==false){
            return $this->errorResponse($response['error']);
        }
        $user=$this->authService->register($response);
        $user['role']=$response['role'];

        return $this->successResponse($user, 'Registration successful');
    }

public function resendTwoFactorCode()
{
    $this->twoAuthService->resendTwoFactorCode();

        return response()->json([
            'success' => true,
            'message' => 'Verification code resent'
        ]);
    }


}

