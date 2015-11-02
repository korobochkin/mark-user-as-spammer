<?php
namespace Korobochkin\MarkUserAsSpammer;

class Plugin {

	const NAME = 'mark_user_as_spammer';

	public static function run() {
		add_action( 'plugins_loaded', array( 'Korobochkin\MarkUserAsSpammer\Translations', 'load_translations' ) );
		add_filter( 'authenticate', array( 'Korobochkin\MarkUserAsSpammer\Authenticate\Authenticate', 'authenticate' ), 99 );

		if ( is_admin() ) {
			add_filter( 'user_row_actions', array( 'Korobochkin\MarkUserAsSpammer\Admin\UserRowActions', 'render' ), 10, 2);
			add_action( 'load-users.php', array( __CLASS__, 'load_users_page' ) );
			add_action( 'admin_notices',  array( __CLASS__, 'admin_notices' ) );
		}
	}
}
