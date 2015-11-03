<?php
namespace Korobochkin\MarkUserAsSpammer\Users;

class User {
	const BANNED_OPTION_NAME = 'mark_user_as_spammer';

	public static $statuses = array(
		'not_a_spammer', // 0. Not spammer
		'spammer'        // 1. Account locked. Spammer.
	);

	public static function set_status( $user_id, $status ) {
		$status = (string) $status;
		switch( $status ) {
			case '1':
			case '0':
				break;

			default:
				return false;
		}

		$update = update_user_meta(
			$user_id,
			self::BANNED_OPTION_NAME,
			$status
		);

		if( !$update ) {
			return false;
		}
		return true;
	}

	public static function get_status( $user_id ) {
		$user_status = get_user_option( self::BANNED_OPTION_NAME, $user_id, false );

		if( $user_status === '1' ) {
			return $user_status;
		}
		return '0';
	}
}
