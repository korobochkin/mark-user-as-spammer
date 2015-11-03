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
		 * Prevent auth via logged in form (wp-login.php for example).
		 */
		add_filter( 'authenticate', array( 'Korobochkin\MarkUserAsSpammer\Authenticate\Authenticate', 'authenticate' ), 99 );

		if ( is_admin() ) {
			add_filter( 'user_row_actions', array( 'Korobochkin\MarkUserAsSpammer\Admin\RowActions\User', 'add_actions' ), 10, 2);
			add_action( 'load-users.php', array( 'Korobochkin\MarkUserAsSpammer\Admin\LoadUsersPage', 'catch_request' ) );
			add_action( 'admin_notices',  array( 'Korobochkin\MarkUserAsSpammer\Admin\AdminNotices', 'add_notices' ) );

			// TODO: добавить балк экшены (в текущей версии WP нет такой возможности)
			// https://codex.wordpress.org/Plugin_API/Filter_Reference/bulk_actions
		}
	}
}
