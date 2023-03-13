<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OzonFboPostingListFlat;

class ozon_fbo_posting_list extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ozon_fbo_posting_list';

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
        $infoStocks = app(OzonFboPostingListFlat::class);
        $result = $infoStocks->saveData();
        echo $result;
    }
}
