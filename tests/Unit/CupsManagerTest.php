<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

require((dirname(dirname(__DIR__))) . '/start.php');
//require __DIR__ . '/../start.php';
class CupsManagerTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_asserts_1()
    {
        //$directory = (dirname(__DIR__));
        $this->assertTrue(true);
        return true;
        //fwrite(STDERR, print_r($directory, TRUE));
    }
    public function test_FolderOfTests()
    {
        $directory = (dirname(dirname(__DIR__))) . '/start.php';
        $postId = 5;
        $this->assertEquals($directory, "/Applications/Ampps/www/start.php");
        //$post = $postsManager->GetPostByID($postID);
    }
    /*public function test_GetPairingHashForThisCup()
    {
    $cupID = 1;
    global $cupsManager;
    $hash1 = $cupsManager->GetPairingHashForThisCup($cupID);
    $hash2 = $cupsManager->GetPairingHashForThisCup($cupID);
    $this->assertIsString($hash1);
    $this->assertIsString($hash2);
    $this->assertNotEquals($hash1, $hash2);
    }*/
    /*public function test_GetEarliestCupYear()
    {
    global $cupsManager;
    $earliest = $cupsManager->GetEarliestCupYear();
    $this->assertIsInt($earliest);
    }*/
    public function test_GetMaximumCupYear()
    {
        global $cupsManager;
        $latest = $cupsManager->GetMaximumCupYear();
        $this->assertIsInt($latest);
    }
}