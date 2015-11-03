<?php
namespace Korobochkin\MarkUserAsSpammer\Admin;

class AdminNotices {

	/*
	 * Shows up the message block which inform about success or failure on block (unblock) user
	 * Show up the error if update_user_meta return an error (checkout load_users_page() function above).
	 *
	 * @since 2.0.0
	 */
	public static function add_notices() {
		// Logic grabbed from bbpress/includes/admin/topics.php
		if(
			!empty( $_GET['mark_user_as_spammer'])
			&&
			in_array( $_GET['mark_user_as_spammer'], array( 'spammed', 'unspammed' ) )
			&&
			!empty( $_GET['user_id'] )
		) {
			$user_id = absint( $_GET['user_id'] );

			if( $user_id > 0 ) {
				$is_failure = !empty( $_GET['failed'] ) ? true : false; // Was that a failure?
				$action = sanitize_text_field( $_GET['mark_user_as_spammer'] ); // Armor

				switch( $action ) {
					case 'spammed':
						if( $is_failure ) {
							$message = sprintf(
								_x( 'An error occured during blocking account with ID <code>%1$d</code>.', '%1$s - the account (user) ID (number)', 'mark_user_as_spammer' ),
								$user_id
							);
						}
						else {
							$message = sprintf(
								_x( 'Account with ID <code>%1$d</code> have been successfully banned and no longer log in.', '%1$s - the account (user) ID (number)', 'mark_user_as_spammer' ),
								$user_id
							);
						}
						break;

					case 'unspammed':
						if( $is_failure ) {
							$message = sprintf(
								_x( 'An error occured during unblocing account with ID <code>%1$d</code>.', '%1$s - the account (user) ID (number)', 'mark_user_as_spammer' ),
								$user_id
							);
						}
						else {
							$message = sprintf(
								_x( 'Account with ID <code>%1$d</code> have been successfully unbanned and now can log in.', '%1$s - the account (user) ID (number)', 'mark_user_as_spammer' ),
								$user_id
							);
						}
						break;

					default:
						$message = __( 'An error occured during something in Mark User as Spammer plugin.', 'mark_user_as_spammer' );
						break;
				}
				?>
				<div class="<?php echo $is_failure == true ? 'error' : 'updated'; ?> fade notice is-dismissible">
					<p><?php echo $message; ?></p>
				</div>
				<?php
			}
		}
	}
}
