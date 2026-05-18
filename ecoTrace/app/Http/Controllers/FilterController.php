<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Category;
use App\Models\Analytics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FilterController extends Controller
{
    /**
     * Cache & filter listings with advanced parameters.
     */
    public function filter(Request $request)
    {
        $location = $request->input('location');
        $categoryId = $request->input('category_id');
        $date = $request->input('date');

        // Log search event & filter usage statistics in MongoDB/Local DB
        Analytics::log('filter', [
            'location' => $location,
            'category_id' => $categoryId,
            'date' => $date,
            'ip' => $request->ip(),
            'user_id' => auth()->id() ?? 'guest',
        ]);

        // Caching key generation based on filter parameters
        $cacheKey = 'services_filter_' . md5(json_encode([$location, $categoryId, $date]));

        // Cache filtered results for 10 minutes (600 seconds)
        $services = Cache::remember($cacheKey, 600, function () use ($location, $categoryId) {
            $query = Service::with(['user', 'category'])->where('status', 'active');

            if (!empty($location)) {
                $query->where('location', 'like', '%' . $location . '%');
            }

            if (!empty($categoryId)) {
                $query->where('category_id', $categoryId);
            }

            return $query->get();
        });

        // Cache top categories for 1 hour (3600 seconds)
        $topCategories = Cache::remember('top_categories', 3600, function () {
            return Category::withCount('services')
                ->orderBy('services_count', 'desc')
                ->take(5)
                ->get();
        });

        $categories = Category::all();

        return view('dashboard.user', [
            'categories' => $categories,
            'services' => $services,
            'myBookings' => auth()->check() ? \App\Models\Booking::with('service.user')->where('user_id', auth()->id())->get() : [],
            'topCategories' => $topCategories,
            'filters' => $request->all(),
        ]);
    }
}
