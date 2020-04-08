<?php
/**
 * JmsCovid Admin.
 *
 * @class   JmsCovid_Admin
 * @package jms-covid-19
 */

if ( ! class_exists( 'JmsCovid_Admin' ) ) {
	/**
	 * JmsCovid_Admin class
	 */
	class JmsCovid_Admin {
		/**
		 * JmsCovid_Admin constructor.
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'jms_covid_19_load_textdomain' ) );
			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 99999 );

			add_action( 'jms_check_covid_19', array( $this, 'jms_covid_19_cron_exec' ) );

		}

		/**
		 * Register a custom menu page.
		 */
		public function add_admin_menu() {
			add_menu_page(
				esc_html__( 'JMS COVID-19', 'jms-covid-19' ),
				esc_html__( 'JMS COVID-19', 'jms-covid-19' ),
				'manage_options',
				'jms_covid_19',
				array(
					'JmsCovid_Params',
					'params_form',
				),
				'dashicons-admin-comments',
				888
			);

		}

		/**
		 * Loads a plugin's translated strings
		 */
		public function jms_covid_19_load_textdomain() {
			load_plugin_textdomain( 'jms-covid-19', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}


		/**
		 * Enqueue admin scripts.
		 */
		public function admin_enqueue_scripts() {
			$screen    = get_current_screen();
			$screen_id = $screen ? $screen->id : '';

			if ( in_array( $screen_id, array( 'toplevel_page_jms_covid_19' ), true ) ) {
				wp_register_style( 'jms-covid-bootstrap', JMS_COVID_19_CSS_URL . 'bootstrap.min.css', array(), '4.4.1', 'all' );
				wp_register_style( 'jms_covid_semantic', JMS_COVID_19_CSS_URL . 'semantic.min.css', array(), '2.4.2', 'all' );
				wp_enqueue_style(
					'jms-covid-admin',
					JMS_COVID_19_CSS_URL . 'admin.css',
					array(
						'jms-covid-bootstrap',
						'jms_covid_semantic',
					),
					JMS_COVID_19_VERSION,
					'all'
				);
				wp_enqueue_style( 'wp-color-picker' );
				wp_register_script( 'jms-covid-bootstrap', JMS_COVID_19_JS_URL . 'bootstrap.min.js', array( 'jquery' ), '4.4.1', true );
				wp_register_script( 'jms_covid_semantic', JMS_COVID_19_JS_URL . 'semantic.min.js', array( 'jquery' ), '2.4.2', true );
				wp_enqueue_script(
					'jms-covid-admin',
					JMS_COVID_19_JS_URL . 'admin.min.js',
					array(
						'jquery',
						'jms-covid-bootstrap',
						'jms_covid_semantic',
					),
					JMS_COVID_19_VERSION,
					true
				);
				wp_enqueue_script( 'wp-color-picker' );
			}
			if ( in_array( $screen_id, array( 'widgets' ), true ) ) {
				wp_enqueue_style( 'jms-covid-select2', JMS_COVID_19_CSS_URL . 'select2.min.css', array(), '4.0.13', 'all' );
				wp_register_script( 'jms-covid-select2', JMS_COVID_19_JS_URL . 'select2.min.js', array( 'jquery' ), '4.0.13 ', true );
				wp_enqueue_script(
					'jms-covid-widgets',
					JMS_COVID_19_JS_URL . 'widgets.min.js',
					array(
						'jquery',
						'jms-covid-select2',
					),
					JMS_COVID_19_VERSION,
					true
				);
				wp_enqueue_style( 'jms-covid-widgets', JMS_COVID_19_CSS_URL . 'widgets.css', array(), JMS_COVID_19_VERSION, 'all' );
			}
		}

		/**
		 * Cron data
		 */
		public function jms_covid_19_cron_exec() {
			$this->get_data_covid();
		}

		/**
		 *  Get data covid 19
		 */
		public function get_data_covid() {
			$url   = esc_url( 'https://www.worldometers.info/coronavirus/' );
			$html  = $this->jms_file_get_html( $url );
			$table = $html->find( 'table#main_table_countries_today', 0 );

			$country = array();
			$data    = array();
			$keys    = array();

			foreach ( $table->find( 'thead tr th' ) as $e ) {
				$sanitize_title                   = sanitize_title( trim( $e->plaintext ) );
				$data['label'][ $sanitize_title ] = trim( $e->plaintext );
				$keys[]                           = $sanitize_title;
			}
			foreach ( $table->find( 'tbody tr' ) as $r => $row ) {
				foreach ( $row->find( 'td' ) as $i => $col ) {
					$country{$r}[ $keys[ $i ] ] = trim( $col->plaintext );
				}
			}
			$data['global']  = array_pop( $country );
			$data['country'] = $country;

			update_option( 'jms_covid_data', $data );
		}

		/**
		 * Jms file get html
		 *
		 * @param string $url Url.
		 * @param bool   $lowercase Lowercase . Default is true.
		 * @param bool   $force_tags_closed ForceTagsClosed . Default is true.
		 * @param string $target_charset DEFAULT_TARGET_CHARSET.
		 * @param bool   $strip_r_n stripRN . Default is true.
		 * @param string $default_b_r_text DEFAULT_BR_TEXT.
		 * @param string $default_span_text DEFAULT_SPAN_TEXT.
		 *
		 * @return bool|simple_html_dom
		 */
		private function jms_file_get_html(
			$url,
			$lowercase = true,
			$force_tags_closed = true,
			$target_charset = DEFAULT_TARGET_CHARSET,
			$strip_r_n = true,
			$default_b_r_text = DEFAULT_BR_TEXT,
			$default_span_text = DEFAULT_SPAN_TEXT ) {

			$dom = new simple_html_dom(
				null,
				$lowercase,
				$force_tags_closed,
				$target_charset,
				$strip_r_n,
				$default_b_r_text,
				$default_span_text
			);

			$response = wp_remote_get( esc_url( $url ) );
			if ( is_wp_error( $response ) ) {
				return false;
			}
			$contents = wp_remote_retrieve_body( $response );

			return $dom->load( $contents, $lowercase, $strip_r_n );
		}
	}
}


