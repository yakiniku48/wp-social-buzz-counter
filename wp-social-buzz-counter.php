<?php
/*
Plugin Name: Social Buzz Counter
Plugin URI: http://
Description: Facebook shared count and Twitter tweeted count -> custom field social_buzz_count.
Author: Hideyuki Motoo
Author URI: http://nearly.jp/
Version: 0.1
*/

function sbc_wp_enqueue_scripts() {
	if ( !is_admin() && is_single() && !is_preview() ) {
		wp_enqueue_script( 'social_buzz_counter', plugins_url( 'js/social_buzz_counter.php?url=' . rawurlencode( get_the_permalink() ), __FILE__ ), array( 'jquery' ), '', true );
	}
}
add_action( 'wp_enqueue_scripts', 'sbc_wp_enqueue_scripts' );

function sbc_request( $vars ) {
	if ( is_admin() && isset( $vars[ 'orderby' ] ) ) {
		if ( $vars[ 'orderby' ] == 'fb_buzz_count' ) { 
			$vars[ 'meta_key' ] = 'fb_buzz_count';
			$vars[ 'orderby' ]  = 'meta_value_num';
		}
		if ( $vars[ 'orderby' ] == 'tw_buzz_count' ) { 
			$vars[ 'meta_key' ] = 'tw_buzz_count';
			$vars[ 'orderby' ]  = 'meta_value_num';
		}
	}
	return $vars;
}
add_filter( 'request', 'sbc_request' );

function sbc_manage_edit_posts_sortable_columns ( $columns ) {
	$columns[ 'fbbc' ] = 'fb_buzz_count';
	$columns[ 'twbc' ] = 'tw_buzz_count';
	return $columns;
}
function sbc_manage_posts_columns ( $columns ) {
	global $post_type;
	add_filter( 'manage_edit-' . $post_type . '_sortable_columns', 'sbc_manage_edit_posts_sortable_columns' );
	
	$columns[ 'fbbc' ] = 'FbBC';
	$columns[ 'twbc' ] = 'TwBC';
	return $columns;
}
add_filter( 'manage_posts_columns', 'sbc_manage_posts_columns' );

function sbc_manage_posts_custom_column( $column_name, $post_id ) {
	if ( $column_name == 'fbbc' ) {
		echo (int) esc_html( get_post_meta( $post_id, 'fb_buzz_count', true ) );
	}
	if ( $column_name == 'twbc' ) {
		echo (int) esc_html( get_post_meta( $post_id, 'tw_buzz_count', true ) );
	}
}
add_action( 'manage_posts_custom_column', 'sbc_manage_posts_custom_column', 10, 2 );
