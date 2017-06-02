<?php
require_once '../../../../wp-load.php';

if ( isset( $_POST[ 'post_id' ] ) && '' != $_POST[ 'post_id' ] ) {
	if ( isset( $_POST[ 'fb' ] ) && '' != $_POST[ 'fb' ] ) {
		update_post_meta( $_POST[ 'post_id' ], 'fb_buzz_count', $_POST[ 'fb' ] );
	}
	if ( isset( $_POST[ 'tw' ] ) && '' != $_POST[ 'tw' ] ) {
		update_post_meta( $_POST[ 'post_id' ], 'tw_buzz_count', $_POST[ 'tw' ] );
	}
}

echo 1;