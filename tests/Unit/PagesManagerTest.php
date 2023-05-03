<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

require_once((dirname(dirname(__DIR__))) . '/start.php');
class PagesManagerTest extends TestCase
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
    public function test_GetPageByID_InvalidID_ThrowsException()
    {
        $this->expectException(\Exception::class);
        global $pagesManager;
        $page = $pagesManager->GetPageByID(-1);
    }
    public function test_GetPageByID_NonExistingID_ReturnsNulle()
    {
        global $pagesManager;
        $page = $pagesManager->GetPageByID(69420);
        $this->assertNull($page);
    }
    public function testGetPageById()
    {
        global $pagesManager;
        $id = 1;
        $page = $pagesManager->GetPageByID($id);
        $this->assertNotNull($page);
        $this->assertEquals('Kontakty', $page->title);
    }
    public function testUpdatePageSuccessfully()
    {
        global $pagesManager;
        $id = 1;
        $newTitle = "New Title";
        $newContent = "New Content";

        $updated = $pagesManager->UpdatePage($id, $newTitle, $newContent);
        $this->assertTrue($updated);

        $page = $pagesManager->GetPageByID($id);
        $this->assertEquals($page->title, "New Title");
        $this->assertEquals($page->content, "New Content");
    }
}