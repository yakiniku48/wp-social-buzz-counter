<?php
/*
Plugin Name: Social Buzz Counter
Plugin URI: http://
Description: Facebook shared count and Twitter tweeted count -> custom field social_buzz_count.
Author: Hideyuki Motoo
Author URI: http://motoo.net/
Version: 0.1
*/

function sbc_wp_enqueue_scripts() {
	if ( !is_admin() && is_single() ) {
		wp_enqueue_script( 'social_buzz_counter', plugins_url( 'js/social_buzz_counter.php?url=' . rawurlencode( get_the_permalink() ), __FILE__ ), array( 'jquery' ), '', true );
	}
}
add_action( 'wp_enqueue_scripts', 'sbc_wp_enqueue_scripts' );

