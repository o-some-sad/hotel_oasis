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
            'capacity' => $this->capacity,
            'price' => $this->price / 100, // هنا بنقسم السعر على 100
            'floor_id' => $this->floor_id,
            'is_reserved' => $this->is_reserved,
            'created_at' => $this->created_at,
            'creator' => $this->whenLoaded('creator', $this->creator),
            'floor' => $this->whenLoaded('floor', $this->floor),
        ];
    }
}
