<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class Analytics
{
    /**
     * Store analytics log (search event, filter usage, collector approval).
     */
    public static function log(string $type, array $data): void
    {
        // First, write to standard Laravel log for transparency
        Log::info("EcoTrace Analytics Log [{$type}]: " . json_encode($data));

        try {
            // Ensure the analytics table exists in SQLite/MySQL as a standard relational table for persistent local logs
            if (!Schema::hasTable('analytics_logs')) {
                Schema::create('analytics_logs', function (Blueprint $table) {
                    $table->id();
                    $table->string('type');
                    $table->json('payload');
                    $table->timestamps();
                });
            }

            DB::table('analytics_logs')->insert([
                'type' => $type,
                'payload' => json_encode($data),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        } catch (\Exception $e) {
            Log::warning("Failed to store local DB log: " . $e->getMessage());
        }

        // Dummy/Simulated MongoDB logic block
        // In a true MongoDB environment with 'mongodb/laravel-mongodb' package, this would extend:
        // MongoDB\Laravel\Eloquent\Model
        // and we would call self::create(['type' => $type, 'data' => $data]);
        try {
            if (class_exists('MongoDB\Client')) {
                // If the real MongoDB client extension is available, attempt connection
                // $client = new \MongoDB\Client(env('MONGODB_URI', 'mongodb://localhost:27017'));
                // $collection = $client->ecotrace->analytics;
                // $collection->insertOne(['type' => $type, 'payload' => $data, 'timestamp' => new \MongoDB\BSON\UTCDateTime()]);
            }
        } catch (\Exception $e) {
            Log::warning("MongoDB Logger encountered an error (ignoring gracefully): " . $e->getMessage());
        }
    }

    /**
     * Retrieve analytics snapshot
     */
    public static function getSnapshot(): array
    {
        try {
            if (Schema::hasTable('analytics_logs')) {
                $logs = DB::table('analytics_logs')->get();
                return [
                    'total_events' => $logs->count(),
                    'searches' => $logs->where('type', 'search')->count(),
                    'filters' => $logs->where('type', 'filter')->count(),
                    'approvals' => $logs->where('type', 'approval')->count(),
                    'recent_logs' => $logs->take(10)->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'type' => $item->type,
                            'payload' => json_decode($item->payload, true),
                            'created_at' => $item->created_at,
                        ];
                    })->toArray()
                ];
            }
        } catch (\Exception $e) {
            Log::error("Failed to retrieve analytics snapshot: " . $e->getMessage());
        }

        return [
            'total_events' => 0,
            'searches' => 0,
            'filters' => 0,
            'approvals' => 0,
            'recent_logs' => []
        ];
    }
}
