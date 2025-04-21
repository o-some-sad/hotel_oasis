<?php

namespace App\Http\Resources\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReceptionistResource extends JsonResource {
    public function toArray(Request $request): array {
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at->format('d-m-Y'),
            'banned_at' => $this->banned_at,
            'id'=>$this->id
        ];

        if (auth()->check()) {
            $user = auth()->user();

            if ($user['role'] === 'admin') {
                $data['created_by'] = $this->manager ? $this->manager->name : 'N/A';
            }

            $data['action'] = $user->id === $this->created_by || $user['role'] === 'admin';
        }

        return $data;
    }
}
