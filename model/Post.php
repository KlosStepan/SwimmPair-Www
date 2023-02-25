<?php
/**
 * Post is a static snippet of news for homepage of web application.
 */
class Post
{
    public $id;
    public $timestamp;
    public $title;
    public $content;
    public $display_flag;
    public $author_user_id;
    public $signature_flag;
    /**
     * Ctor of Post object for web application
     * @param int $id
     * @param string $timestamp
     * @param string $title
     * @param string $content
     * @param bool $display_flag
     * @param int $author_user_id
     * @param int $signature_flag
     */
    public function __construct($id, $timestamp, $title, $content, $display_flag, $author_user_id, $signature_flag)
    {
        $this->id = $id;
        $this->timestamp = $timestamp;
        $this->title = $title;
        $this->content = $content;
        $this->display_flag = $display_flag;
        $this->author_user_id = $author_user_id;
        $this->signature_flag = $signature_flag;
    }
    /**
     * TODO - Constructor of Post with nothing inside. Not working yet.
     * @return void
     */
    public function NullCtor()
    {
        $this->id = null;
        $this->timestamp = null;
        $this->title = null;
        $this->content = null;
        $this->display_flag = null;
        $this->author_user_id = null;
        $this->signature_flag = null;
    }
    //7/7 Full Serialization
    //id, timestamp, title, content, display_flag, author_user_id, signature_flag
    //{"id":"1","timestamp":"2018-01-16 21:06:16","title":"Test title","content":"Hello! Blabla post","display_flag":"1","author_user_id":"21","signature_flag":"1"}
    /**
     * Serialize outputs 7/7 these members: id, timestamp, title, content, display_flag, author_user_id, signature_flag
     * @return string
     */
    public function Serialize()
    {
        $_serialized = "{\"id\":\"" . $this->id . "\",\"timestamp\":\"" . $this->timestamp . "\",\"title\":\"" . $this->title . "\",\"content\":\"" . $this->content . "\",\"display_flag\":\"" . $this->display_flag . "\",\"author_user_id\":\"" . $this->author_user_id . "\",\"signature_flag\":\"" . $this->signature_flag . "\"}";
        return $_serialized;
    }

}