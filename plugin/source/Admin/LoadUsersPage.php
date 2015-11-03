<?php
namespace Korobochkin\MarkUserAsSpammer\Admin;

class LoadUsersPage {

	public static $selectors;

	public static function catch_request() {
		// Styles for banned accounts (red background)
		add_action( 'admin_footer', array( __CLASS__, 'admin_footer' ) );

		// Current request related to this plugin?
		if (
			! empty( $_GET['mark_user_as_spammer_action'] )
			&&
			in_array( $_GET['mark_user_as_spammer_action'], array ('unban', 'ban'))
			&&
			! empty( $_GET['user_id'] )
		) {
			$user_id = absint( $_GET['user_id'] );

			if( $user_id > 0) {
				// No sanitize because we use in_array above
				$action = $_GET['mark_user_as_spammer_action'];

				// Only users with promote_users cap can do this (by default admin and super admin)
				if( !current_user_can( 'promote_users' ) ) {
					wp_die( __( 'You do not have the permission to do that!', 'mark_user_as_spammer' ) );
				}

				// Check nonce (WordPress dies if nonce not valid and return 403)
				check_admin_referer( 'mark_user_as_spammer_' . $action . '_' .  $user_id,  'mark_user_as_spammer_nonce' );

				switch ($action) {
					case 'ban':
						$message['mark_user_as_spammer'] = 'spammed';

						// Update user meta in DB
						$update = \Korobochkin\MarkUserAsSpammer\Users\User::set_status( $user_id, '1' );

						// Drop all user active sessions
						\Korobochkin\MarkUserAsSpammer\Users\User::delete_sessions( $user_id );
						break;

					case 'unban':
					default:
						$message['mark_user_as_spammer'] = 'unspammed';

						// Update user meta in DB
						$update = \Korobochkin\MarkUserAsSpammer\Users\User::set_status( $user_id, '0' );

						break;
				}

				if( !$update ) {
					$message['failed'] = '1';
				}

				// Delete args from URL and do redirect to current page with args with results of operation
				wp_safe_redirect(
					add_query_arg(
						$message, remove_query_arg( array ( 'mark_user_as_spammer_action', 'mark_user_as_spammer_nonce' ) )
					)
				);
				exit();
			}
		}
	}

	public static function admin_footer() {
		if( !empty( self::$selectors ) ) {
			?>
			<style media="all" type="text/css">
				<?php foreach( self::$selectors as $selector) {
					echo '#user-' . $selector . ',';
				} ?> .mark-user_as_spammer_spammer { background: #faafaa; }
			</style>
			<?php
		}
	}
}
