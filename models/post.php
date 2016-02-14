<?php

class simple_api_post_basic {

    public $id;
    public $type;
    public $slug;
    public $url;
    public $status;
    public $title;
    public $title_plain;
    public $excerpt;
    public $date;
    public $modified;
    public $categories;
    public $tags;
    public $author;
    public $comment_count;
    public $comment_status;
    public $custom_fields;

    function __construct( $wp_post ) {
        $this->id = (int) $wp_post->ID;
        $this->type = $wp_post->post_type;
        $this->slug = $wp_post->post_name;
        $this->url = get_permalink($this->id);
        $this->status = $wp_post->post_status;
        $this->title = get_the_title($this->id);
        $this->title_plain = strip_tags(@$this->title);
        $this->excerpt = apply_filters( 'the_excerpt', get_the_excerpt($wp_post->ID));
        $this->date = get_the_time($wp_post->ID);
        $this->modified = $wp_post->post_modified;
        $this->categories = get_the_category( $this->id );
        $tags = get_the_tags( $this->id );
        $this->tags = $tags ? $tags : array();
        $this->author = get_the_author($wp_post->ID);
        $this->comment_count = (int) $wp_post->comment_count;
        $this->comment_status = $wp_post->comment_status;
        $this->thumbnail = get_the_post_thumbnail_url($wp_post->ID);
        $this->custom_fields = get_post_custom($this->id);
    }
}

class simple_api_post extends simple_api_post_basic{

    public $content;
    public $attachments;

    function __construct( $wp_post ) {

        parent::__construct( $wp_post );
        $this->content = get_the_content($wp_post->ID);
        $this->attachments = $this->getAttachments();
    }

    function getAttachments(){
        return $wp_attachments = get_children(array(
            'post_type' => 'attachment',
            'post_parent' => $this->id,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'suppress_filters' => false
        ));
    }

}

