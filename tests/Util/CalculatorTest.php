<?php
// tests/Util/CalculatorTest.php
namespace App\Tests\Util;

use App\Util\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    /** @var Calculator $calculator  **/
    protected $calculator;

    public function setUp(): void {
        $this->calculator = new Calculator();
    }

    # region add

    public function testAdd()
    {
        $result = $this->calculator->add(30, 12);
        // assert that your calculator added the numbers correctly!
        $this->assertEquals(42, $result);
    }

    #endregion

    #region sub
    public function testSub() {
        $result = $this->calculator->sub(10, 2);
        $this->assertEquals(8, $result);
    }
    #endregion

    #region mul

    public function testMul() {
        $result = $this->calculator->mul(2, 5);
        $this->assertEquals(10, $result);
    }

    #endregion

    #region div

    public function testDiv() {
        $result = $this->calculator->div(10, 2);
        $this->assertEquals(5, $result);
    }

    public function testDivByZero() {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Division par zero");
        $this->calculator->div(2, 0);

    }

    #endregion


    #region avg

    public function testAvg() {
        $result = $this->calculator->avg([10, 2]);
        $this->assertEquals(6, $result);
    }

    public function testEmptyArray() {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Empty array");
        $this->calculator->avg([]);
    }

    #endregion


}