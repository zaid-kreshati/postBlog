<?php

namespace App\Services;

use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Traits\JsonResponseTrait;

class AuthService
{
    use JsonResponseTrait;
    protected $AuthRepository;

    public function __construct(AuthRepository $AuthRepository)
    {
        $this->AuthRepository = $AuthRepository;
    }

    public function register(array $data)
    {
        Log::info($data);
        // Hash the password before saving
        $data['password'] = ($data['password']);

           // Call repository to create post
           $user= $this->AuthRepository->create([
               'name' => $data['name'],
               'email' => $data['email'],
               'password' => $data['password'],
               'role' => $data['role'],
           ]);

           Auth::login($user);
        // Return the user and token information
        return $this->successResponse('Registration successful!');
    }

    public function login(array $credentials)
    {
        // Attempt to authenticate the user with the given credentials
        if (Auth::attempt($credentials)) {
            // Get the authenticated user
            $user = Auth::user();

            // Generate a new token for the authenticated user
            $token = $user->createToken('MyApp')->accessToken;
            $role=$user->roles->first()->name;
            $response=[
                'status' => 'success',
                'user' => $user,
                'access_token' => $token,
                'role' => $role,
            ];

        } else {
            $response=[
                'status' => 'error',
                'message' => 'Invalid credentials.',
            ];
        }
        return $response;
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return $this->successResponse('Logged out successfully.');
    }

}
