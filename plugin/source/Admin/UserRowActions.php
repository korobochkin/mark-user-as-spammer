<?php
namespace Korobochkin\MarkUserAsSpammer\Admin;

class UserRowActions {
	
	public static function render( $actions, $user_object ) {
		$meta = get_user_meta( $user_object->ID, \Korobochkin\MarkUserAsSpammer\Users\User::BANNED_OPTION_NAME, true);

		$is_spammer = false;
		if ( $meta === '1' ) {
			$is_spammer = true;
			self::$selectors[] = $user_object->ID;
		}
		unset( $meta );

		$url = add_query_arg(
			array(
				'mark_user_as_spammer_action' => $is_spammer ? 'unban' : 'ban',
				'user_id' => $user_object->ID
			)
		);

		$url = wp_nonce_url(
			$url,
			( $is_spammer ? 'mark_user_as_spammer_unban_' : 'mark_user_as_spammer_ban_' ) . $user_object->ID,
			'mark_user_as_spammer_nonce'
		);

		$url = site_url( $url );

		/*
		 * Always use esc_url() before output the links!
		 * wp_nonce_url() already pass url to esc_html and script tags will be encoded but we need armor to protect URL from XSS
		 */
		$url = esc_url( $url );

		$actions['spammer'] = '<a href="'
		                      . $url
		                      . '" class="mark-user-as-spammer" title="' . (
		                      $is_spammer ?
			                      esc_attr_x ('Unban user. He will be able to log in on site.', 'Verb. Mark user (account) like non spammer account', 'mark_user_as_spammer')
			                      :
			                      esc_attr_x ('Ban user. He will not be able to log in on site and get an error that his account marked as spammer.', 'Verb. Mark user (account) like spammer account', 'mark_user_as_spammer')
		                      ) .'">'
		                      . (
		                      $is_spammer ?
			                      _x ('Unban', 'Verb. Mark user (account) like non spammer account', 'mark_user_as_spammer')
			                      :
			                      _x ('Ban', 'Verb. Mark user (account) like spammer account', 'mark_user_as_spammer')
		                      )
		                      . '</a>';
		return $actions;
	}
}
