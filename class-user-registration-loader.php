<?php
/**
 * User Registration Loader
 *
 * @package User Registration
 * @author Dinesh Chouhan
 */

// exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

if ( ! class_exists( 'User_Registration_Loader' ) ) :

	/**
	 * Create class User_Registration_Loader
	 */
	class User_Registration_Loader {

		/**
		 * Declare a static variable instance.
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 * WordPress Customizer Object
		 *
		 * @since 1.0.0
		 * @var $wp_customize
		 */
		private $wp_customize;

		/**
		 * Initiate class
		 *
		 * @since 1.0.0
		 * @return object
		 */
		public static function instance() {

			if ( ! isset( self::$instance ) ) {
				self::$instance = new User_Registration_Loader();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->init();
		}

		/**
		 * WP Core Shortcodes.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function init() {

			add_action( 'login_head', array( $this, 'user_activation' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'wp_ajax_save_new_user_data', array( $this, 'save_new_user_data_callback' ) );
			add_action( 'wp_ajax_nopriv_save_new_user_data', array( $this, 'save_new_user_data_callback' ) );
			add_filter( 'theme_page_templates', array( $this, 'custom_page_template' ) );
			add_filter( 'page_template', array( $this, 'custom_page_template_markup' ) );
		}

		/**
		 * User activation.
		 */
		public function user_activation(){
			if( isset( $_GET['dc-user-registration'] ) ) {
				$uniqid = $_GET['dc-user-registration'];
				$waiting_data = get_option( 'user-registration-not-activated', array() );

				if( ! isset( $waiting_data[$uniqid] ) ) {
					$data = $waiting_data[$uniqid];
					$userdata = array(
						'first_name' =>  $data['first-name'],
						'last_name'  =>  $data['last-name'],
						'user_email' =>  $data['email'],
						'user_login' =>  $data['username'],
						'role'       =>  $data['user-roles'],
						'user_pass'  =>  $data['password']  // When creating an user, `user_pass` is expected.
					);

					$user_id = wp_insert_user( $userdata );
					unset( $waiting_data[$uniqid] );
					update_option( 'user-registration-not-activated', $waiting_data );
				}
			}
		}

		/**
		 * Added new user.
		 */
		public function save_new_user_data_callback() {
			
			wp_verify_nonce( 'new-user-registration', $_POST['nonce'] );

			$response = array(
				'success' => false,
				'message' => __('Something went wrong, Please try again.' ),
			);
			if ( isset( $_POST['data'] ) ) {
				parse_str( $_POST['data'] , $data );

				if( isset( $data['email'] ) && ! empty( $data['email'] ) ) {
			
					if ( ! get_user_by( 'email', $data['email'] ) && ! get_user_by( 'slug', $data['username'] ) ) {
						$uniqid = sha1( uniqid() );
						$waiting_data = get_option( 'user-registration-not-activated', array() );
						$waiting_data[$uniqid] = $data;
						$url = add_query_arg( 'dc-user-registration', $uniqid, wp_login_url() );
						$subject = __( 'Confirmation Mail from User Registration plugin.', 'user-registration' );
						$message = __( 'Dear ', 'user-registration' ) . $data['first-name'];
						$message .= "\n";
						$message .= __( 'Please click below to confirm your registration.', 'user-registration' );
						$message .= "\n<a href='" . $url . "'>" . __( 'Confirm Me', 'user-registration' ) . "</a>";
						$headers = 'From: '. get_bloginfo( 'name' ) . "\r\n";
						
						if( wp_mail( $data['email'], $subject, $message, $headers ) ) {
							update_option( 'user-registration-not-activated', $waiting_data );
							$response['success'] = true;
							$response['message'] = __( 'Congrats, You have been successfully registrated, please check your email id for confirmatin link.', 'user-registration' );
						} else {
							$response['message'] = __( 'Please verify your SMPT settings, or try again later.', 'user-registration' );
						}

					} else {
						$response['message'] = __( 'Your already exist with same email or username, Please try with different username & email id.', 'user-registration' );
					}
				} else {
					$response['message'] = __( 'Please fill required fields.' );
				}
			}

			wp_send_json( $response );
		}

		/**
		 * Add requied script & styles.
		 */
		public function enqueue_scripts(){
			
			if ( is_page() && 'user-registration-template' == get_page_template_slug() ) {

				// Enqueue JS.
				wp_enqueue_style( 'user-registration-style', USER_REGISTRATION_URL . 'assets/style.css' );
				wp_enqueue_script( 'user-registration-script', USER_REGISTRATION_URL . 'assets/script.js', array( 'jquery' ), USER_REGISTRATION_VER, true );
				wp_localize_script( 'user-registration-script', 'UserRegistrationLocalized', array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'nonce' => wp_create_nonce('new-user-registration'),
				));
			}
		}

		/**
		 * Add custom page template
		 */
		public function custom_page_template( $templates ) {
			$templates[ 'user-registration-template' ] = __( 'User Registration', 'user-registration' );
			return $templates;
		}

		/**
		 * Redirect 
		 */
		public function custom_page_template_markup( $template ) {

			if ( is_page() && 'user-registration-template' == get_page_template_slug() ) {
				return USER_REGISTRATION_DIR . '/user-registration-template.php';
			}
			 
			return $template;
		}

	}

	User_Registration_Loader::instance();
endif;
