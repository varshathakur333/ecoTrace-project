<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollectorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\UserController;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// 1. Basic Welcome Route (Multilingual)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// 2. Request Lifecycle Debug Route (Returns request info, custom headers, cookies)
Route::get('/debug/lifecycle', function (Request $request) {
    // Demonstration of response types: JSON, custom headers, cookies
    return response()->json([
        'message' => 'Laravel Request Lifecycle Info',
        'method' => $request->method(),
        'uri' => $request->getRequestUri(),
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent(),
        'locale' => app()->getLocale(),
        'session_id' => session()->getId(),
        'cookies' => $request->cookies->all(),
        'headers' => $request->headers->all(),
    ])
    ->header('X-EcoTrace-Debug', 'Lifecycle-Verified')
    ->cookie('lifecycle_cookie_test', 'secure_cookie_value', 120);
});

// 3. Domain Routing Example (collector.ecotrace.test)
Route::domain('collector.ecotrace.test')->group(function () {
    Route::get('/info', function () {
        return response()->json([
            'subdomain' => 'collector',
            'message' => 'Welcome to EcoTrace Collector Subdomain Portal.'
        ]);
    });
});

// 4. Required & Optional Parameter Routing + Route Model Binding Example
// Required parameter
Route::get('/service/details/{id}', function ($id) {
    $service = Service::with('user')->findOrFail($id);
    return response()->json([
        'message' => 'Service fetched using required parameter',
        'service' => $service
    ]);
})->name('service.details.required');

// Optional parameter route
Route::get('/location/pickup/{city}/{area?}', function ($city, $area = 'Central') {
    return response()->json([
        'message' => 'Location pickup schedule request',
        'city' => $city,
        'area' => $area,
    ]);
});

// Route Model Binding Example
Route::get('/service/bind/{service}', function (Service $service) {
    return response()->json([
        'message' => 'Service fetched automatically via Route Model Binding!',
        'service' => $service
    ]);
});

// 5. Auth Web Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// 6. Language Switcher Route
Route::get('/lang/switch/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'hi'])) {
        session(['locale' => $locale]);
        cookie()->queue('locale', $locale, 43200); // 30 days
    }
    return redirect()->back()->with('success', 'Language switched successfully!');
})->name('lang.switch');

// 7. Authenticated Dashboard & Role-based Web Routes
Route::middleware(['auth'])->group(function () {
    
    // Core dashboard router
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Advanced Filtering Web Route (supports locations, category, caching)
    Route::get('/search', [FilterController::class, 'filter'])->name('search');

    // Admin Secure Group
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        
        // Category CRUD using Query Builder
        Route::post('/category', [AdminController::class, 'storeCategory'])->name('admin.category.store');
        Route::delete('/category/{id}', [AdminController::class, 'deleteCategory'])->name('admin.category.destroy');

        // Collector Approvals using Query Builder
        Route::post('/collector/approve/{id}', [AdminController::class, 'approveCollector'])->name('admin.collector.approve');
        Route::post('/collector/reject/{id}', [AdminController::class, 'rejectCollector'])->name('admin.collector.reject');
    });

    // Collector Secure Group
    Route::middleware(['role:collector'])->prefix('collector')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'collectorDashboard'])->name('collector.dashboard');
        
        // Service CRUD using Eloquent
        Route::post('/service', [CollectorController::class, 'storeService'])->name('collector.service.store');
        Route::put('/service/{id}', [CollectorController::class, 'updateService'])->name('collector.service.update');
        Route::delete('/service/{id}', [CollectorController::class, 'deleteService'])->name('collector.service.destroy');

        // Booking status update
        Route::post('/booking/{id}/status', [CollectorController::class, 'updateBookingStatus'])->name('collector.booking.status');
    });

    // Regular End-User (Customer) Secure Group
    Route::middleware(['role:user'])->prefix('user')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');
        
        // Bookings
        Route::post('/booking', [UserController::class, 'storeBooking'])->name('user.booking.store');
        Route::post('/booking/{id}/cancel', [UserController::class, 'cancelBooking'])->name('user.booking.cancel');

        // Profiles
        Route::put('/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');
    });
});
