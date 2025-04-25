<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'price' => $this->price,
            'capacity' => $this->capacity,
            'reserved' => (bool) $this->reserved,
            'floor' => [
                'id' => $this->floor->id,
                'number' => $this->floor->number
            ],
            'created_by' => [
                'id' => $this->creator->id,
                'name' => $this->creator->name
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
