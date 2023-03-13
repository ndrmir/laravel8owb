<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WBPriceInfo extends Model
{
    use HasFactory;

    public $timestamps = false;
    private $curl;
    private $url;
    private $tablename;

    protected $fillable = [
        'id',
        'nmId',
        'price',
        'discount',
        'promoCode',
        'date',
    ];
    protected $connection = 'mysql_wb';

    public function __construct(array $attributes = [])
    {
        $this->curl = app(WBCurl::class);

        // Цены
        $this->url = $url = 'https://suppliers-api.wildberries.ru/public/api/v1/info' . '?quantity=0';
        $this->tablename = 'w_b_price_infos';

        parent::__construct($attributes);
    }

    public function saveData()
    {
        $strDate = date("Y-m-d H:i:s", time());

        $response = $this->curl->getWB($this->url, 'supplier');

        // В случае превышения количества запросов, делаю sleep(2) и повторный запрос 20 попыток
        if (isset($response['errors'][0]) && ($response['errors'][0] === '(api-new) too many requests')) {
            $i = 1;
            while ($i <= 20) {
                sleep(2);
                $msg = 'Repeating request ' . $this->url . ' count ' . $i . PHP_EOL;
                echo $msg;
                \Log::channel('schedule')->info($msg);
                $response = $this->curl->getWB($this->url, 'supplier');
                if (!isset($response['errors'][0])) {
                    break;
                }
                $i++;
            }
        }

        // Loop through data and insert records into table
        $countEntries = count($response);
        foreach ($response as $row) {
            $row['date'] = $strDate;
            // print_r($row);
            try {
                $priceInfo = $this::create($row);
                $id = $priceInfo->id;
            } catch (\Illuminate\Database\QueryException $exception) {
                // You can check get the details of the error using `errorInfo`:
                $errorInfo = $exception->errorInfo;
                dd($errorInfo);
                // Return the response to the client..
            }
            $msg = 'Insert in ' . $this->tablename . ' id = ' . $id . PHP_EOL;
            echo $msg;
            // \Log::channel('schedule')->info($msg);
        }
        $msg = 'Insert or update in ' . $this->tablename . ' ' . $countEntries . ' entries' . PHP_EOL;
        echo $msg;
        \Log::channel('schedule')->info($msg);
        return 'Success data insert' . PHP_EOL;
    }
}
