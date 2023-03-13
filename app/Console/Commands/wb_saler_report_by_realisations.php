<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WBSalerReportByRealisation;

class wb_saler_report_by_realisations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wb_saler_report_by_realisations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $infoStocks = app(WBSalerReportByRealisation::class);
        $result = $infoStocks->saveData();
        echo $result;
    }
}
