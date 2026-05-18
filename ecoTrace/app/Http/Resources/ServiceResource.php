<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'services',
            'id' => (string) $this->id,
            'attributes' => [
                'title' => $this->title,
                'description' => $this->description,
                'location' => $this->location,
                'cost_per_kg' => (float) $this->cost_per_kg,
                'status' => $this->status,
                'ewaste_types' => $this->ewaste_types,
                'created_at' => $this->created_at?->toIso8601String(),
                'updated_at' => $this->updated_at?->toIso8601String(),
            ],
            'relationships' => [
                'collector' => [
                    'data' => [
                        'type' => 'users',
                        'id' => (string) $this->user_id,
                        'name' => $this->user?->name,
                        'business_name' => $this->user?->business_name,
                        'is_verified' => (bool) $this->user?->is_verified,
                    ]
                ],
                'category' => [
                    'data' => [
                        'type' => 'categories',
                        'id' => (string) $this->category_id,
                        'name' => $this->category?->name,
                    ]
                ]
            ],
            'links' => [
                'self' => url("/api/services/{$this->id}")
            ]
        ];
    }
}
