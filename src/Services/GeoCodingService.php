<?php


namespace App\Services;


class GeoCodingService
{

    public function reverseGeoCoding($lat, $long)
    {
        $latlong = "{$lat}, {$long}";
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "https://places-dsn.algolia.net/1/places/reverse?aroundLatLng={$latlong}&hitsPerPage=1&language=fr",
            CURLOPT_HEADER => [
                "X-Algolia-Application-Id: {$_ENV['ALGOLIA_APP_ID']}",
                "X-Algolia-API-Key: {$_ENV['ALGOLIA_API_KEY']}"
            ]
        ]);
        $res = json_decode(curl_exec($curl), true);
        curl_close($curl);
        return $res;

    }

    public function search($query)
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://places-dsn.algolia.net/1/places/query');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            "query" => $query,
            "language" => "fr",
            "countries" => ["fr"]
        ]));
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $res = json_decode(curl_exec($ch), true);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);
        return $res;
    }

}