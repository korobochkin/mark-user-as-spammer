=== Mark User as Spammer ===
Contributors: korobochkin
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=me%40korobochkin%2ecom&lc=EN&item_name=For%20plugin%20Mark%20user%20as%20spammer&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted
Tags: spammers, ban, block users, spam, accounts, login, blacklist
Requires at least: 4.3.1
Tested up to: 4.3.1
Stable tag: 2.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The ability to mark specific users as spammers like on Multisite install.

== Description ==

The ability to mark specific users as spammers like on Multisite install. Right now after account have been banned user can't log in. Even if they currently have active sessions (and admin area currently open) they will be force log out without opportunity to log in again.

If you found any errors in this text (or on other plugin description tabs) please contact me directly via me@korobochkin.com or [submit a bug report on Github](https://github.com/korobochkin/mark-user-as-spammer/issues). You can also help by translating this plugin.

[Plugin on Github](https://github.com/korobochkin/mark-user-as-spammer). Photo on banner created by [Bastian Sara](https://stocksnap.io/photo/LVKUG7VU8F).

== Installation ==

= From your WordPress dashboard =

1. Visit 'Plugins > Add New'
2. Search for 'mark user as spammer'
3. Activate Mark User as Spammer from your Plugins page.
4. Ban or unban users on yourdomain.com/wp-admin/users.php page.

= From WordPress.org =

1. Download Mark User as Spammer.
2. Upload the 'mark-user-as-spammer' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...).
3. Activate Mark User as Spammer from your Plugins page.
4. Ban or unban users on yourdomain.com/wp-admin/users.php page.

== Screenshots ==

1. Ban or unban users on Users page. Blocked users marked with red background.
2. If you ban someone he can't log in anymore and WordPress shows up the error notice during login process.

== Frequently Asked Questions ==

= Why deleting accounts is not good idea? =

This plugin helpfull if you want disable any accounts but not want delete the account. Deleting account is not good idea because you can delete good account. After deleting you can't restore account but with this plugin you can ban or unban accounts anytime. You can ban or unban users on /wp-admin/users.php page by clicking links bellow username. Be careful, you can ban yourself, administrators or other highlevel users.

= Which information plugin stores in DB? =

The plugin adds only single meta option for each user (`mark_user_as_spammer`). On uninstall action plugin completely removes this metas for all users.

= How to manually edit account status? =

You can also switch account status by manually editing `mark_user_as_spammer` meta in `wp_usermeta` table. `1` — spammer. `0` — not spammer.

== Changelog ==

= 2.0.0 =
* Force delete active user sessions immediately after click "ban" link.
* Completely new plugin architecture.
* API for any other plugins.

= 1.0.2 =
* Prepare URL before output it. This plugin doesn't have XSS vulnerability like many others plugins (because we use `wp_nonce_url()` before output the links) but page may look incorrect if you try to open something like `site.com/users.php?"><script>alert('hi')</script>`. Script not working (thanks `wp_nonce_url()`) but markup looks crashed.

= 1.0.1 =
* Translated all comments in code to english.
* Added plugin icon, banner and screenshots.
* Fixed WordPress required and tested up versions.
* Improved readme file.

= 1.0.0 =
* First version of plugin.

== Upgrade Notice ==

= 2.0.0 =
Force delete active user sessions immediately. Better plugin architecture and API for developers.

= 1.0.2 =
Security improvements release. Better output for the links.

= 1.0.1 =
Fixed WordPress required and tested up versions.
