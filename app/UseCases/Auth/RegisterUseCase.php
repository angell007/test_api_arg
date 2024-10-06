<?php

namespace App\UseCases\Auth;

use App\Services\AuthService;

class RegisterUseCase
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function execute(array $data)
    {
        $user = $this->authService->register($data);
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}