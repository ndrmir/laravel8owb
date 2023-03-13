<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WBSupplierIncomes;

class wb_supplier_incomes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wb_supplier_incomes';

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
        $infoStocks = app(WBSupplierIncomes::class);
        $result = $infoStocks->saveData();
        echo $result;
    }
}
