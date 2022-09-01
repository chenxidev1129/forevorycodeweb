<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CroneService;
use Illuminate\Support\Facades\Log;

class ProfileCreateGreetingAfterDayNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ProfileCreateGreetingAfterDayNotification:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Profile greeting notification after 1 day';

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
        Log::debug('Profile greeting notification after 1 day cronJob Start');
        CroneService::profileGreatingAfterDay();
        Log::debug('Profile greeting notification after 1 day cronJob End');
    }
}
