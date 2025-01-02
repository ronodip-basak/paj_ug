<?php

namespace App\Services;

use App\Exceptions\InvalidEmailOrPasswordException;
use App\Exceptions\InvalidTokenException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\LoginResponse;
use App\Models\RefreshToken;
use App\Models\User;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;

class AuthService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository;
    }

    public function register(RegisterRequest $request): User
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        return $this->userRepository->createUser($data, true);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $user = $this->userRepository->findUserByEmail($data['email']);

        if (!$user) {
            throw new InvalidEmailOrPasswordException("User or password invalid");
        }

        if (!Hash::check($data['password'], $user->password)) {
            throw new InvalidEmailOrPasswordException("User or password invalid");
        }

        return $this->generateAccessAndRefreshTokenForUser($user);
    }

    private function generateAccessAndRefreshTokenForUser(User $user): LoginResponse
    {

        $data = $this->userRepository->createRefreshAndAccessToken($user);

        return new LoginResponse($data['access_token'], $data['refresh_token']);
    }

    public function getAccessTokenFromRefreshToken(string $token_str)  {
        try {
            [$token, $token_str]  = $this->userRepository->getTokenFromTokenString($token_str);
        } catch (ModelNotFoundException $err) {
            throw new InvalidTokenException("Invalid Token", -1, $err);

        }
        
        if(Carbon::parse($token->expires_at)->isBefore(now())){
            throw new InvalidTokenException("Token Expired");
        }

        if(!Hash::check($token_str, $token->refresh_token)){
            throw new InvalidTokenException("Invalid Token");
        }

        $access_token = $token->user->createToken(Random::generate(4))->plainTextToken;
        return new LoginResponse($access_token);
    }
}
