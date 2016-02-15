<?php

include_once "response.php";
include_once "models/post.php";

// api=get_posts&page&size&author_id&tag&cat&keyword
// api=get_post&post_id
// api=get_comments&post_id
// api=get_page&page_id
// 解析参数

class simple_api_controller {

    /**
     * available parameters for get posts
     * @link <https://developer.wordpress.org/reference/classes/wp_query/>
     */
    public $query_parameters = array(
        'sa_author',
        'sa_author_name',
        'sa_author__id',
        'sa_author__not_in',
        'sa_cat',
        'sa_category_name',
        'sa_category__and',
        'sa_category__in',
        'sa_category__not_in',
        'sa_tag',
        'sa_tag_id',
        'sa_tag__and',
        'sa_tag__in',
        'sa_tag__not_in',
        'sa_tag_slug__and',
        'sa_tag_slug__in',
        'sa_s',
        'sa_p',
        'sa_name',
        'sa_page_id',
        'sa_pagename',
        'sa_post_parent',
        'sa_post_parent__in',
        'sa_post_parent__not_in',
        'sa_post__in',
        'sa_post__not_in',
        'sa_post_name__in',
        'sa_post_type',
        'sa_post_status',
        'sa_nopaging',
        'sa_posts_per_page',
        'sa_posts_per_archive_page',
        'sa_offset',
        'sa_paged',
        'sa_page',
        'sa_ignore_sticky_posts',
        'sa_order',
        'sa_orderby',
        'sa_year',
        'sa_monthnum',
        'sa_w',
        'sa_day',
        'sa_hour',
        'sa_minute',
        'sa_second',
        'sa_m',
        'sa_meta_key',
        'sa_meta_value',
        'sa_meta_value_num',
        'sa_meta_compare'

    );

    function get_posts( $args ){
        $wpArgs = array();

        foreach( $args as $key => $value ){
            if( array_search( $key, $this->query_parameters ) !== false ){
                // 检查是否为数组
                if( strpos ( $value, ',' ) !== false ){
                    $wpArgs[ $key ] = explode( ',', $value );
                }
                else {
                    $wpArgs[ $key ] = $value;
                }
            }
        }

        $wpArgs[ 'sa_posts_per_page' ] = isset( $wpArgs[ 'sa_posts_per_page' ] ) ? $wpArgs[ 'sa_posts_per_page' ] : 20;

        $query = new WP_Query( $wpArgs );
        $resultPosts = array();

        if( $query->have_posts() ){
            while( $query->have_posts() ){
                $query->the_post();
                $wpPost = $query->post;

                if( $args[ 'sa_get_brief_posts' ] ){
                    $resultPosts[] = new simple_api_post_basic( $wpPost );
                }
                else {
                    $resultPosts[] = new simple_api_post( $wpPost );
                }
            }
        }

        return array(
            'posts' => $resultPosts,
            'pagination' => array(
                'totalCount' => $query->found_posts,
                'totalPage' => $query->max_num_pages,
                'pageSize' => $wpArgs[ 'posts_per_page' ]
            )
        );
    }

    function ctrl_get_posts(){

        $result = $this->get_posts( $_REQUEST );

        return new simple_api_response( $result[ 'posts' ], 200, array(
            'pagination' => array(
                'totalCount' => $result[ 'totalCount' ],
                'totalPage' => $result[ 'totalPage' ],
                'pageSize' => $result[ 'pageSize' ]
            )
        ));
    }

    function ctrl_get_post(){
        $result = $this->get_posts( array( 'sa_p' => $_REQUEST[ 'sa_post_id' ] ) );

        if( count( $result[ 'posts' ] ) > 0 ){
            return new simple_api_response( $result[ 'posts' ][0], 200 );
        }
        else {
            return new simple_api_response( 'post not found', 404 );
        }
    }

    function ctrl_get_page(){
        $result = $this->get_posts( array( 'sa_page_id' => $_REQUEST[ 'sa_page_id' ] ) );

        if( count( $result[ 'posts' ] ) > 0 ){
            return new simple_api_response( $result[ 'posts' ][0], 200 );
        }
        else {
            return new simple_api_response( 'post not found', 404 );
        }
    }

    function ctrl_get_comments(){

        $result = get_comments( array( 'sa_post_id' => $_REQUEST[ 'sa_post_id' ] ) );

        return new simple_api_response( $result, 200 );
    }

    function ctrl_insert_comment(){
        $comment = array(
            'comment_approved' => is_user_member_of_blog() ? 1 : 0,
            'comment_author' => $_REQUEST[ 'comment_author' ],
            'comment_author_email' => $_REQUEST[ 'comment_author_email' ],
            'comment_author_url' => $_REQUEST[ 'comment_author_url' ],
            'comment_author_ip' => $_SERVER['REMOTE_ADDR'],
            'comment_content' => $_REQUEST[ 'comment_content' ],
            'comment_post_ID' => $_REQUEST[ 'comment_post_ID' ]
        );

        $commentId = wp_insert_comment( $comment );
        return new simple_api_response( get_comment( $commentId ), 200 );
    }
}