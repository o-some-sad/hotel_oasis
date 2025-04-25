<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
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
            'client' => [
                'id' => $this->whenLoaded('client', fn() => $this->client->id),
                'name' => $this->whenLoaded('client', fn() => $this->client->name),
                'email' => $this->whenLoaded('client', fn() => $this->client->email),
                'phone' => $this->whenLoaded('client', fn() => $this->client->mobile),
            ],
            'room' => [
                'id' => $this->whenLoaded('room', fn() => $this->room->id),
                'number' => $this->whenLoaded('room', fn() => $this->room->number),
            ],
            'accompany_number' => $this->accompany_number,
            'paid_price' => $this->paid_price,
            'payment_status' => $this->payment_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}