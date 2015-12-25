<?php
namespace Korobochkin\MarkUserAsSpammer;
/*
Plugin Name: Mark User as Spammer
Plugin URI: http://korobochkin.com/
Description: The ability to mark specific users as spammers like on Multisite install. Right now after account have been banned user can't log in. Even if they currently have active sessions (and admin area currently open) they will be force log out without opportunity to log in again.
Author: Kolya Korobochkin
Author URI: http://korobochkin.com/
Version: 2.0.1
Text Domain: mark_user_as_spammer
Domain Path: /languages/
Requires at least: 4.3.1
Tested up to: 4.3.1
License: GPLv2 or later
*/

/**
 * Autoloader for all classes.
 *
 * @since 2.0.0
 */
require_once 'vendor/autoload.php';
$GLOBALS['MarkUserAsSpammerPlugin'] = new Plugin( __FILE__ );
$GLOBALS['MarkUserAsSpammerPlugin']->run();
