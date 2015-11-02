<?php
namespace Korobochkin\MarkUserAsSpammer;

class Plugin {

	const NAME = 'mark_user_as_spammer';

	public $plugin_path = NULL;

	public function __construct( $run_from_file ) {
		$this->plugin_path = dirname( plugin_basename( $run_from_file ) );
	}

	public function run() {
		add_action( 'plugins_loaded', array( 'Korobochkin\MarkUserAsSpammer\Translations', 'load_translations' ) );

		/*
		 * Prevent auth via logged in form (wp-login.php).
		 */
		add_filter( 'authenticate', array( 'Korobochkin\MarkUserAsSpammer\Authenticate\Authenticate', 'authenticate' ), 99 );

		/*
		 * Force log out already logged in users and destroy their sessions.
		 */
		add_action( 'set_current_user', array( 'Korobochkin\MarkUserAsSpammer\Authenticate\Authenticate', 'log_out_banned_users' ) );

		if ( is_admin() ) {
			add_filter( 'user_row_actions', array( 'Korobochkin\MarkUserAsSpammer\Admin\UserRowActions', 'render' ), 10, 2);
			add_action( 'load-users.php', array( 'Korobochkin\MarkUserAsSpammer\Admin\LoadUsersPage', 'render' ) );
			add_action( 'admin_notices',  array( 'Korobochkin\MarkUserAsSpammer\Admin\AdminNotices', 'add_notices' ) );
		}
	}
}
