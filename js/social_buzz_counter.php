<?php
require_once '../../../../wp-load.php';

$url = rawurldecode( $_GET[ 'url' ] );
$post_id = url_to_postid( $url );

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
	jQuery.when(
		jQuery.getJSON(
			"https://graph.facebook.com/?id=<?php echo $url; ?>"
		),
		jQuery.getJSON(
			"http://jsoon.digitiminimi.com/twitter/count.json?url=<?php echo $url; ?>&callback=?"
		)
	)
	.done( function ( fbJson, twJson ) {
		var buzzCount = 0;
		if ( fbJson[0].shares != null ) {
			buzzCount += parseInt( fbJson[0].shares );
		}
		if ( twJson[0].count != null ) {
			buzzCount += parseInt( twJson[0].count );
		}
		jQuery.post( "<?php echo plugins_url(); ?>/wp-social-buzz-counter/js/social_buzz_count_update.php", {
			post_id: "<?php echo $post_id; ?>",
			shares: buzzCount
		} );
	} );
}
