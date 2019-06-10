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
        dd($res);
        curl_close($curl);

    }

    public function search($query)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "https://places-dsn.algolia.net/1/places/query",
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => [
                "query" => $query
            ],
            CURLOPT_HEADER => [
                "X-Algolia-Application-Id: {$_ENV['ALGOLIA_APP_ID']}",
                "X-Algolia-API-Key: {$_ENV['ALGOLIA_API_KEY']}"
            ]
        ]);
        $res = curl_exec($curl);
        dd($res);
        curl_close($curl);
    }

}