<?php


namespace App\Services;


class GeoCodingService
{

    public function reverseGeoCoding($lat, $long)
    {
        $latlong = "{$lat},{$long}";
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "https://places-dsn.algolia.net/1/places/reverse?aroundLatLng={$latlong}&hitsPerPage=1&language=fr",
            CURLOPT_HTTPHEADER => [
                "X-Algolia-Application-Id: {$_ENV['ALGOLIA_APP_ID']}",
                "X-Algolia-API-Key: {$_ENV['ALGOLIA_API_KEY']}",
            ]
        ]);
        $res = json_decode(curl_exec($curl), true);
        curl_close($curl);
        $city = $res["hits"][0]["city"][0];
        $postcode = $res["hits"][0]["postcode"][0];
        $street = $res["hits"][0]["locale_names"][0];
        $administrative = $res["hits"][0]["administrative"][0];
        $address = "{$street}, {$city}, {$administrative}, {$postcode}";

        return [$city, $postcode, $address];
    }

    public function search($query)
    {

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://places-dsn.algolia.net/1/places/query",
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POSTFIELDS => json_encode([
                "query" => $query,
                "language" => "fr",
                "countries" => ["fr"]
            ]),
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => ["Content-Type: application/x-www-form-urlencoded"]
        ]);
        $res = json_decode(curl_exec($curl), true);
        curl_close ($curl);
        return $res;
    }

}