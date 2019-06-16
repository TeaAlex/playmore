<?php


namespace App\Services;


use App\Entity\User;

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
        return $res;
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

    public function setUserGeo($res, User &$user)
    {
        if(empty($res["hits"])){
            return;
        }
        $res = $res["hits"][0];
        $street = "";
        $postcode = $res["postcode"][0];
        if($res["is_city"] == true){
            $city = $res["locale_names"][0];
        } else {
            $city = $res["city"][0];
            $street = $res["locale_names"][0];
        }
        $lon = $res["_geoloc"]["lng"];
        $lat = $res["_geoloc"]["lat"];
        $administrative = $res["administrative"][0];
        if(isset($res["query"])){
            $address = $user->setAddress($res["query"]);
        } else {
            $address = "{$street} {$city}, {$administrative}, {$postcode}";
        }
        $user->setAddress($address);
        $user->setLon($lon);
        $user->setLat($lat);
        $user->setPostalCode($postcode);
        $user->setCity($city);
    }

}