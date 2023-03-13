<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WBSalesInfo extends Model
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
        'isSupply',
        'isRealization',
        'promoCodeDiscount',
        'warehouseName',
        'countryName',
        'oblastOkrugName',
        'regionName',
        'incomeID',
        'saleID',
        'odid',
        'spp',
        'forPay',
        'finishedPrice',
        'priceWithDisc',
        'nmId',
        'subject',
        'category',
        'brand',
        'IsStorno',
        'gNumber',
        'sticker',
        'srid',
    ];
    protected $connection = 'mysql_wb';

    public function __construct(array $attributes = [])
    {
        $this->curl = app(WBCurl::class);
        // Продажи
        $data['dateFrom'] = date(DATE_RFC3339, strtotime('2020-02-26'));
        $this->url = 'https://statistics-api.wildberries.ru/api/v1/supplier/sales' . '?dateFrom=' . $data['dateFrom'];
        $this->tablename = 'w_b_sales_infos';

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
            $row['isSupply'] = $row['isSupply'] ? 1 : 0;
            $row['isRealization'] = $row['isRealization'] ? 1 : 0;
            $row['IsStorno'] = $row['IsStorno'] ? 1 : 0;
            $row['techSize'] = $row['techSize'] ? $row['techSize'] : null;
            $insertData[] = $row;
        }
        $keys = array_keys($insertData[0]);
        // print_r($keys);
        $countEntries = count($insertData);
        $splitInsert = array_chunk($insertData, 50);
        foreach ($splitInsert as $item) {
            // print_r($item);
            try {
                $salesInfo = $this::upsert(
                    $item,
                    [
                    'saleID',
                    ],
                    $keys
                );
                // $salesInfo = $this::create($row);
                // $id = $salesInfo->id;
            } catch (\Illuminate\Database\QueryException $exception) {
                // You can check get the details of the error using `errorInfo`:
                $errorInfo = $exception->errorInfo;
                dd($errorInfo);
                // Return the response to the client..
            }
        }
        $msg = 'Insert or update ' . $this->tablename . ' ' . $countEntries . ' entries' . PHP_EOL;
        echo $msg;
        \Log::channel('schedule')->info($msg);
        return 'Success data insert' . PHP_EOL;
    }
}
