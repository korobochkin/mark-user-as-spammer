<?php
namespace Korobochkin\MarkUserAsSpammer;


class Translations {
	public static function load_translations() {
		load_plugin_textdomain(
			Plugin::NAME,
			false,
			$GLOBALS['MarkUserAsSpammerPlugin']->plugin_path . '/languages'
		);
	}
}
