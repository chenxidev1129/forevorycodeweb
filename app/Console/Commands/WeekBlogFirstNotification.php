<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CroneService;
use Illuminate\Support\Facades\Log;

class WeekBlogFirstNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'WeekBlogFirstNotification:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Week Blog Notification first time after Signup';

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
        Log::debug('Week Blog Notification first time after Signup cronJob Start');
        CroneService::weekBlogFirst();
        Log::debug('Week Blog Notification first time after Signup cronJob End');
    }
}
