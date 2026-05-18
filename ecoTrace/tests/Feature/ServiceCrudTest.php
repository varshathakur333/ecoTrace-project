<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceCrudTest extends TestCase
{
    use RefreshDatabase;

    private $collector;
    private $category;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a verified collector user
        $this->collector = User::create([
            'name' => 'Eco Collector',
            'email' => 'collector@example.com',
            'password' => bcrypt('password123'),
            'role' => 'collector',
            'phone' => '+91 99999 00000',
            'address' => 'Sector 62, Noida',
            'is_verified' => true,
        ]);

        // Create a dummy Category
        $this->category = Category::create([
            'name' => 'Smartphones',
            'slug' => 'smartphones',
            'description' => 'Mobile cellular devices',
        ]);
    }

    /**
     * Test storing a service via collector web route.
     */
    public function test_collector_can_create_service()
    {
        $response = $this->actingAs($this->collector)->post('/collector/service', [
            'category_id' => $this->category->id,
            'title' => 'Bulk Smartphone Recycling',
            'description' => 'We accept all brands including Apple, Samsung, Xiaomi.',
            'location' => 'Noida NCR',
            'cost_per_kg' => 50.00,
            'ewaste_types' => ['smartphone', 'battery'],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('services', [
            'title' => 'Bulk Smartphone Recycling',
            'location' => 'Noida NCR',
            'cost_per_kg' => 50.00,
        ]);
    }

    /**
     * Test custom validation rule ValidEwasteType.
     */
    public function test_service_creation_fails_with_invalid_ewaste_type()
    {
        $response = $this->actingAs($this->collector)->post('/collector/service', [
            'category_id' => $this->category->id,
            'title' => 'Bulk Smartphone Recycling',
            'description' => 'We accept all brands.',
            'location' => 'Noida NCR',
            'cost_per_kg' => 50.00,
            'ewaste_types' => ['smartphone', 'toxic-sludge-fake'], // Invalid type
        ]);

        $response->assertSessionHasErrors(['ewaste_types']);
    }
}
