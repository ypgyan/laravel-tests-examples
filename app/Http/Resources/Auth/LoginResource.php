<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => "{$this->first_name}  {$this->last_name}",
            'email' => $this->email,
            'created_at' => $this->created_at->format('H:s:i d-m-Y'),
            'updated_at' => $this->updated_at->format('H:s:i d-m-Y'),
            'token' => $this->whenNotNull($this->token),
        ];
    }
}
