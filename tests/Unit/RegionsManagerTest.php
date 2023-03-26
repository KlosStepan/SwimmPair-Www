<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

require_once((dirname(dirname(__DIR__))) . '/start.php');
class RegionsManagerTest extends TestCase
{
    /**
     * A basic test example.
     */

    public function test_that_asserts_1()
    {
        $this->assertTrue(true);
        return true;
    }
    public function test_FindAllRegions_ReturnsArrayOfRegions()
    {
        global $regionsManager;
        $regions = $regionsManager->FindAllRegions();
        $this->assertIsArray($regions);
        $this->assertGreaterThan(0, count($regions));
    }
    public function test_GetRegionNameOfClub_ReturnsRegionNameOfGivenClub()
    {
        global $regionsManager;
        $regionName = $regionsManager->GetRegionNameOfClub(0);
        $this->assertEquals('Český svaz plaveckých sportů', $regionName);
    }
    public function test_GetRegionByID_ReturnsCorrectRegionForGivenID()
    {
        global $regionsManager;
        $region = $regionsManager->GetRegionByID(0);
        $this->assertInstanceOf('Region', $region);
        $this->assertEquals('Český svaz plaveckých sportů', $region->name);
        $this->assertEquals('CSPS', $region->abbreviation);
    }
    public function test_UpdateRegionFailure()
    {
        global $regionsManager;
        $result = $regionsManager->UpdateRegion(9999, 'Královecký kraj', 'KRK');
        $this->assertFalse($result);
    }
}