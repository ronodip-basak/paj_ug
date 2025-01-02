<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResponse extends JsonResource
{
    public function __construct(
        private string $access_token,
        private string|null $refresh_token = null 
    )
    {
        
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'access_token' => $this->access_token
        ];

        if($this->refresh_token){
            $data['refresh_token'] = $this->refresh_token;
        }

        return $data;
    }
}
