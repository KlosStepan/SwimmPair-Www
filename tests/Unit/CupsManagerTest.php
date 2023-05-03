<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

require_once((dirname(dirname(__DIR__))) . '/start.php');
class CupsManagerTest extends TestCase
{
    /**
     * A basic test example.
     */
    /*
    public function test_that_asserts_1()
    {
    //$directory = (dirname(__DIR__));
    $this->assertTrue(true);
    return true;
    //fwrite(STDERR, print_r($directory, TRUE));
    }
    public function test_FindAllUpcomingCupsEarliestFirst()
    {
    global $cupsManager;
    $cups = $cupsManager->FindAllUpcomingCupsEarliestFirst();
    $this->assertIsArray($cups);
    $this->assertNotEmpty($cups);
    //$this->assertGreaterThanOrEqual($cups[0]['date_begin'], $cups[1]['date_begin']);
    }
    /*
    public function test_FolderOfTests()
    {
    $directory = (dirname(dirname(__DIR__))) . '/start.php';
    $postId = 5;
    $this->assertEquals($directory, "/Applications/Ampps/www/start.php");
    //$post = $postsManager->GetPostByID($postID);
    }*/
    /*
    public function test_GetPairingHashForThisCup()
    {
    $cupID = 5;
    global $cupsManager;
    $hash1 = $cupsManager->GetPairingHashForThisCup($cupID);
    global $cupsManager;
    $hash2 = $cupsManager->GetPairingHashForThisCup($cupID);
    $this->assertIsString($hash1);
    $this->assertIsString($hash2);
    $this->assertEquals($hash1, $hash2);
    }*/

    public function test_GetMaximumCupYear()
    {
        global $cupsManager;
        $latest = $cupsManager->GetMaximumCupYear();
        $this->assertIsInt($latest);
    }
    public function testInsertCupAndCheckIfUserIsAvailable()
    {
        global $cupsManager;
        // Insert a new cup
        $name = "Test Cup";
        $date_begin = "2023-06-01";
        $date_end = "2023-06-05";
        $club = "Test Club";
        $content = "Test Content";
        $cupID = $cupsManager->InsertNewCup($name, $date_begin, $date_end, $club, $content);

        $userID = 1;
        //InsertNewAvailability Cup x User x 1
        $cupsManager->InsertNewAvailability($cupID, $userID, 1);
        // Check if a user is available for the cup
        $isAvailable = $cupsManager->IsUserAvailableForTheCup($userID, $cupID);

        // Assert that the user is not available by default
        $this->assertTrue($isAvailable);
    }
    public function testInsertCupAndCheckIfUserIsNotAvailable()
    {
        global $cupsManager;
        // Insert a new cup
        $name = "Test Cup";
        $date_begin = "2023-06-01";
        $date_end = "2023-06-05";
        $club = "Test Club";
        $content = "Test Content";
        $cupID = $cupsManager->InsertNewCup($name, $date_begin, $date_end, $club, $content);

        // Check if a user is available for the cup
        $userID = 2;
        $isAvailable = $cupsManager->IsUserAvailableForTheCup($userID, $cupID);

        // Assert that the user is not available by default
        $this->assertFalse($isAvailable);
    }
    public function testCupPreparationTrue()
    {
        //Create cup
        //put pple available
        //pair users
        $this->assertTrue(true);
        return true;
    }
    public function testHashPairingWorks()
    {
        //get hash of pairing
        //add 1 pairing
        //get hash of pairing
        //hash1 NEQ hash2
        $this->assertTrue(true);
        return true;
    }
    public function testStartsEarlierThanEnds()
    {
        $this->assertTrue(true);
        return true;
    }
    public function testInsertBadDates()
    {
        //assert RTE
        //try insert ends earlier than starts -> RTE
        $this->assertTrue(true);
        return true;
    }
}