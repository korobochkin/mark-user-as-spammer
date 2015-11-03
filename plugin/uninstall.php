<?php
// If uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
exit();

// Delete user metas with `mark_user_as_spammer` meta_value
global $wpdb;
$wpdb->delete( $wpdb->usermeta, array( 'meta_key' => 'mark_user_as_spammer' ), array ( '%s' ) );
