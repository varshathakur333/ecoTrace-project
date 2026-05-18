<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\VerificationRequest;
use App\Models\Analytics;
use Illuminate\Console\Command;

class BulkApproveCollectors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:bulk-approve-collectors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bulk approve all pending collectors verification requests';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pendingRequests = VerificationRequest::where('status', 'pending')->get();

        if ($pendingRequests->isEmpty()) {
            $this->info("No pending collector verification requests found.");
            return Command::SUCCESS;
        }

        $count = 0;
        foreach ($pendingRequests as $request) {
            // Update request
            $request->update([
                'status' => 'approved',
                'notes' => 'Bulk approved via Artisan console command.',
            ]);

            // Verify collector user
            $user = $request->user;
            if ($user) {
                $user->update([
                    'is_verified' => true,
                ]);
            }

            // Log analytics
            Analytics::log('approval', [
                'collector_id' => $request->user_id,
                'business_name' => $request->business_name,
                'approved_by' => 'artisan_command',
            ]);

            $count++;
        }

        $this->info("Successfully approved {$count} pending collectors!");
        return Command::SUCCESS;
    }
}
