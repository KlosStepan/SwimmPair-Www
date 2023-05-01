<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

require_once((dirname(dirname(__DIR__))) . '/start.php');
class ClubsManagerTest extends TestCase
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
    public function testFindAllClubsReturnsArray()
    {
        $clubs = $this->cupsManager->FindAllClubs();
        $this->assertIsArray($clubs);
    }

    public function testFindAllClubsReturnsEmptyArrayWhenNoClubs()
    {
        $clubs = $this->cupsManager->FindAllClubs();
        $this->assertEmpty($clubs);
    }

    public function testGetClubAffiliationToRegionReturnsValidRegionId()
    {
        $clubId = 1;
        $regionId = $this->cupsManager->GetClubAffiliationToRegion($clubId);
        $this->assertNotNull($regionId);
    }

    public function testGetClubAffiliationToRegionReturnsNullWithInvalidClubId()
    {
        $clubId = -1;
        $regionId = $this->cupsManager->GetClubAffiliationToRegion($clubId);
        $this->assertNull($regionId);
    }

    public function testGetClubByIDReturnsValidClubObject()
    {
        $clubId = 1;
        $club = $this->cupsManager->GetClubByID($clubId);
        $this->assertInstanceOf(Club::class, $club);
    }

    public function testGetClubByIDReturnsNullWithInvalidClubId()
    {
        $clubId = -1;
        $club = $this->cupsManager->GetClubByID($clubId);
        $this->assertNull($club);
    }

    public function testInsertNewClubSuccessfullyInsertsClub()
    {
        $name = 'Test Club';
        $abbreviation = 'TC';
        $clubId = 100;
        $img = 'test_image.png';
        $regionId = 1;

        $success = $this->cupsManager->InsertNewClub($name, $abbreviation, $clubId, $img, $regionId);
        $this->assertTrue($success);
    }

    public function testInsertNewClubReturnsFalseWithInvalidInput()
    {
        $name = 'Invalid Club';
        $abbreviation = 'IC';
        $clubId = 1;
        $img = 'invalid_image.png';
        $regionId = -1;

        $success = $this->cupsManager->InsertNewClub($name, $abbreviation, $clubId, $img, $regionId);
        $this->assertFalse($success);
    }

    public function testUpdateClubSuccessfullyUpdatesClub()
    {
        $id = 1;
        $name = 'Updated Club';
        $abbreviation = 'UC';
        $code = '123';
        $img = 'updated_image.png';
        $regionId = 2;

        $success = $this->cupsManager->UpdateClub($id, $name, $abbreviation, $code, $img, $regionId);
        $this->assertTrue($success);
    }

    public function testUpdateClubReturnsFalseWithInvalidInput()
    {
        $id = -1;
        $name = 'Invalid Club';
        $abbreviation = 'IC';
        $code = '';
        $img = 'invalid_image.png';
        $regionId = -1;

        $success = $this->cupsManager->UpdateClub($id, $name, $abbreviation, $code, $img, $regionId);
        $this->assertFalse($success);
    }
}