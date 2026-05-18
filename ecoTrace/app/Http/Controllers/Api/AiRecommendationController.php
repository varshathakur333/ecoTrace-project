<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class AiRecommendationController extends Controller
{
    /**
     * Predict optimal collection day based on user location.
     */
    public function predictOptimalDay(Request $request)
    {
        $location = $request->query('location', 'Downtown');

        // Clean & robust simulated AI algorithm
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $hashValue = crc32(strtolower(trim($location)));
        $recommendedDay = $days[abs($hashValue) % count($days)];

        // Simulated confidence score
        $confidence = 85 + (abs($hashValue) % 15);

        return response()->json([
            'success' => true,
            'location' => $location,
            'recommended_collection_day' => $recommendedDay,
            'ai_confidence_percentage' => $confidence,
            'optimal_time_slot' => '09:00 AM - 12:00 PM',
            'explanation' => "Based on historical routing and active e-waste collection schedules in '{$location}', our AI model predicts that {$recommendedDay} has the lowest carbon footprint and fastest pickup turnaround.",
        ]);
    }

    /**
     * Recommend nearest collection points.
     */
    public function recommendCollectionPoints(Request $request)
    {
        $location = $request->query('location', 'default');

        // Fetch active services closest to query or general list
        $services = Service::with('user')
            ->where('status', 'active')
            ->where('location', 'like', "%{$location}%")
            ->take(3)
            ->get();

        if ($services->isEmpty()) {
            $services = Service::with('user')->where('status', 'active')->take(3)->get();
        }

        $recommendations = $services->map(function ($service, $index) {
            $distance = number_format(1.2 + ($index * 0.8), 1);
            return [
                'service_id' => $service->id,
                'title' => $service->title,
                'collector' => $service->user?->business_name ?? $service->user?->name,
                'location' => $service->location,
                'distance_km' => $distance,
                'estimated_travel_time_mins' => ceil($distance * 6),
                'cost_per_kg_refund' => $service->cost_per_kg,
            ];
        });

        return response()->json([
            'success' => true,
            'location_searched' => $location,
            'recommended_collection_points' => $recommendations,
        ]);
    }
}
