<?php
/**
 * Invoice Ninja (https://invoiceninja.com)
 *
 * @link https://github.com/invoiceninja/invoiceninja source repository
 *
 * @copyright Copyright (c) 2020. Invoice Ninja LLC (https://invoiceninja.com)
 *
 * @license https://opensource.org/licenses/AAL
 */

namespace App\Console;

use App\Jobs\Cron\RecurringInvoicesCron;
use App\Jobs\Util\ReminderJob;
use App\Jobs\Util\VersionCheck;
use App\Utils\Ninja;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        
        //$schedule->job(new RecurringInvoicesCron)->hourly();
        $schedule->job(new VersionCheck)->daily();

        $schedule->job(new ReminderJob)->daily();
        
        /* Run queue's in shared hosting with this*/
        if (Ninja::isSelfHost()) {
            $schedule->command('queue:work')->everyMinute()->withoutOverlapping();
            $schedule->command('queue:restart')->everyFiveMinutes(); //we need to add this as we are seeing cached queues mess up the system on first load.
        }
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
