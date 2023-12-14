<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class Reminder extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ok' => true,
            'data' => [
                'reminders' => ReminderMapResource::collection($this["reminders"]),
                'limit' => $this["limit"],
            ],
        ];
    }
}
