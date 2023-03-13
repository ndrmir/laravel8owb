<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WBSalerReportByRealisation extends Model
{
    use HasFactory;

    public $timestamps = false;
    private $curl;
    private $url;
    private $tablename;

    protected $fillable = [
        'id',
        'realizationreport_id',
        'date_from',
        'date_to',
        'create_dt',
        'suppliercontract_code',
        'rrd_id',
        'gi_id',
        'subject_name',
        'nm_id',
        'brand_name',
        'sa_name',
        'ts_name',
        'barcode',
        'doc_type_name',
        'quantity',
        'retail_price',
        'retail_amount',
        'sale_percent',
        'commission_percent',
        'office_name',
        'supplier_oper_name',
        'order_dt',
        'sale_dt',
        'rr_dt',
        'shk_id',
        'retail_price_withdisc_rub',
        'delivery_amount',
        'return_amount',
        'delivery_rub',
        'gi_box_type_name',
        'product_discount_for_report',
        'supplier_promo',
        'rid',
        'ppvz_spp_prc',
        'ppvz_kvw_prc_base',
        'ppvz_kvw_prc',
        'ppvz_sales_commission',
        'ppvz_for_pay',
        'ppvz_reward',
        'acquiring_fee',
        'acquiring_bank',
        'ppvz_vw',
        'ppvz_vw_nds',
        'ppvz_office_id',
        'ppvz_office_name',
        'ppvz_supplier_id',
        'ppvz_supplier_name',
        'ppvz_inn',
        'declaration_number',
        'bonus_type_name',
        'sticker_id',
        'site_country',
        'penalty',
        'additional_payment',
        'srid',
    ];
    protected $connection = 'mysql_wb';

    public function __construct(array $attributes = [])
    {
        $this->curl = app(WBCurl::class);
        // Отчет о продажах по реализации
        $this->url = 'https://statistics-api.wildberries.ru/api/v1/supplier/reportDetailByPeriod';

        $this->tablename = 'w_b_saler_report_by_realisations';

        parent::__construct($attributes);
    }

    public function saveData()
    {
        $dateFrom = strtotime('2022-01-01');
        $data['dateFrom'] = date(DATE_RFC3339, $dateFrom);
        $data['dateTo'] = date(DATE_RFC3339, $dateFrom + 30 * 24 * 60 * 60);
        $data['rrdid'] = 0;

        $count = 1;
        $countEntriesFull = 0;
        // Loop through data and insert records into table
        while ($count) {
            $tempUrl = $this->url . '?dateFrom=' . $data['dateFrom'] . '&' .
            'dateTo=' . $data['dateTo'] . '&' . 'rrdid=' . $data['rrdid'];
            echo $tempUrl;
            $response = $this->curl->getWB($tempUrl, 'statistic');

            // В случае превышения количества запросов, делаю sleep(2) и повторный запрос 20 попыток
            if (isset($response['errors'][0]) && ($response['errors'][0] === '(api-new) too many requests')) {
                $i = 0;
                while ($i < 20) {
                    sleep(2);
                    $msg = 'Repeating request ' . $tempUrl . ' count ' . $i . PHP_EOL;
                    echo $msg;
                    \Log::channel('schedule')->info($msg);
                    $response = $this->curl->getWB($tempUrl, 'statistic');
                    if (!isset($response['errors'][0])) {
                        break;
                    }
                    $i++;
                }
            }

            if ($response === null) {
                $data['dateFrom'] = $data['dateTo'];
                $dateTo = strtotime($data['dateTo']) + 30 * 24 * 60 * 60;
                $data['dateTo'] = date(DATE_RFC3339, $dateTo);
                sleep(6);
                continue;
            }
            $countEntries = count($response);
            $countEntriesFull += $countEntries;
            if (is_array($response)) {
                // print_r($response);
                $data['dateFrom'] = $data['dateTo'];
                $count = count($response);
                $data['rrdid'] = end($response)['rrd_id'];
                $dateTo = end($response)['date_to'];
                $dateTo = str_replace('Z', '', $dateTo);
                $dateTo = strtotime($dateTo) + 30 * 24 * 60 * 60;
                if ($dateTo >= time()) {
                    $dateTo = time();
                    $count = false;
                }
                $data['dateTo'] = date(DATE_RFC3339, $dateTo);

                // В ответе четко не регламентировано наличие определенных полей
                // Создаю массив со всеми ключами
                // $fullArray = [];

                // foreach ($response as $item) {
                //     foreach ($item as $key => $value) {
                //         if (!isset($fullArray[$key])) {
                //             $fullArray[$key] = 1;
                //         }
                //     }
                // }

                // Loop through data and insert records into table

                
                // $fullKeys = array_keys($fullArray);
                $fullKeys = $this->fillable;
                unset($fullKeys[0]);
                // print_r($fullKeys);
                $splitResponse = array_chunk($response, 50);
                foreach ($splitResponse as $item) {
                    $insertData = [];
                    $keys = [];
                    foreach ($item as $key => $row) {
                        // Подготавливаем даты к инсерту
                        foreach ($row as $k => $v) {
                            if (!is_array($v)) {
                                if (substr($k, -3) === '_dt') {
                                    // $v = str_replace('T', ' ', $v);
                                    if (!$v) {
                                        $insertData[$key][$k] = null;
                                    } else {
                                        $v = str_replace('Z', '', $v);
                                        $insertData[$key][$k] = $v;
                                    }
                                } elseif ($k === 'date_from' || $k === 'date_to') {
                                    $v = str_replace('Z', '', $v);
                                    $insertData[$key][$k] = $v;
                                } else {
                                    $insertData[$key][$k] = $v;
                                }
                            }
                        }
                        // Дополняем отстутствующие поля
                        $keys = array_keys($insertData[$key]);
                        $diff = array_diff($fullKeys, $keys);

                        foreach ($diff as $value) {
                            $insertData[$key][$value] = null;
                        }
                    }
                    $countEntriesTemp = count($insertData);
                    try {
                        // print_r($insertData);
                        $salerReportByRealisation = $this::upsert(
                            $insertData,
                            [
                            'rrd_id',
                            ],
                            $keys
                        );

                        // $salerReportByRealisation = $this::updateOrCreate([
                        //     'rrd_id' => $insertData['rrd_id'],
                        // ], $insertData);
                        // $id = $salerReportByRealisation->id;
                    } catch (\Illuminate\Database\QueryException $exception) {
                        // You can check get the details of the error using `errorInfo`:
                        $errorInfo = $exception->errorInfo;
                        dd($errorInfo);
                        // Return the response to the client..
                    }
                    $msg = 'Running: insert or update in ' . $this->tablename . ' ' . $countEntriesTemp . ' entries' . PHP_EOL;
                    echo $msg;
                }
            }
        }
        $msg = 'Insert or update in ' . $this->tablename . ' ' . $countEntriesFull . ' entries' . PHP_EOL;
        echo $msg;
        \Log::channel('schedule')->info($msg);
        return 'Success data insert' . PHP_EOL;
    }
}
