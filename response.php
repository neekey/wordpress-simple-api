<?php

class simple_api_response {

    function __construct( $result, $httpCode, $additional = array() ){

        $json = $this->get_json($result);
        $charset = get_option('blog_charset');
        if (!headers_sent()) {
            status_header($httpCode);
            header("Content-Type: application/json; charset=$charset", true);
        }

        if( isset( $additional[ 'pagination' ] ) ){
            header("X-Total-Count: {$additional['pagination']['totalCount']} " );
            header("X-Page-Size: {$additional['pagination']['pageSize']}" );
            header("X-Total-Page: {$additional['pagination']['totalPage']}" );
        }

        echo $json;
        exit;
    }

    function get_json( $data ){
        $data = apply_filters('json_api_encode', $data);

        if (function_exists('json_encode')) {
            $json = json_encode($data);
        } else {
            // Use PEAR's Services_JSON encoder otherwise
            if (!class_exists('Services_JSON')) {
                require_once "library/JSON.php";
            }
            $json_service = new Services_JSON();
            $json = $json_service->encode($data);
        }

        return $json;
    }
}