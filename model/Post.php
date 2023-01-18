<?php
/**
 * Summary of Post
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
     * Summary of __construct
     * @param mixed $id
     * @param mixed $timestamp
     * @param mixed $title
     * @param mixed $content
     * @param mixed $display_flag
     * @param mixed $author_user_id
     * @param mixed $signature_flag
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
     * Summary of NullCtor
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
     * Summary of Serialize
     * @return string
     */
    public function Serialize()
    {
        $_serialized = "{\"id\":\"" . $this->id . "\",\"timestamp\":\"" . $this->timestamp . "\",\"title\":\"" . $this->title . "\",\"content\":\"" . $this->content . "\",\"display_flag\":\"" . $this->display_flag . "\",\"author_user_id\":\"" . $this->author_user_id . "\",\"signature_flag\":\"" . $this->signature_flag . "\"}";
        return $_serialized;
    }

}