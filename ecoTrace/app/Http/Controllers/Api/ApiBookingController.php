<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Http\Resources\BookingResource;
use Illuminate\Http\Request;

class ApiBookingController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user() ?? \App\Models\User::where('role', 'user')->first();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $bookings = Booking::with(['service', 'user'])
            ->where('user_id', $user->id)
            ->orderBy('booking_date', 'desc')
            ->paginate(15);

        return BookingResource::collection($bookings);
    }

    public function store(Request $request)
    {
        $user = auth()->user() ?? \App\Models\User::where('role', 'user')->first();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'weight' => 'required|numeric|min:0.5',
            'notes' => 'nullable|string|max:500',
        ]);

        $booking = Booking::create([
            'service_id' => $request->service_id,
            'user_id' => $user->id,
            'booking_date' => $request->booking_date,
            'weight' => $request->weight,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully!',
            'data' => new BookingResource($booking)
        ], 201);
    }
}
