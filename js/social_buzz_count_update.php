<?php
require_once '../../../../wp-load.php';

update_post_meta( $_POST[ 'post_id' ], 'social_buzz_count', $_POST[ 'shares' ] );

?>1