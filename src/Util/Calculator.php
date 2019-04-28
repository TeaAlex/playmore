<?php

namespace App\Util;

class Calculator {

    public function add(int $a, int $b): int {
        return $a + $b;
    }

    public function sub(int $a, int $b): int {
        return $a - $b;
    }

    public function mul(int $a, int $b): int {
        return $a * $b;
    }

    public function div(int $a, int $b): int {
        if($b === 0){
            throw new \Exception("Division par zero");
        }
        return $a / $b;
    }

    public function avg(array $numbers): int {
        if(count($numbers) === 0){
            throw new \Exception("Empty array");
        }
        $sum = 0;
        foreach ($numbers as $number) {
            $sum += $number;
        }
        return $sum / count($numbers);
    }

}