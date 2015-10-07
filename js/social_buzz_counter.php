<?php
require_once '../../../../wp-load.php';

$url = rawurldecode( $_GET[ 'url' ] );
$post_id = url_to_postid( $url );

$json = json_decode( @file_get_contents( 'https://graph.facebook.com/?id=' . rawurlencode( $url ) ) );
$facebook_count = ( isset( $json->shares ) ) ? $json->shares : 0;

update_post_meta( $post_id, 'social_buzz_count', $facebook_count );

header( 'Content-type: application/javascript' );

?>
// Code is Poetry

jQuery( document ).ready( function ( $ ) {
	social_buzz_count_update();
} );


window.fbAsyncInit = function () {
	FB.Event.subscribe( "edge.create",
		function ( response ) { social_buzz_count_update(); }
	);
	FB.Event.subscribe( "edge.remove",
		function ( response ) { social_buzz_count_update(); }
	);
};

function social_buzz_count_update() {
	jQuery.get(
		"https://graph.facebook.com/",
		{
			id: "<?php echo $url; ?>",
		},
		function ( json ) {
			if ( json.shares != null ) {
				jQuery.post( "<?php echo plugins_url(); ?>/wp-social-buzz-counter/js/social_buzz_count_update.php", {
					post_id: "<?php echo $post_id; ?>",
					shares: json.shares
				} );
			}
		}
	);
}
