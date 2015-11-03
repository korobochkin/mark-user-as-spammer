<?php
namespace Korobochkin\MarkUserAsSpammer\Authenticate;

class Authenticate {

	/**
	 * If user have meta mark_user_as_spammer (meta_key) and this meta equal === '1' (meta_value)
	 * we don't allow to auth on site. It's the same method like Multisite uses.
	 *
	 * @since 1.0.0
	 * @param object $user WordPress user object
	 * @return object WordPress user object or WP_Error object with error description.
	 */
	public static function authenticate( $user ) {
		if ( $user instanceof \WP_User ) {
			$status = \Korobochkin\MarkUserAsSpammer\Users\User::get_status( $user->ID );
			if ( $status === '1' ) {
				// Text copied from wp-includes/user.php (line 217)
				return new \WP_Error( 'spammer_account', __( '<strong>ERROR</strong>: Your account has been marked as a spammer.' ) );
			}
		}
		// Return $user if object is not an instantiated object of a WP_User class
		return $user;
	}
}
