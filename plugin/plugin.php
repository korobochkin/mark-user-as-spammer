<?php
/**
 * Plugin Name: Mark User as Spammer
 * Plugin URI: http://korobochkin.com/
 * Description: This plugin provides the ability to mark specific users as spammers like on Multisite install.
 * Author: Kolya Korobochkin
 * Author URI: http://korobochkin.com/
 * Version: 1.0.0
 * Text Domain: mark_user_as_spammer
 * Domain Path: /languages/
 */
class Mark_User_As_Spammer {

	public $selectors = array();

	function __construct() {
		add_filter( 'authenticate', array( $this, 'authenticate' ), 99 );
		if ( is_admin() ) {
			add_filter( 'user_row_actions', array( $this, 'user_row_actions' ), 10, 2);
			add_action( 'load-users.php', array( $this, 'load_users_page' ) );
			add_action( 'admin_notices',  array( $this, 'admin_notices' ) );
		}
	}

	/*
	 * В случае если у пользователя есть meta, которая говорит о том, что он спаммер мы не даем авторизацию
	 * Таким же способом работает Multisite, если пользователь помечен как спамер.
	 */
	public function authenticate( $user ) {
		if ( $user instanceof WP_User ) {
			$meta = get_user_meta( $user->ID, 'mark_user_as_spammer', true);
			if ( isset( $meta['spammer'] ) && $meta['spammer'] == true) {
				// Text copied from wp-includes/user.php (line 217)
				return new WP_Error( 'spammer_account', __( '<strong>ERROR</strong>: Your account has been marked as a spammer.' ) );
			}
		}
		// Возвращаем $user если объект на самом деле не экзепляр класса WP_User
		return $user;
	}

	/*
	 * Добавляем ссылку-кнопку, чтобы помечать пользователя как спамера.
	 */
	public function user_row_actions( $actions, $user_object ) {
		$meta = get_user_meta( $user_object->ID, 'mark_user_as_spammer', true);

		$is_spammer = false;
		if ( isset( $meta['spammer'] ) && $meta['spammer'] == true) {
			$is_spammer = true;
			$this->selectors[] = $user_object->ID;
		}

		$url = add_query_arg(
			array(
				'action' => $is_spammer ? 'mark_user_as_non_spammer' : 'mark_user_as_spammer',
				'user_id' => $user_object->ID
			),
			'users.php'
		);

		$url = wp_nonce_url(
			$url,
			($is_spammer ? 'mark_user_as_non_spammer_' : 'mark_user_as_spammer_') . $user_object->ID
		);

		$actions['spammer'] = '<a href="'
		                      . admin_url( $url )
		                      . '" class="mark-user-as-spammer" title="'
		                      .
		                      (
		                            $is_spammer ?
										esc_attr_x ('Unban user. He will be able to log in on site.', 'Verb. Mark user (account) like non spammer account', 'mark_user_as_spammer')
										:
										esc_attr_x ('Ban user. He will not be able to log in on site and get an error that his account marked as spammer.', 'Verb. Mark user (account) like spammer account', 'mark_user_as_spammer')
		                      )
		                      .'">'
		                      . ($is_spammer ?
								_x ('Unban', 'Verb. Mark user (account) like non spammer account', 'mark_user_as_spammer')
								:
								_x ('Ban', 'Verb. Mark user (account) like spammer account', 'mark_user_as_spammer'))
		                      . '</a>';
		return $actions;
	}

	/*
	 * При загрузке страницы пользователей смотрим, относится ли запрос к нашему плагину.
	 * Если да, то блокируем (разблокируем) пользователя.
	 */
	public function load_users_page() {
		// Стили для заблокированных пользователей
		add_action( 'admin_footer', array( $this, 'admin_footer' ) );

		// Относится ли запрос к нашему плагину?
		if ( !empty( $_GET['action'] ) && in_array( $_GET['action'], array ('mark_user_as_spammer', 'mark_user_as_non_spammer')) ) {
			// Необходим ID пользователя
			if( !empty( $_GET['user_id'] ) ) {

				// Only users with promote_users cap can do this (by default admin and super admin)
				if( !current_user_can( 'promote_users' ) ) {
					wp_die( __( 'You do not have the permission to do that!', 'mark_user_as_spammer' ) );
				}

				// Check nonce (WordPress dies if nonce not valid and return 403)
				check_admin_referer( $_GET['action'] . '_' .  $_GET['user_id'] );

				// Получаем метаданные
				$_GET['user_id'] = (int) $_GET['user_id'];
				$user_meta = get_user_meta( $_GET['user_id'], 'mark_user_as_spammer', true );
				// Если метаданные с именем mark_user_as_spammer уже есть у пользователя,
				// меняем содержимое на противоположное
				//if ( !empty( $user_meta ) ) {
				if ( isset( $meta['spammer'] ) ) {
					$user_meta['spammer'] = !(bool)$user_meta['spammer'];
				}
				// Если данных нет, создаем данные по умолчанию и смотрим какой "флажок" нужно поставить
				else {
					$user_meta['spammer'] = false;
				}

				switch ($_GET['action']) {
					case 'mark_user_as_spammer':
						$user_meta['spammer'] = true;
						$message = 'spammed';
						break;

					case 'mark_user_as_non_spammer':
						$user_meta['spammer'] = false;
						$message = 'unspammed';
						break;
				}

				// Обновляем метаданные в БД
				$update = update_user_meta(
					$_GET['user_id'],
					'mark_user_as_spammer',
					$user_meta
				);

				$message = array( 'mark_user_as_spammer' => $message);
				if( !$update ) {
					$message['failed'] = '1';
				}

				// Удаляем ненужные аргументы из адреса и делаем редирект на ту же страницу, но с другими параметрами
				wp_safe_redirect(
					add_query_arg(
						$message, remove_query_arg( array ( 'action', '_wpnonce' ) )
					)
				);
				exit();

				// И выводим сообщение об успешном блокировании (разблокировании) пользователя
			}
		}
	}

	public function admin_notices() {
		// Logic grabbed from bbpress/includes/admin/topics.php
		if(
			!empty( $_GET['mark_user_as_spammer'])
			&&
			in_array( $_GET['mark_user_as_spammer'], array( 'spammed', 'unspammed' ) )
			&&
			!empty( $_GET['user_id'] )
		) {
			$is_failure = !empty( $_GET['failed'] ) ? true : false; // Was that a failure?

			$_GET['user_id'] = (int) $_GET['user_id'];

			switch( $_GET['mark_user_as_spammer']) {
				case 'spammed':
					if( $is_failure ) {
						$message = sprintf(
							_x( 'An error occured during blocking account with ID <code>%1$s</code>.', '%1$s - the account (user) ID (number)', 'mark_user_as_spammer' ),
							$_GET['user_id']
						);
					}
					else {
						$message = sprintf(
							_x( 'Account with ID <code>%1$s</code> have been banned and no longer log in.', '%1$s - the account (user) ID (number)', 'mark_user_as_spammer' ),
							$_GET['user_id']
						);
					}
					break;

				case 'unspammed':
					if( $is_failure ) {
						$message = sprintf(
							_x( 'An error occured during unblocing account with ID <code>%1$s</code>.', '%1$s - the account (user) ID (number)', 'mark_user_as_spammer' ),
							$_GET['user_id']
						);
					}
					else {
						$message = sprintf(
							_x( 'Account with ID <code>%1$s</code> have been successfully unbanned and now can log in.', '%1$s - the account (user) ID (number)', 'mark_user_as_spammer' ),
							$_GET['user_id']
						);
					}
					break;
			}

			?>
			<div id="message" class="<?php echo $is_failure === true ? 'error' : 'updated'; ?> fade">
				<p><?php echo $message; ?></p>
			</div>
			<?php
		}
	}

	/*
	 * Выделяем заблокированных пользователей красным фоном (как на Мультисайте)
	 */
	public function admin_footer() {
		if( !empty( $this->selectors ) ) {
			?>
			<style media="all" type="text/css">
				<?php foreach( $this->selectors as $selector) {
					echo '#user-' . $selector . ',';
				} ?> .mark-user_as_spammer_spammer { background: #faafaa; }
			</style>
			<?php
		}
	}
}

new Mark_User_As_Spammer();
?>