<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WBOrderInfo extends Model
{
    use HasFactory;

    public $timestamps = false;
    private $curl;
    private $url;
    private $tablename;

    protected $fillable = [
        'id',
        'date',
        'lastChangeDate',
        'supplierArticle',
        'techSize',
        'barcode',
        'totalPrice',
        'discountPercent',
        'warehouseName',
        'oblast',
        'incomeID',
        'odid',
        'nmId',
        'subject',
        'category',
        'brand',
        'isCancel',
        'cancel_dt',
        'gNumber',
        'sticker',
        'srid',
    ];
    protected $connection = 'mysql_wb';

    public function __construct(array $attributes = [])
    {
        $this->curl = app(WBCurl::class);
        // Заказы
        $data['dateFrom'] = date(DATE_RFC3339, strtotime('2020-02-26'));
        $this->url = 'https://statistics-api.wildberries.ru/api/v1/supplier/orders' . '?dateFrom=' . $data['dateFrom'];

        $this->tablename = 'w_b_order_infos';

        parent::__construct($attributes);
    }

    public function saveData()
    {
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
        foreach ($response as $key => $row) {
            // print_r($row);
            $insertData[$key] = $row;
            $insertData[$key]['isCancel'] = $row['isCancel'] ? 1 : 0;
        }
        $countEntries = count($insertData);
        $keys = array_keys($insertData[0]);

        // upsert не работает - слишком много записей

        foreach ($insertData as $key => $row) {
            try {
                $orderInfo = $this::updateOrCreate([
                    'odid' => $row['odid'],
                ], $row);

                $id = $orderInfo->id;
                $msg = 'Insert or update in ' . $this->tablename . ' id = ' . $id . PHP_EOL;
                echo $msg;
                // \Log::channel('schedule')->info($msg);
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
