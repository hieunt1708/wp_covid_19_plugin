<?php
/**
 * JmsCovid Elementor
 *
 * @class   JmsCovid_Elementor
 * @package jms-covid-19
 */

if ( ! class_exists( 'JmsCovid_Elementor' ) && did_action( 'elementor/loaded' ) ) {
	/**
	 * Class JmsCovid_Elementor
	 */
	class JmsCovid_Elementor {
		/**
		 * Instance.
		 *
		 * @var string
		 */
		protected static $instance;

		/**
		 * JmsCovid_Elementor constructor.
		 */
		public function __construct() {
			add_action(
				'elementor/elements/categories_registered',
				array(
					$this,
					'jms_covid_add_elementor_widget_categories',
				)
			);
			add_action( 'elementor/widgets/widgets_registered', array( $this, 'jms_covid_init_elementor_widgets' ) );
		}


		/**
		 * Add elementor widget categories
		 *
		 * @param object $elements_manager Elements manager categories.
		 */
		public function jms_covid_add_elementor_widget_categories( $elements_manager ) {
			$elements_manager->add_category(
				'jms',
				array(
					'title' => __( 'Jms', 'jms-covid-19' ),
					'icon'  => 'fa fa-plug',
				)
			);
		}

		/**
		 * Init elementor widgets
		 */
		public function jms_covid_init_elementor_widgets() {
			require_once __DIR__ . '/widgets/class-jmscovid-elementor-widget.php';
		}


		/**
		 * JmsCovid_Elementor
		 *
		 * @return JmsCovid_Elementor|string
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
	}

	JmsCovid_Elementor::get_instance();
}
