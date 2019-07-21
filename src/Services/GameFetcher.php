<?php
namespace App\Services;

use App\Entity\Game;

class GameFetcher
{

    public function fetchGames(array &$gamesList)
    {
//        dd($_ENV["GAME_API_KEY"]);
        foreach ($gamesList as &$game) {
            $curl = curl_init('https://api-v3.igdb.com/games');
            curl_setopt_array($curl, [
               CURLOPT_RETURNTRANSFER => 1,
               CURLOPT_POST => 1,
               CURLOPT_POSTFIELDS => 'fields cover.url, cover.width, platforms.name, name, slug; search "'.$game.'"; limit 1;',
               CURLOPT_HTTPHEADER => ['user-key: f5bca30ac00cc136132203b3cfcd25f3']
            ]);
           $res = json_decode(curl_exec($curl), true);
           if(!isset($res[0])){
              return;
           }
           $imageUrl = str_replace("t_thumb", "t_cover_big", $res[0]["cover"]["url"]);
           $res[0]["cover"]["url"] = $imageUrl;
           dd($res);
           $this->saveGame($res[0]);
        }
        curl_close($curl);
    }

    public function saveGame(&$game)
    {
        $g = new Game();
        $g->setName($game["name"]);
        foreach ($game["platforms"] as $platform) {

        }
    }

}