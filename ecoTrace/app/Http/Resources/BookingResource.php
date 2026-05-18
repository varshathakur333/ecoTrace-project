<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'bookings',
            'id' => (string) $this->id,
            'attributes' => [
                'booking_date' => $this->booking_date?->toDateString(),
                'weight' => (float) $this->weight,
                'status' => $this->status,
                'notes' => $this->notes,
                'photo_url' => $this->photo_path ? asset('storage/' . $this->photo_path) : null,
                'created_at' => $this->created_at?->toIso8601String(),
                'updated_at' => $this->updated_at?->toIso8601String(),
            ],
            'relationships' => [
                'service' => [
                    'data' => [
                        'type' => 'services',
                        'id' => (string) $this->service_id,
                        'title' => $this->service?->title,
                    ]
                ],
                'user' => [
                    'data' => [
                        'type' => 'users',
                        'id' => (string) $this->user_id,
                        'name' => $this->user?->name,
                    ]
                ]
            ]
        ];
    }
}
