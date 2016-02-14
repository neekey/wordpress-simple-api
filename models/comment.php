<?php

class simple_api_comment {
    public $id;
    public $name;
    public $url;
    public $date;
    public $content;
    public $parent;

    function __construct( $wp_comment ) {
        $content = apply_filters('comment_text', $wp_comment->comment_content);
        $this->id = (int) $wp_comment->comment_ID;
        $this->name = $wp_comment->comment_author;
        $this->url = $wp_comment->comment_author_url;
        $this->date = $wp_comment->comment_date;
        $this->content = $content;
        $this->parent = (int) $wp_comment->comment_parent;
        $this->author = get_comment_author( $this->id );
    }
}