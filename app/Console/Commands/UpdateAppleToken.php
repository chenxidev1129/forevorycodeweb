<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AppleToken;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class UpdateAppleToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdateAppleToken:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update apple secret key';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(AppleToken $appleToken)
    {
        parent::__construct();
        $this->appleToken = $appleToken;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::debug('Update apple secret token cronJob Start');
        $this->appleToken->generateClientSecret();
        Artisan::call('config:cache');
        Log::debug('Update apple secret token cronJob End');
    }
}
