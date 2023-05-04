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
    public function testUserRetrievalAdmin1()
    {
        global $usersManager;
        $user = $usersManager->GetUserByID(1);
        $this->assertInstanceOf('User', $user);
        $this->assertEquals($user->last_name, "Kousal");
        $this->assertIsNumeric($user->affiliation_club_id);
    }
    public function testUserRetrievalAdmin2()
    {
        global $usersManager;
        $user = $usersManager->GetUserByID(2);
        $this->assertInstanceOf('User', $user);
        $this->assertEquals($user->last_name, "Klos");
        $this->assertIsString($user->email);
    }
    public function testRegisterUserRetrieveUser()
    {
        global $usersManager;
        $insertResponse = $usersManager->RegisterUser("Tomas", "Novak", "tomasnovak@seznam.cz", "12345", "0", "2", "1");
        $this->assertTrue($insertResponse);
    }
    public function testFindAllRefereeRanks()
    {
        global $usersManager;
        $ranks = $usersManager->FindAllRefereeRanks();
        $this->assertIsArray($ranks);
        $this->assertEquals(count($ranks), 4);
    }
    public function testUserWithIDPresent()
    {
        global $usersManager;
        $isUserPresent = $usersManager->IsUserWithIDPresentAlready(1);
        $this->assertTrue($isUserPresent);
    }
    public function testUserWithIDNotPresent()
    {
        global $usersManager;
        $notUserPresent = $usersManager->IsUserWithIDPresentAlready(-500);
        $this->assertFalse($notUserPresent);
    }
    public function testUserWithEmailPresent()
    {
        global $usersManager;
        $userPresent = $usersManager->IsEmailPresentAlready("stepanklos@gmail.com");
        $this->assertTrue($userPresent);
    }
    public function testUserWithEmailNotPresent()
    {
        global $usersManager;
        $userPresent = $usersManager->IsEmailPresentAlready("tonda.zapotocky@seznam.cz");
        $this->assertFalse($userPresent);
    }
}