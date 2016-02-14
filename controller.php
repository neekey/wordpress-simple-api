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
        'author',
        'author_name',
        'author__id',
        'author__not_in',
        'cat',
        'category_name',
        'category__and',
        'category__in',
        'category__not_in',
        'tag',
        'tag_id',
        'tag__and',
        'tag__in',
        'tag__not_in',
        'tag_slug__and',
        'tag_slug__in',
        's',
        'p',
        'name',
        'page_id',
        'pagename',
        'post_parent',
        'post_parent__in',
        'post_parent__not_in',
        'post__in',
        'post__not_in',
        'post_name__in',
        'post_type',
        'post_status',
        'nopaging',
        'posts_per_page',
        'posts_per_archive_page',
        'offset',
        'paged',
        'page',
        'ignore_sticky_posts',
        'order',
        'orderby',
        'year',
        'monthnum',
        'w',
        'day',
        'hour',
        'minute',
        'second',
        'm',
        'meta_key',
        'meta_value',
        'meta_value_num',
        'meta_compare'

    );

    // todo 目前虽然这样直接把参数放进来的方式很简单,但是本身某些参数名和wordpress本身的参数规则有冲突,比如 page_id,
    // 需要看看是否有办法覆盖掉wordpress的这些规则
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

        $wpArgs[ 'posts_per_page' ] = isset( $wpArgs[ 'posts_per_page' ] ) ? $wpArgs[ 'posts_per_page' ] : 20;

        $query = new WP_Query( $wpArgs );
        $resultPosts = array();

        if( $query->have_posts() ){
            while( $query->have_posts() ){
                $query->the_post();
                $wpPost = $query->post;

                if( $_REQUEST[ 'get_brief_posts' ] ){
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
        $result = $this->get_posts( array( 'p' => $_REQUEST[ 'post_id' ] ) );

        if( count( $result[ 'posts' ] ) > 0 ){
            return new simple_api_response( $result[ 'posts' ][0], 200 );
        }
        else {
            return new simple_api_response( 'post not found', 404 );
        }
    }

    function ctrl_get_page(){
        $result = $this->get_posts( array( 'page_id' => $_REQUEST[ 'p_id' ] ) );

        if( count( $result[ 'posts' ] ) > 0 ){
            return new simple_api_response( $result[ 'posts' ][0], 200 );
        }
        else {
            return new simple_api_response( 'post not found', 404 );
        }
    }

    function ctrl_get_comments(){

        $result = get_comments( array( 'post_id' => $_REQUEST[ 'post_id' ] ) );

        return new simple_api_response( $result, 200 );
    }
}