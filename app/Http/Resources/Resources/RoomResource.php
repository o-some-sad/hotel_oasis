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
            'price' => '$' . number_format($this->price, 2),
            'floor' => $this->floor?->name,
            'id'=>$this->id
        ];

        if (auth()->check()) {
            $user = auth()->user();

            if ($user['role']=='admin') {
                $data['created_by'] =  $this->creator?->name;
            }

            if ($user->id === $this->created_by || $user['role'] === 'admin') {
                $data['action'] = true;
            }

        }
        return $data;
        }

}
