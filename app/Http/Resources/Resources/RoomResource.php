<?php

namespace App\Http\Resources\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /*
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'number' => $this->number,
            'capacity' => $this->capacity,
            'price' => '$' . number_format($this->price / 100, 2),
            'floor_id' => $this->floor ? $this->floor->name : null, // تأكد من وجود floor
            'id' => $this->id,
            'created_at' => $this->created_at->format('d-m-Y'),
        ];

        if (auth()->check()) {
            $user = auth()->user();

            if ($user['role'] == 'admin') {
                $data['created_by'] = $this->creator ? $this->creator->name : null; // تأكد من وجود creator
            }

            if ($user->id === $this->created_by || $user['role'] === 'admin') {
                $data['action'] = true;
            }
        }

        return $data;
    }
}


