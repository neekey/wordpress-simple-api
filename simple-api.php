<?php
/*
Plugin Name: Simple-API
Description: A Simple API for WordPress
Version: 0.0.1
Author: Neekey
Author URI: http://neekey.net/
*/

include_once 'controller.php';

$simpleApiController = new simple_api_controller();

$simpleApiBase = 'api3';

function simple_api_init() {
    global $wp_rewrite;
    add_filter('rewrite_rules_array', 'simple_api_rewrites');
    add_action('template_redirect', 'simple_api_template_rewrite');
    $wp_rewrite->flush_rules();
}

function simple_api_template_rewrite(){
    global $simpleApiBase;
    global $simpleApiController;

    if( isset( $_REQUEST[ $simpleApiBase ] ) ){

        $controller = 'ctrl_' . $_REQUEST[ $simpleApiBase ];

        if( method_exists( $simpleApiController, $controller ) ){
            $simpleApiController->$controller();
        }
    }
}

function simple_api_activation() {
    // Add the rewrite rule on activation
    global $wp_rewrite;
    add_filter('rewrite_rules_array', 'simple_api_rewrites');
    $wp_rewrite->flush_rules();
}

function simple_api_deactivation() {
    // Remove the rewrite rule on deactivation
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}

function simple_api_rewrites($wp_rules) {
    global $simpleApiBase;
    if (empty($base)) {
        return $wp_rules;
    }
    $simple_api_rules = array(
        "$simpleApiBase\$" => "index.php?{$simpleApiBase}=info",
        "$simpleApiBase/(.+)\$" => "index.php?{$simpleApiBase}=\$matches[1]"
    );
    return array_merge($simple_api_rules, $wp_rules);
}

// Add initialization and activation hooks
add_action('init', 'simple_api_init');
register_activation_hook( __FILE__, 'simple_api_activation');
register_deactivation_hook( __FILE__, 'simple_api_deactivation');

?>
