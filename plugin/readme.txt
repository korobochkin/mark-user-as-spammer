=== Mark User as Spammer ===
Contributors: korobochkin
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=me%40korobochkin%2ecom&lc=EN&item_name=For%20plugin%20Mark%20user%20as%20spammer&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted
Tags: spammers, ban, block users
Requires at least: 4.1.1
Tested up to: 4.1.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The ability to mark specific users as spammers like on Multisite install.

== Description ==

The ability to mark specific users as spammers like on Multisite install. If you mark user as spammer he can't log in into WordPress and got an error shows up that account have been marked as spammer account.

== Installation ==

Install this plugin like many others WordPress plugins. Simply download archive and upload to /wp-content/plugins directory.

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= Which information plugin stores in DB? =

The plugin add only single user meta option to each user with meta_key equal 'mark_user_as_spammer'. On uninstall action plugin completely remove this metas for all users.

== Changelog ==

= 1.0.0 =
* First version of plugin.
