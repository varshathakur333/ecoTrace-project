<?php

namespace App\Http\Controllers;

use App\Models\Analytics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Category CRUD using Laravel Query Builder
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $slug = Str::slug($request->name);

        DB::table('categories')->insert([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Clear cached top categories since list changed
        \Illuminate\Support\Facades\Cache::forget('top_categories');

        return redirect()->back()->with('success', 'Category created successfully using Query Builder!');
    }

    public function deleteCategory($id)
    {
        DB::table('categories')->where('id', $id)->delete();
        \Illuminate\Support\Facades\Cache::forget('top_categories');

        return redirect()->back()->with('success', 'Category deleted successfully using Query Builder!');
    }

    /**
     * Verification requests using Query Builder
     */
    public function approveCollector($id)
    {
        $verificationRequest = DB::table('verification_requests')->where('id', $id)->first();

        if (!$verificationRequest) {
            return redirect()->back()->with('error', 'Request not found.');
        }

        // Update status of verification request
        DB::table('verification_requests')->where('id', $id)->update([
            'status' => 'approved',
            'notes' => 'Approved by Admin ' . auth()->user()->name,
            'updated_at' => now(),
        ]);

        // Update user status
        DB::table('users')->where('id', $verificationRequest->user_id)->update([
            'is_verified' => true,
            'updated_at' => now(),
        ]);

        // Log to MongoDB/Local Analytics
        Analytics::log('approval', [
            'collector_id' => $verificationRequest->user_id,
            'business_name' => $verificationRequest->business_name,
            'approved_by' => auth()->user()->id,
        ]);

        // Clear daily analytics cache snapshot
        \Illuminate\Support\Facades\Cache::forget('daily_analytics_snapshot');

        return redirect()->back()->with('success', 'Collector approved successfully!');
    }

    public function rejectCollector(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|string|max:500',
        ]);

        $verificationRequest = DB::table('verification_requests')->where('id', $id)->first();

        if (!$verificationRequest) {
            return redirect()->back()->with('error', 'Request not found.');
        }

        DB::table('verification_requests')->where('id', $id)->update([
            'status' => 'rejected',
            'notes' => $request->notes,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Collector verification rejected.');
    }
}
