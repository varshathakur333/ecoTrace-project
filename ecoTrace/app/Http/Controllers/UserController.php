<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function storeBooking(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'weight' => 'required|numeric|min:0.5',
            'notes' => 'nullable|string|max:500',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // E-waste photo upload
        ]);

        $service = Service::findOrFail($request->service_id);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            // Store e-waste photo in public uploads folder
            $photoPath = $request->file('photo')->store('ewaste_photos', 'public');
        }

        $booking = Booking::create([
            'service_id' => $service->id,
            'user_id' => Auth::id(),
            'booking_date' => $request->booking_date,
            'weight' => $request->weight,
            'status' => 'pending',
            'notes' => $request->notes,
            'photo_path' => $photoPath,
        ]);

        // Email booking confirmation to user (dummy log email)
        try {
            \Illuminate\Support\Facades\Mail::to(Auth::user()->email)->send(new \App\Mail\ServiceBookingMail($booking));
        } catch (\Exception $e) {
            // Ignore email errors locally
        }

        return redirect()->back()->with('success', 'E-waste collection booking submitted successfully! The collector will review your pickup request.');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
        ]);

        $user->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function cancelBooking($id)
    {
        $booking = Booking::where('user_id', Auth::id())->findOrFail($id);
        $booking->update([
            'status' => 'cancelled',
        ]);

        return redirect()->back()->with('success', 'Booking cancelled successfully.');
    }
}
