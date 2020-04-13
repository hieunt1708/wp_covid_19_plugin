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
			$request_1   = 'https://corona.lmao.ninja/countries';
			$request_2   = 'https://corona.lmao.ninja/all';
			$response_1 = wp_remote_get( esc_url( $request_1 ) );
			$response_2 = wp_remote_get( esc_url( $request_2 ) );

			if ( is_wp_error( $response_1 ) || is_wp_error( $response_2)) {
				return false;
			}

			$results_1 = json_decode( wp_remote_retrieve_body( $response_1 ), true );
			$results_2 = json_decode( wp_remote_retrieve_body( $response_2 ), true );

			if ( ! is_array( $results_1 ) || ! is_array( $results_2 )) {
				return false;
			}else{
				$data['label'] = [
					'country' => __('Country, Other','jms-covid-19'),
					'cases' => __('Total Cases','jms-covid-19'),
					'todayCases' => __('New Cases','jms-covid-19'),
					'deaths' => __('Total Deaths','jms-covid-19'),
					'todayDeaths' => __('New Cases','jms-covid-19'),
					'recovered' => __('Total Recovered','jms-covid-19'),
					'active' => __('Active Cases','jms-covid-19'),
					'critical' => __('Serious, Critical','jms-covid-19'),
					'casesPerOneMillion' => __('Tot Cases / 1M pop','jms-covid-19'),
					'deathsPerOneMillion' => __('Deaths/ 1M pop','jms-covid-19'),
				];
				$data['country'] = $results_1;
				$data['global'] = $results_2;
				$data['global']['country'] = __( 'Global', 'jms-covid-19' );
				update_option( 'jms_covid_data', $data );
			}
		}

	}
}


