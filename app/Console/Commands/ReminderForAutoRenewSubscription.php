<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CroneService;
use Illuminate\Support\Facades\Log;

class ReminderForAutoRenewSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ReminderForAutoRenewSubscription:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Profile subscription reminder befor 4 days of auto renewal';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::debug('Profile renew subscription cronJob Start');
        CroneService::reminderForAutoRenewSubscription();
        Log::debug('Profile renew subscription cronJob End');
    }
}
