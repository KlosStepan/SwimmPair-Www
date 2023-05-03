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
    public function test_UserRetrieval1()
    {
        global $usersManager;
        $user = $usersManager->GetUserByID(1);
        $this->assertInstanceOf('User', $user);
        $this->assertEquals($user->last_name, "Kousal");
        $this->assertIsNumeric($user->affiliation_club_id);
    }
    public function test_UserRetrieval2()
    {
        global $usersManager;
        $user = $usersManager->GetUserByID(2);
        $this->assertInstanceOf('User', $user);
        $this->assertEquals($user->last_name, "Klos");
        $this->assertIsString($user->email);
    }
    public function testRegisterUserRetrieveUser()
    {
        //RegisterUser xyz
        //GetUserByID user xyz
        $this->assertTrue(true);
        return true;
    }
    public function testFindAllRefereeRanks()
    {
        //check for array
        //check equal array (4 things)
        $this->assertTrue(true);
        return true;
    }
}