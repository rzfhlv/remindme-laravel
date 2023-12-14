<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReminderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ok' => true,
            'data' => [
                'id' => $this->id,
                'title' => $this->title,
                'description' => $this->description,
                'remind_at' => $this->remind_at,
                'event_at' => $this->event_at,
            ],
        ];
    }
}
