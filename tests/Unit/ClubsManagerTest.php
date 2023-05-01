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
        global $clubsManager;
        $clubs = $clubsManager->FindAllClubs();
        $this->assertIsArray($clubs);
    }

    public function testFindAllClubsReturnsEmptyArrayWhenNoClubs()
    {
        global $clubsManager;
        $clubs = $clubsManager->FindAllClubs();
        $this->assertEmpty($clubs);
    }

    public function testGetClubAffiliationToRegionReturnsValidRegionId()
    {
        global $clubsManager;
        $clubId = 1;
        $regionId = $clubsManager->GetClubAffiliationToRegion($clubId);
        $this->assertNotNull($regionId);
    }

    public function testGetClubAffiliationToRegionReturnsNullWithInvalidClubId()
    {
        global $clubsManager;
        $clubId = -1;
        $regionId = $clubsManager->GetClubAffiliationToRegion($clubId);
        $this->assertNull($regionId);
    }

    public function testGetClubByIDReturnsValidClubObject()
    {
        global $clubsManager;
        $clubId = 1;
        $club = $clubsManager->GetClubByID($clubId);
        $this->assertInstanceOf('Club', $club);
    }

    public function testGetClubByIDReturnsNullWithInvalidClubId()
    {
        global $clubsManager;
        $clubId = -1;
        $club = $clubsManager->GetClubByID($clubId);
        $this->assertNull($club);
    }

    public function testInsertNewClubSuccessfullyInsertsClub()
    {
        global $clubsManager;
        $name = 'Test Club';
        $abbreviation = 'TC';
        $clubId = 100;
        $img = 'test_image.png';
        $regionId = 1;

        $success = $clubsManager->InsertNewClub($name, $abbreviation, $clubId, $img, $regionId);
        $this->assertTrue($success);
    }

    public function testInsertNewClubReturnsFalseWithInvalidInput()
    {
        global $clubsManager;
        $name = 'Invalid Club';
        $abbreviation = 'IC';
        $clubId = 1;
        $img = 'invalid_image.png';
        $regionId = -1;

        $success = $clubsManager->InsertNewClub($name, $abbreviation, $clubId, $img, $regionId);
        $this->assertFalse($success);
    }

    public function testUpdateClubSuccessfullyUpdatesClub()
    {
        global $clubsManager;
        $id = 1;
        $name = 'Updated Club';
        $abbreviation = 'UC';
        $code = '123';
        $img = 'updated_image.png';
        $regionId = 2;

        $success = $clubsManager->UpdateClub($id, $name, $abbreviation, $code, $img, $regionId);
        $this->assertTrue($success);
    }

    public function testUpdateClubReturnsFalseWithInvalidInput()
    {
        global $clubsManager;
        $id = -1;
        $name = 'Invalid Club';
        $abbreviation = 'IC';
        $code = '';
        $img = 'invalid_image.png';
        $regionId = -1;

        $success = $clubsManager->UpdateClub($id, $name, $abbreviation, $code, $img, $regionId);
        $this->assertFalse($success);
    }
}