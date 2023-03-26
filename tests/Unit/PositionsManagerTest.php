<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

require_once((dirname(dirname(__DIR__))) . '/start.php');
class PositionsManagerTest extends TestCase
{
    /**
     * A basic test example.
     */

    public function test_that_asserts_1()
    {
        $this->assertTrue(true);
        //$this->assertEquals([1, 2, 3], [1, 2, 4]);
        $this->assertEquals([1, 2, 3], [1, 2, 3]);
        return true;
    }
    public function test_DisplayedLiveStatsConfiguredPositions_returns_array()
    {
        global $positionsManager;
        $result = $positionsManager->DisplayedLiveStatsConfiguredPositions();
        $this->assertIsArray($result);
    }
    public function test_FindAllPositions_returns_correct_number_of_positions()
    {
        global $positionsManager;
        $result = $positionsManager->FindAllPositions();
        $this->assertCount(19, $result);
    }
    public function test_GetPositionNameById_returns_correct_name()
    {
        global $positionsManager;
        $result = $positionsManager->GetPositionNameById(1);
        $this->assertEquals('Vrchní rozhodčí', $result);
    }
    public function test_GetPositionNameById_returns_string()
    {
        global $positionsManager;
        $result = $positionsManager->GetPositionNameById(1);
        $this->assertIsString($result);
    }
/*
public function test_DeleteOldStatsPositions_deletes_old_positions() {
global $positionsManager;
$positionsManager->DeleteOldStatsPositions();
$result = $positionsManager->FindAllPositions();
$this->assertCount(0, $result);
}*/
}