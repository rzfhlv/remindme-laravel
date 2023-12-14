<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            "ok" => true,
            "data" => [
                "user" => [
                    "id" => $this["user"]->id,
                    "email" => $this["user"]->email,
                    "name" => $this["user"]->name,
                ],
                "access_token" => $this["token"],
                "refresh_token" => $this["refresh"],
            ],
        ];
    }
}
