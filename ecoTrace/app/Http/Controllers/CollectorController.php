<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Booking;
use App\Models\Category;
use App\Rules\ValidEwasteType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollectorController extends Controller
{
    public function storeService(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'location' => 'required|string|max:255',
            'cost_per_kg' => 'required|numeric|min:0',
            'ewaste_types' => ['required', 'array', new ValidEwasteType],
        ]);

        Service::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'cost_per_kg' => $request->cost_per_kg,
            'status' => 'active',
            'ewaste_types' => $request->ewaste_types,
        ]);

        return redirect()->back()->with('success', 'E-Waste recycling service posted successfully!');
    }

    public function updateService(Request $request, $id)
    {
        $service = Service::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'location' => 'required|string|max:255',
            'cost_per_kg' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'ewaste_types' => ['required', 'array', new ValidEwasteType],
        ]);

        $service->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'cost_per_kg' => $request->cost_per_kg,
            'status' => $request->status,
            'ewaste_types' => $request->ewaste_types,
        ]);

        return redirect()->back()->with('success', 'Recycling service updated successfully!');
    }

    public function deleteService($id)
    {
        $service = Service::where('user_id', Auth::id())->findOrFail($id);
        $service->delete(); // Soft deletes service

        return redirect()->back()->with('success', 'Recycling service deleted successfully!');
    }

    public function updateBookingStatus(Request $request, $id)
    {
        $booking = Booking::whereHas('service', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($id);

        $request->validate([
            'status' => 'required|in:accepted,completed,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        $booking->update([
            'status' => $request->status,
            'notes' => $request->notes ?? $booking->notes,
        ]);

        // Send Email notification (dummy log email)
        try {
            \Illuminate\Support\Facades\Mail::to($booking->user->email)->send(new \App\Mail\ServiceBookingMail($booking));
        } catch (\Exception $e) {
            // Ignore mail issues locally
        }

        return redirect()->back()->with('success', "Booking status updated to '{$request->status}' successfully!");
    }
}
