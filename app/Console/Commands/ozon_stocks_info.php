<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OzonStocksInfo;

class ozon_stocks_info extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ozon_stocks_info';

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
        $infoStocks = app(OzonStocksInfo::class);
        $result = $infoStocks->saveData();
        echo $result;
    }
}
