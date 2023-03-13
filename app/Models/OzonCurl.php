<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OzonCurl extends Model
{
    use HasFactory;

    // ozon
    private $clientId;
    private $apiKey;

    public function __construct()
    {
        $client = DB::connection('mysql')->table('client')->select('client_id', 'api_key')->first();
        $this->clientId = $client->client_id;
        $this->apiKey = $client->api_key;
    }

    public function postOzon($data, $method)
    {
        $url = 'https://api-seller.ozon.ru' . $method;
        $headers = array(
          'Content-Type: application/json',
          'Host: api-seller.ozon.ru',
            'Client-Id: ' . $this->clientId,
          'Api-Key: ' . $this->apiKey
        ) ;
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_SSL_VERIFYHOST => '0',
            CURLOPT_SSL_VERIFYPEER => '1',
            CURLOPT_USERAGENT      => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)',
            CURLOPT_VERBOSE        => 0,
        );
        curl_setopt_array($ch, $options);
        $out = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
        $code = (int) $code;

        $errors = [
            301 => 'Moved permanently.',
            400 => 'Wrong structure of the array of transmitted data, or invalid identifiers of custom fields.',
            401 => 'Not Authorized. There is no account information on the server. You need to make a request to another server on the transmitted IP.',
            403 => 'The account is blocked, for repeatedly exceeding the number of requests per second.',
            404 => 'Not found.',
            500 => 'Internal server error.',
            502 => 'Bad gateway.',
            503 => 'Service unavailable.'
        ];

        if ($code < 200 || $code > 204) die( "Error $code. " . (isset($errors[$code]) ? $errors[$code] : 'Undefined error'));

        $response = json_decode($out, true);

        return $response;
    }
}
