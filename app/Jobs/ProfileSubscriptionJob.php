<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\UserRepository;

class ProfileSubscriptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $profile;
    protected $jobtype;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 2;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($profile, $jobtype)
    {
        $this->profile = $profile;
        $this->jobtype = $jobtype;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    { 
        /* If admin inactivate the user account */
        if($this->jobtype == 'inactive'){
            UserRepository::inactiveProfileSubscription($this->profile);
        }
        
        /* if user activate the user account */
        if($this->jobtype == 'active'){
            UserRepository::resumeProfileSubscription($this->profile);
        }

    }
}
