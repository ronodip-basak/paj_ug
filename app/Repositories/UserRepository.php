<?php

namespace App\Repositories;

use App\Http\Requests\RegisterRequest;
use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;

class UserRepository
{
    public function createUser(array $data, bool $mark_as_verified = false): User
    {
        try {
            DB::beginTransaction();
            $user = User::create($data);
            if ($mark_as_verified) {
                $user->email_verified_at = now();
            }

            $user->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }


        return $user;
    }

    public function findUserByEmail(string $email): User|null
    {
        return User::query()->where('email', $email)->where('email_verified_at', "!=", null)->first();
    }

    public function createRefreshAndAccessToken(User $user): array
    {
        try {
            DB::beginTransaction();
            
            $refresh_token = Random::generate(64);
            $token = $user->refreshTokens()->create([
                'refresh_token' => Hash::make($refresh_token),
                'expires_at' => now()->addMonth(6)
            ]);

            $accessToken = $user->createToken(Random::generate(6), [], now()->addHours(12))->plainTextToken;

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }


        return [
            'access_token' => $accessToken,
            'refresh_token' => $token->id . "|" . $refresh_token
        ];
    }

    public function getTokenFromTokenString(string $token_str) : array {
        try{
            [$token_id, $token_str] = explode("|", $token_str);
            return [RefreshToken::findOrFail($token_id), $token_str];
            
            
            ;

        }
        catch(\Throwable $th){
            throw new ModelNotFoundException("Invalid Token String", -1, $th);
        }
    }
}
