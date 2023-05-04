<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

require_once((dirname(dirname(__DIR__))) . '/start.php');
class PostsManagerTest extends TestCase
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
    public function testInsertPost()
    {
        global $postsManager;
        $insertPostSucc = $postsManager->InsertNewPost("Novy post", "Novy post je fakt super", 1, 1, 1);
        $this->assertTrue($insertPostSucc);
    }
    public function testGetPostByID()
    {
        global $postsManager;
        $post = $postsManager->GetPostByID(1);
        $this->assertInstanceOf('Post', $post);
    }
    public function testGetFollowingPost()
    {
        global $postsManager;
        $post = $postsManager->GetFollowingPost(2);
        $this->assertNotNull($post);
        $this->assertInstanceOf('Post', $post);
        $this->assertEquals($post->id, 1);
    }
    public function testGetFollowingPostNonsenseNull()
    {
        global $postsManager;
        $post = $postsManager->GetFollowingPost(1);
        $this->assertNull($post);
    }
    public function testFindLastNPostsN2()
    {
        global $postsManager;
        $posts = $postsManager->FindLastNPosts(2);
        $this->assertIsArray($posts);
        $this->assertEquals(count($posts), 2);
    }
}