<?php
namespace Korobochkin\MarkUserAsSpammer;

class Uninstaller {
	// TODO: Проверить удалялку

	/*
	 * Uninstall action callback.
	 *
	 * @since 2.0.0
	 */
	public static function uninstall() {
		// The uninstall plugin must be this file
		// The current user can activate plugins
		if(
			$GLOBALS['MarkUserAsSpammerPlugin']->plugin_path != WP_UNINSTALL_PLUGIN
			||
			! current_user_can( 'activate_plugins')
		){
			return;
		}

		// Additional check
		check_admin_referer( 'bulk-plugins' );

		// Delete user metas with `mark_user_as_spammer` meta_value
		global $wpdb;
		$wpdb->delete( $wpdb->usermeta, array( 'meta_key' => 'mark_user_as_spammer' ), array ( '%s' ) );
	}
}