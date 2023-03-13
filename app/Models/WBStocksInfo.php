<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WBCurl;

class WBStocksInfo extends Model
{
    use HasFactory;

    public $timestamps = false;
    private $curl;
    private $data = [];
    private $url;
    private $tablename;

    protected $fillable = [
        'id',
        'lastChangeDate',
        'supplierArticle',
        'techSize',
        'barcode',
        'quantity',
        'isSupply',
        'isRealization',
        'quantityFull',
        'warehouseName',
        'nmId',
        'subject',
        'category',
        'daysOnSite',
        'brand',
        'SCCode',
        'Price',
        'Discount',
        'date',
    ];
    protected $connection = 'mysql_wb';

    public function __construct(array $attributes = [])
    {
        $this->curl = app(WBCurl::class);
        // Получаем остатки
        $this->data['dateFrom'] = date(DATE_RFC3339, strtotime('2020-02-26'));
        $this->url = 'https://statistics-api.wildberries.ru/api/v1/supplier/stocks' . '?dateFrom=' . $this->data['dateFrom'];

        $this->tablename = 'w_b_stocks_infos';

        parent::__construct($attributes);
    }

    public function saveData()
    {
        $strDate = date("Y-m-d H:i:s", time());

        $response = $this->curl->getWB($this->url, 'statistic');

        // В случае превышения количества запросов, делаю sleep(2) и повторный запрос 20 попыток
        if (isset($response['errors'][0]) && ($response['errors'][0] === '(api-new) too many requests')) {
            $i = 1;
            while ($i <= 20) {
                sleep(2);
                $msg = 'Repeating request ' . $this->url . ' count ' . $i . PHP_EOL;
                echo $msg;
                \Log::channel('schedule')->info($msg);
                $response = $this->curl->getWB($this->url, 'statistic');
                if (!isset($response['errors'][0])) {
                    break;
                }
                $i++;
            }
        }

        // Loop through data and insert records into table        
        $insertData = [];
        foreach ($response as $row) {
            // print_r($row);
            $row['isSupply'] = $row['isSupply'] ? 1 : 0;
            $row['isRealization'] = $row['isRealization'] ? 1 : 0;
            $row['date'] = $strDate;
            $insertData[] = $row;
        }
        $countEntries = count($insertData);
        $splitInsert = array_chunk($insertData, 50);
        foreach ($splitInsert as $item) {
            // print_r($item);
            try {
                $stocksInfo = $this::insert($item);
            } catch (\Illuminate\Database\QueryException $exception) {
                // You can check get the details of the error using `errorInfo`:
                $errorInfo = $exception->errorInfo;
                dd($errorInfo);
                // Return the response to the client..
            }
        }
        $msg = 'Insert or update in ' . $this->tablename . ' ' . $countEntries . ' entries' . PHP_EOL;
        echo $msg;
        \Log::channel('schedule')->info($msg);
        return 'Success data insert' . PHP_EOL;
    }
}
