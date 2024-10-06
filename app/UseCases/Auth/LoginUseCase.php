<?php

namespace App\UseCases\Auth;

use App\Services\AuthService;

class LoginUseCase
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function execute(array $credentials)
    {
        $user = $this->authService->login($credentials);
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}