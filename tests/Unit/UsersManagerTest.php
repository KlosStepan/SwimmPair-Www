<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

require_once((dirname(dirname(__DIR__))) . '/start.php');
//require __DIR__ . '/../start.php';
class UsersManagerTest extends TestCase
{
    /**
     * A basic test example.
     */

    public function test_that_asserts_1()
    {
        $this->assertTrue(true);
        //$this->assertEquals([1, 2, 3], [1, 2, 4]);
//$this->assertEquals([1, 2, 3], [1, 2, 3]);
        return true;
    }
    public function test_2()
    {
        $this->assertTrue(true);
        return true;
    }
}