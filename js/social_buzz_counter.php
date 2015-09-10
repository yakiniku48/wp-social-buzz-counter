<?php
require_once '../../../../wp-load.php';

$url = rawurldecode( $_GET[ 'url' ] );
$post_id = url_to_postid( $url );

$json = json_decode( @file_get_contents( 'https://urls.api.twitter.com/1/urls/count.json?url=' . rawurldecode( $url ) ) );
$twitter_count = ( isset( $json->count ) ) ? $json->count : 0;

$json = json_decode( @file_get_contents( 'https://graph.facebook.com/?id=' . rawurlencode( $url ) ) );
$facebook_count = ( isset( $json->shares ) ) ? $json->shares : 0;

$buzz_count = $twitter_count + $facebook_count;

update_post_meta( $post_id, 'social_buzz_count', $buzz_count );

header( 'Content-type: application/javascript' );

?>
// Code is Poetry

window.fbAsyncInit = function () {
	FB.Event.subscribe( "edge.create",
		function ( response ) { social_buzz_count(); }
	);
	FB.Event.subscribe( "edge.remove",
		function ( response ) { social_buzz_count(); }
	);
};

window.twttr = ( function ( d, s, id ) {
	var t, js, fjs = d.getElementsByTagName( s )[ 0 ];
	if ( d.getElementById( id ) ) return; js = d.createElement( s ); js.id = id;
	js.src = "//platform.twitter.com/widgets.js"; fjs.parentNode.insertBefore( js, fjs );
	return window.twttr || ( t = { _e: [], ready: function( f ){ t._e.push( f ) } });
}( document, "script", "twitter-wjs" ) );

twttr.ready( function ( twttr ) {
	twttr.events.bind( "tweet",
		function ( intent_event ) { social_buzz_count(); }
	);
});

function social_buzz_count() {
	jQuery.get( "<?php echo $_SERVER[ 'SCRIPT_NAME' ]; ?>", {
		url: "<?php echo rawurlencode( $url ); ?>",
	} );
}
