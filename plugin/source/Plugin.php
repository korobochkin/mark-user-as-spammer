<?php
namespace Korobochkin\MarkUserAsSpammer;

class Plugin {

	const NAME = 'mark_user_as_spammer';

	public static function run() {
		add_action( 'plugins_loaded', array( 'Korobochkin\MarkUserAsSpammer\Translations', 'load_translations' ) );

		add_filter( 'authenticate', array( 'Korobochkin\MarkUserAsSpammer\Authenticate\Authenticate', 'authenticate' ), 99 );
	}
}
