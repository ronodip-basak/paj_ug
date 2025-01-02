<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidEmailOrPasswordException;
use App\Exceptions\InvalidTokenException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthService $authService;
    public function __construct()
    {
        $this->authService = new AuthService;
    }
    public function register(RegisterRequest $req){
        $this->authService->register($req);
        return response([
            'message' => "User created successfully"
        ], 201);
    }

    public function login(LoginRequest $req){
        try {
            return $this->authService->login($req);

        } catch (InvalidEmailOrPasswordException $th) {
            return response([
                'message' => 'Invalid email or password'
            ], 422);
        }
    }

    public function getAccessTokenFromRefreshToken (Request $req){
        try {
            $req->validate([
                'refresh_token' => 'required'
            ]);
            return $this->authService->getAccessTokenFromRefreshToken($req->refresh_token);
    
        } catch (InvalidTokenException $th) {
            return response([
                'message' => "Invalid refresh token"
            ], 403);
        }
        
    }
}
