<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\OzonStocksInfoJob;
use App\Jobs\WBStocksInfoJob;
use App\Jobs\OzonFboPostingListFlatJob;
use App\Jobs\WBOrderInfoJob;
use App\Jobs\WBPriceInfoJob;
use App\Jobs\WBSalerReportByRealisationJob;
use App\Jobs\WBSalesInfoJob;
use App\Jobs\WBSupplierIncomesJob;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->job(new OzonStocksInfoJob)->cron('34 0 * * *');
        $schedule->job(new WBStocksInfoJob)->cron('34 0 * * *');
        $schedule->job(new OzonFboPostingListFlatJob)->cron('34 0 * * *');
        $schedule->job(new WBOrderInfoJob)->cron('34 0 * * *');
        $schedule->job(new WBPriceInfoJob)->cron('34 0 * * *');
        $schedule->job(new WBSalerReportByRealisationJob)->cron('34 0 * * *');
        $schedule->job(new WBSalesInfoJob)->cron('34 0 * * *');
        $schedule->job(new WBSupplierIncomesJob)->cron('34 0 * * *');
        
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
