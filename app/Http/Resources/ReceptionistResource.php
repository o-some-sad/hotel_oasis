<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReceptionistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at->format('d-m-Y'),
        ];
        if (auth()->check() && auth()->user()->hasRole('admin')) {
           $data['created_by'] = $this->manager ? $this->manager->name : 'N/A';
        }
        return $data;
    }
}
