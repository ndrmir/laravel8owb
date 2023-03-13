<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WBCurl extends Model
{
    use HasFactory;

    // wildberries
    private $tokenSupplier;
    private $tokenStatistic;

    public function __construct()
    {
        $client = DB::connection('mysql_wb')->table('client')->select('token_supplier', 'token_statistic')->first();
        $this->tokenSupplier = $client->token_supplier;
        $this->tokenStatistic = $client->token_statistic;
    }

    public function getWB($url, $type)
    {
        if ($type === 'statistic') {
            $token = $this->tokenStatistic;
        } elseif ($type === 'supplier') {
            $token = $this->tokenSupplier;
        }

        $headers = [
            'Content-Type: application/json',
            'Authorization:' . $token
        ];

        

        $cookie_file = 'cookieWB.txt';
        $curl = curl_init();
        $options = array(
          CURLOPT_URL => $url,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_HTTPHEADER => $headers,
          CURLOPT_FOLLOWLOCATION => false,
          CURLOPT_SSL_VERIFYHOST => '0',
          CURLOPT_SSL_VERIFYPEER => '1',
          CURLOPT_USERAGENT      => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)',
          CURLOPT_VERBOSE        => 0,
        );
        curl_setopt_array($curl, $options);
        $out = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $code = (int) $code;

        $errors = [
            301 => 'Moved permanently.',
            400 => 'Wrong structure of the array of transmitted data, or invalid identifiers of custom fields.',
            401 => 'Not Authorized. There is no account information on the server. You need to make a request to another server on the transmitted IP.',
            403 => 'The account is blocked, for repeatedly exceeding the number of requests per second.',
            404 => 'Not found.',
            500 => 'Internal server error.',
            502 => 'Bad gateway.',
            503 => 'Service unavailable.',
            429 => '(api-new) too many requests'
        ];

        if (($code < 200 || $code > 204) && $code !== 429) die( "Error $code. " . (isset($errors[$code]) ? $errors[$code] : 'Undefined error'));

        $response = json_decode($out, true);

        return $response;
    }
}
