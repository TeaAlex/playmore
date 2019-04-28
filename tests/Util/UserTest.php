<?php

// tests/Util/CalculatorTest.php
namespace App\Tests\Util;

use App\Util\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    public function testIsValid(): void
    {
        $user = new User("Aissaoui","Redha","redha955@hotmail.fr",16);
        $this->assertTrue(
            $user->isValid()
        );
    }
}