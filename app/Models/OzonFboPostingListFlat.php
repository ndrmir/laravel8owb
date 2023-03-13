<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OzonFboPostingListFlat extends Model
{
    use HasFactory;

    public $timestamps = false;
    private $curl;
    private $url;
    private $tablename;
    private $data;
    private $method;

    protected $fillable = [
        'id',
        'order_id',
        'order_number',
        'posting_number',
        'status',
        'cancel_reason_id',
        'created_at',
        'in_process_at',
        'additional_data',
        'products_sku',
        'products_name',
        'products_quantity',
        'products_offer_id',
        'products_price',
        'products_digital_codes',
        'products_currency_code',
        'AD_region',
        'AD_city',
        'AD_delivery_type',
        'AD_is_premium',
        'AD_payment_type_group_name',
        'AD_warehouse_id',
        'AD_warehouse_name',
        'AD_is_legal',
        'FD_cluster_from',
        'FD_cluster_to',
        'FD_products_commission_amount',
        'FD_products_commission_percent',
        'FD_products_payout',
        'FD_products_product_id',
        'FD_products_old_price',
        'FD_products_price',
        'FD_products_total_discount_value',
        'FD_products_total_discount_percent',
        'FD_products_actions',
        'FD_products_picking',
        'FD_products_quantity',
        'FD_products_client_price',
        'FD_products_currency_code',
        'FD_products_IS_MSI_fulfillment',
        'FD_products_IS_MSI_pickup',
        'FD_products_IS_MSI_dropoff_pvz',
        'FD_products_IS_MSI_dropoff_sc',
        'FD_products_IS_MSI_dropoff_ff',
        'FD_products_IS_MSI_direct_flow_trans',
        'FD_products_IS_MSI_return_flow_trans',
        'FD_products_IS_MSI_deliv_to_customer',
        'FD_products_IS_MSI_return_not_deliv_to_customer',
        'FD_products_IS_MSI_return_part_goods_customer',
        'FD_products_IS_MSI_return_after_deliv_to_customer',
        'FD_PS_MSI_fulfillment',
        'FD_PS_MSI_pickup',
        'FD_PS_MSI_dropoff_pvz',
        'FD_PS_MSI_dropoff_sc',
        'FD_PS_MSI_dropoff_ff',
        'FD_PS_MSI_direct_flow_trans',
        'FD_PS_MSI_return_flow_trans',
        'FD_PS_MSI_deliv_to_customer',
        'FD_PS_MSI_return_not_deliv_to_customer',
        'FD_PS_MSI_return_part_goods_customer',
        'FD_PS_MSI_return_after_deliv_to_customer',
    ];
    protected $connection = 'mysql';

    public function __construct(array $attributes = [])
    {
        $this->curl = app(OzonCurl::class);
        // Список отправлений
        $strDate = date("Y-m-d\TH:i:s\Z", time());

        $this->data = '{
            "dir": "ASC",
            "filter": {
            "since": "2023-01-01T10:44:12.828Z",
            "status": "",
            "to": "' . $strDate . '"
            },
            "limit": 5,
            "offset": 0,
            "translit": true,
            "with": {
            "analytics_data": true,
            "financial_data": true
            }
        }';

        $this->method = "/v2/posting/fbo/list";
        $this->tablename = 'ozon_fbo_posting_list_flats';

        parent::__construct($attributes);
    }

    public function saveData()
    {
        $count = 1;
        $offset = 5 - 1;
        while ($count) {
            $response = $this->curl->postOzon($this->data, $this->method);
            $items = $response['result'];
            // print_r($items);
            // exit;
            $count = count($items);
            if (!$count) {
                break;
            }

            $this->data = json_decode($this->data, true);
            $this->data['offset'] = $offset;
            $this->data = json_encode($this->data);


            foreach ($items as $key => $value) {
                $insertData[$key] = $this->recursionField($value, '');
                // print_r($insertData[$key]);
            }
            // insert records into table
            $keys = array_keys($insertData[0]);
            // print_r($keys);
            $countEntries = count($insertData);
            
            try {
                $fboPostingListFlat = $this::upsert(
                    $insertData,
                    [
                        'order_id',
                        'posting_number',
                        'products_sku',
                    ],
                    $keys
                );
                $msg = 'Insert or update ' . $this->tablename . ' ' . $countEntries . ' entries' . PHP_EOL;
                echo $msg;
                \Log::channel('schedule')->info($msg);
            } catch (\Illuminate\Database\QueryException $exception) {
                // You can check get the details of the error using `errorInfo`:
                $errorInfo = $exception->errorInfo;
                dd($errorInfo);
                // Return the response to the client..
            }
            $offset += 5;
        }
        return 'Success data insert' . PHP_EOL;
    }

    public function recursionField($array, $keyStr)
    {
        $fields = [];
        foreach ($array as $key => $value) {
            switch ($key) {
                case 'analytics_data':
                    $key = 'AD';
                    break;
                case 'financial_data':
                    $key = 'FD';
                    break;
                case 'item_services':
                    $key = 'IS';
                    break;
                case 'posting_services':
                    $key = 'PS';
                    break;
            }
            if (strpos($key, 'marketplace_service_item') !== false) {
                $key = str_replace('marketplace_service_item', 'MSI', $key);
            }
            if ($keyStr === '') {
                $tempKeyStr = $key;
            } elseif (is_int($key)) {
                $tempKeyStr = $keyStr;
            } else {
                $tempKeyStr = $keyStr . '_' . $key;
            }

            if (is_array($value)) {
                if (
                    $key === 'digital_codes' || 
                    $key === 'actions' ||
                    $key === 'additional_data'
                ) {
                    $fields[$tempKeyStr] = json_encode($value, JSON_UNESCAPED_UNICODE);
                } else {
                    $result = $this->recursionField($value, $tempKeyStr);
                    $fields = array_merge($fields, $result);
                }
            } elseif ($key === 'PS') {
                $posting_services = [
                    'marketplace_service_item_fulfillment' => null,
                    'marketplace_service_item_pickup' => null,
                    'marketplace_service_item_dropoff_pvz' => null,
                    'marketplace_service_item_dropoff_sc' => null,
                    'marketplace_service_item_dropoff_ff' => null,
                    'marketplace_service_item_direct_flow_trans' => null,
                    'marketplace_service_item_return_flow_trans' => null,
                    'marketplace_service_item_deliv_to_customer' => null,
                    'marketplace_service_item_return_not_deliv_to_customer' => null,
                    'marketplace_service_item_return_part_goods_customer' => null,
                    'marketplace_service_item_return_after_deliv_to_customer'  => null,
                ];
                $result = $this->recursionField($posting_services, $tempKeyStr);
                $fields = array_merge($fields, $result);
            } else {
                if (substr($key, -3) === '_at') {
                    $str = str_replace('T', ' ', $value);
                    $str = str_replace('Z', '', $str);
                    $fields[$tempKeyStr] = $str;
                } elseif (substr($key, 0, 3) === 'is_') {
                    $value = $value ? 1 : 0;
                    $fields[$tempKeyStr] = $value;
                } elseif (substr($key, -6) === '_price' && $value === '') {
                    $value = null;
                    $fields[$tempKeyStr] = $value;
                } else {
                    $fields[$tempKeyStr] = $value;
                }
            }
        }
        return $fields;
    }
}
