<?php
namespace Korobochkin\MarkUserAsSpammer;


class Translations {
	public static function load_translations() {
		// TODO: проверить адрес который тут фигурирует
		load_plugin_textdomain( Plugin::NAME, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
}
