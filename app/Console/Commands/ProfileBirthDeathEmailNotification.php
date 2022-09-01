<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CroneService;
use Illuminate\Support\Facades\Log;

class ProfileBirthDeathEmailNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ProfileBirthDeathEmailNotification:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send birth death email notification';

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
        Log::debug('Profile birth & death email notification cronJob Start');
        CroneService::sendProfileBirthEmail();
        Log::debug('Profile birth & death email notification cronJob End');
    }
}
