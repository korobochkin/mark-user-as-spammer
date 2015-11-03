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
			$meta = get_user_meta( $user->ID, \Korobochkin\MarkUserAsSpammer\Users\User::BANNED_OPTION_NAME, true);
			if ( $meta === '1' ) {
				// Text copied from wp-includes/user.php (line 217)
				return new \WP_Error( 'spammer_account', __( '<strong>ERROR</strong>: Your account has been marked as a spammer.' ) );
			}
		}
		// Return $user if object is not an instantiated object of a WP_User class
		return $user;
	}

	/**
	 * Force log out for already logged in users and destroy their sessions.
	 *
	 * @since 2.0.0
	 */
	public static function log_out_banned_users() {
		if( ! is_user_logged_in() )
			return;

		$user = wp_get_current_user();
		$user_status = \Korobochkin\MarkUserAsSpammer\Users\User::get_status( $user->ID );

		if( $user_status === '1' ) {
			wp_logout();
			wp_redirect( home_url( '/' ) );
			exit;
		}
	}

	public static function wp_authenticate_user( $user ) {
		$kk = '';
		return $user;
	}
}
