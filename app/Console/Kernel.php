<?php

namespace App\Console;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\UpdateAppleToken::class,
        Commands\ProfileBirthDeathEmailNotification::class,
        Commands\ReminderForAutoRenewSubscription::class,
        Commands\ReminderForAutoRenewSubscriptionCustomer::class,
        Commands\ProfileCreateGreetingAfterDayNotification::class,
        Commands\ProfileCreateGreetingAfterMonthNotification::class,
        Commands\WeekBlogFirstNotification::class,
        Commands\WeekBlogEveryWeekNotification::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('UpdateAppleToken:command')->weekly();
        $schedule->command('ReminderForAutoRenewSubscription:command')->dailyAt('00:05');
        $schedule->command('ReminderForAutoRenewSubscriptionCustomer:command')->dailyAt('00:10');
        $schedule->command('ProfileBirthDeathEmailNotification:command')->dailyAt('00:15');
        $schedule->command('ProfileCreateGreetingAfterDayNotification:command')->everyMinute();
        $schedule->command('ProfileCreateGreetingAfterMonthNotification:command')->everyMinute();
        $schedule->command('WeekBlogFirstNotification:command')->dailyAt('00:20');
        $schedule->command('WeekBlogEveryWeekNotification:command')->dailyAt('00:25');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
