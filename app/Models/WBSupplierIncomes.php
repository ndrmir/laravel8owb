<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WBSupplierIncomes extends Model
{
    use HasFactory;

    public $timestamps = false;
    private $curl;
    private $url;
    private $tablename;

    protected $fillable = [
        'id',
        'incomeId',
        'number',
        'date',
        'lastChangeDate',
        'supplierArticle',
        'techSize',
        'barcode',
        'quantity',
        'totalPrice',
        'dateClose',
        'warehouseName',
        'nmId',
        'status',
    ];
    protected $connection = 'mysql_wb';

    public function __construct(array $attributes = [])
    {
        $this->curl = app(WBCurl::class);

        // Поставки
        $method = '/api/v1/supplier/incomes';
        $data['dateFrom'] = date(DATE_RFC3339, strtotime('2020-02-26'));
        $this->url = 'https://statistics-api.wildberries.ru' . $method . '?dateFrom=' . $data['dateFrom'];

        $this->tablename = 'w_b_supplier_incomes';

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
        $insertData = $response;
        $countEntries = count($insertData);
        $keys = array_keys($insertData[0]);
        $splitInsert = array_chunk($insertData, 50);
        foreach ($splitInsert as $item) {
            try {
                $supplierIncomes = $this::upsert(
                    $insertData,
                    [
                    'incomeId',
                    'barcode',
                    ],
                    $keys
                );
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
