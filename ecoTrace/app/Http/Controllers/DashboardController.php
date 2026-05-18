<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Booking;
use App\Models\Category;
use App\Models\VerificationRequest;
use App\Models\Analytics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Main dashboard entry router.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isCollector()) {
            return redirect()->route('collector.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    }

    public function adminDashboard()
    {
        $collectorsCount = \App\Models\User::where('role', 'collector')->count();
        $pendingRequests = VerificationRequest::with('user')->where('status', 'pending')->get();
        $categories = Category::all();
        $analytics = Analytics::getSnapshot();

        return view('dashboard.admin', compact('collectorsCount', 'pendingRequests', 'categories', 'analytics'));
    }

    public function collectorDashboard()
    {
        $user = Auth::user();
        
        // Eager load category
        $services = Service::with('category')->where('user_id', $user->id)->get();
        $bookings = Booking::with(['service', 'user'])
            ->whereIn('service_id', $services->pluck('id'))
            ->orderBy('booking_date', 'desc')
            ->get();

        return view('dashboard.collector', compact('services', 'bookings'));
    }

    public function userDashboard(Request $request)
    {
        $categories = Category::all();
        
        // Eager load collector (user) and category relations
        $servicesQuery = Service::with(['user', 'category'])->where('status', 'active');

        // Advanced filter search logic
        if ($request->filled('location')) {
            $servicesQuery->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('category_id')) {
            $servicesQuery->where('category_id', $request->category_id);
        }

        $services = $servicesQuery->get();
        $myBookings = Booking::with('service.user')->where('user_id', Auth::id())->orderBy('booking_date', 'desc')->get();

        return view('dashboard.user', compact('categories', 'services', 'myBookings'));
    }
}
