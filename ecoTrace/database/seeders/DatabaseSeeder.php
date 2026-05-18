<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Categories
        $categories = [
            ['name' => 'Lithium-Ion Batteries', 'slug' => 'batteries', 'description' => 'Rechargeable batteries from phones, powerbanks, laptops.'],
            ['name' => 'Cellphones & Tablets', 'slug' => 'mobiles', 'description' => 'Old smartphones, tablets, iPods, and logic boards.'],
            ['name' => 'Monitors & Televisions', 'slug' => 'screens', 'description' => 'CRT monitors, LCD screens, and flat panels.'],
            ['name' => 'Computer Peripherals', 'slug' => 'computers', 'description' => 'Laptops, motherboards, RAM, CPUs, mice, keyboards.'],
            ['name' => 'Cables & Copper Wires', 'slug' => 'cables', 'description' => 'Power adapters, LAN cords, charger wires, extensions.'],
        ];

        foreach ($categories as $cat) {
            \App\Models\Category::create($cat);
        }

        // Fetch categories for services seeding
        $batteryCat = \App\Models\Category::where('slug', 'batteries')->first();
        $mobileCat = \App\Models\Category::where('slug', 'mobiles')->first();
        $computerCat = \App\Models\Category::where('slug', 'computers')->first();

        // 2. Create Admin User
        User::create([
            'name' => 'EcoTrace Admin',
            'email' => 'admin@ecotrace.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin',
            'phone' => '+91 90000 11111',
            'address' => 'New Delhi HQ',
            'is_verified' => true,
        ]);

        // 3. Create Collector (Verified)
        $collector = User::create([
            'name' => 'Delhi E-Waste Recycling Hub',
            'email' => 'collector@ecotrace.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'collector',
            'phone' => '+91 98888 77777',
            'address' => 'Sector 62, Noida, NCR',
            'business_name' => 'NCR Green Collectors Ltd.',
            'license_no' => 'LIC-EW-2026-NCR-99',
            'is_verified' => true,
        ]);

        // 4. Create Normal User
        $user = User::create([
            'name' => 'Varsha Thakur',
            'email' => 'user@ecotrace.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'user',
            'phone' => '+91 96666 55555',
            'address' => 'Vasant Kunj, New Delhi',
            'is_verified' => false,
        ]);

        // 5. Create Services for the collector
        $service1 = \App\Models\Service::create([
            'user_id' => $collector->id,
            'category_id' => $batteryCat->id,
            'title' => 'Industrial & Consumer Battery Pickup',
            'description' => 'Recycle rechargeable batteries with full eco-credit refund. We safely neutralize toxic acid residues.',
            'location' => 'Noida',
            'cost_per_kg' => 45.00,
            'status' => 'active',
            'ewaste_types' => ['battery'],
        ]);

        $service2 = \App\Models\Service::create([
            'user_id' => $collector->id,
            'category_id' => $mobileCat->id,
            'title' => 'Smartphone & Circuit Board Collection',
            'description' => 'Secure precious mineral recovery from old smartphones and computing circuit boards.',
            'location' => 'Delhi',
            'cost_per_kg' => 120.00,
            'status' => 'active',
            'ewaste_types' => ['smartphone', 'cable'],
        ]);

        // 6. Create Bookings
        \App\Models\Booking::create([
            'service_id' => $service2->id,
            'user_id' => $user->id,
            'booking_date' => now()->addDays(2),
            'weight' => 4.2,
            'status' => 'pending',
            'notes' => 'Contains three old iPhones and power cords.',
        ]);
    }
}
