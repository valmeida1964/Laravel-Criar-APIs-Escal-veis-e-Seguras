<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovementResource extends JsonResource
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
            'product' => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'description' => $this->product->description,
                'category' => [
                    'id' => $this->product->category->id,
                    'name' => $this->product->category->name,
                ]
            ],
            'quantity' => $this->quantity,
            'movementType' => $this->movement_type,
            'createAt' => $this->created_at,
        ];
    }
}
