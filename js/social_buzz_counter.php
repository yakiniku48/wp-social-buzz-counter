<?php
require_once '../../../../wp-load.php';

$url = rawurldecode( $_GET[ 'url' ] );
$post_id = url_to_postid( $url );

header( 'Content-type: application/javascript' );
?>
// Code is Poetry

jQuery( document ).ready( function ( $ ) {
	fb_buzz_count_update();
	tw_buzz_count_update();
} );


window.fbAsyncInit = function () {
	FB.Event.subscribe( "edge.create",
		function ( response ) { fb_buzz_count_update(); }
	);
	FB.Event.subscribe( "edge.remove",
		function ( response ) { fb_buzz_count_update(); }
	);
};

function fb_buzz_count_update() {
	jQuery.getJSON(
		"https://graph.facebook.com/?id=<?php echo $url; ?>",
		function ( json ) {
			if ( json.share != null && json.share.share_count ) {
				jQuery.post( "<?php echo plugins_url(); ?>/wp-social-buzz-counter/js/social_buzz_count_update.php", {
					post_id: "<?php echo $post_id; ?>",
					fb: parseInt( json.share.share_count )
				} );
			}
		}
	);
}

function tw_buzz_count_update() {
	jQuery.getJSON(
		"http://jsoon.digitiminimi.com/twitter/count.json?url=<?php echo $url; ?>&callback=?",
		function ( json ) {
			if ( json.count != null ) {
				jQuery.post( "<?php echo plugins_url(); ?>/wp-social-buzz-counter/js/social_buzz_count_update.php", {
					post_id: "<?php echo $post_id; ?>",
					tw: parseInt( json.count )
				} );
			}
		}
	);
}
