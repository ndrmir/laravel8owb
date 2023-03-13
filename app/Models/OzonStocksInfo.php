<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OzonCurl;

class OzonStocksInfo extends Model
{
    use HasFactory;

    public $timestamps = false;
    private $curl;
    private $data;
    private $method;
    private $infoStoksTablename;

    protected $fillable = [
        'id',
        'product_id',
        'offer_id',
        'fbs_present',
        'fbs_reserved',
        'fbo_present',
        'fbo_reserved',
        'date',
    ];
    protected $connection = 'mysql';

    public function __construct(array $attributes = [])
    {
        $this->curl = app(OzonCurl::class);
        // Получаем остатки
        $this->data = '{
            "filter": {
                "visibility": "ALL"
                },
            "last_id": "",
            "limit": 10
        }';
        $this->method = "/v3/product/info/stocks";
        $this->infoStoksTablename = 'ozon_stocks_infos';

        parent::__construct($attributes);
    }

    public function saveData()
    {
        $strDate = date("Y-m-d H:i:s", time());
        // dd($strDate);
        $total = 1;

        while ($total) {
            $response = $this->curl->postOzon($this->data, $this->method);
            // print_r($response);
            $result = $response['result'];
            $lastId = $result['last_id'];
            $total = $result['total'];

            $this->data = json_decode($this->data, true);
            $this->data['last_id'] = $lastId;
            $this->data = json_encode($this->data);

            if (!$total) {
                break;
            }

            $items = $result['items'];

            $insertData = [];
            foreach ($items as $key => $value) {
                if (count($value['stocks']) > 0) {
                    foreach ($value['stocks'] as $k => $v) {
                        if ($v['type'] === 'fbs') {
                            $insertData[$key]['fbs_present'] = $v['present'];
                            $insertData[$key]['fbs_reserved'] = $v['reserved'];
                        }
                        if ($v['type'] === 'fbo') {
                            $insertData[$key]['fbo_present'] = $v['present'];
                            $insertData[$key]['fbo_reserved'] = $v['reserved'];
                        }
                    }
                } else {
                    $insertData[$key]['fbs_present'] = null;
                    $insertData[$key]['fbs_reserved'] = null;
                    $insertData[$key]['fbo_present'] = null;
                    $insertData[$key]['fbo_reserved'] = null;
                }
                $insertData[$key]['product_id'] = $value['product_id'];
                $insertData[$key]['offer_id'] = $value['offer_id'];
                $insertData[$key]['date'] = $strDate;
            }
            $count = count($insertData);
            // print_r($insertData);
            try {
                $infoStocks = $this::insert($insertData);
            } catch (\Illuminate\Database\QueryException $exception) {
                // You can check get the details of the error using `errorInfo`:
                $errorInfo = $exception->errorInfo;
                dd($errorInfo);
                // Return the response to the client..
            }
            $msg = 'Insert in ozon_stocks_infos ' . $count . ' entries' . PHP_EOL;
            echo $msg;
            \Log::channel('schedule')->info($msg);
        }
        return 'Success data insert' . PHP_EOL;
    }
}
