<?php
/**
 * JmsCovid Elementor Widget
 *
 * @class   JmsCovid_Elementor_Widget
 * @package jms-covid-19
 */

namespace Elementor;

if ( ! class_exists( 'JmsCovid_Elementor_Widget' ) ) {
	/**
	 * Class JmsCovid_Elementor_Widget
	 *
	 * @package Elementor
	 */
	class JmsCovid_Elementor_Widget extends Widget_Base {

		/**
		 * JmsCovid_Elementor_Widget constructor.
		 *
		 * @param array      $data Widget data. Default is an empty array.
		 * @param array|null $args Optional. Widget default arguments. Default is null.
		 */
		public function __construct( $data = array(), $args = null ) {
			parent::__construct( $data, $args );

			wp_register_script( 'jms-hammer', JMS_COVID_19_JS_URL . 'hammer.min.js', array( 'jquery' ), JMS_COVID_19_VERSION, true );
			wp_register_script( 'jms-svg-pan-zoom', JMS_COVID_19_JS_URL . 'svg-pan-zoom.min.js', array( 'jquery', 'jms-hammer' ), JMS_COVID_19_VERSION, true );
			wp_register_script( 'jms-highcharts', JMS_COVID_19_JS_URL . 'highcharts.js', array( 'jquery' ), JMS_COVID_19_VERSION, true );
			wp_register_script( 'script-handle', JMS_COVID_19_JS_URL . 'jms-covid-elementor.min.js', array( 'elementor-frontend', 'jms-highcharts', 'jms-svg-pan-zoom' ), '1.0.0', true );
		}

		/**
		 * Get script depends.
		 *
		 * @return array
		 */
		public function get_script_depends() {
			if ( Plugin::$instance->preview->is_preview_mode() ) {
				return array( 'script-handle' );
			}
			return array();
		}

		/**
		 * Get name.
		 *
		 * @return string
		 */
		public function get_name() {
			return 'jms_covid';
		}

		/**
		 * Get title.
		 *
		 * @return string|void
		 */
		public function get_title() {
			return __( 'JMS Covid 19', 'jms-covid-19' );
		}

		/**
		 * Get Icon.
		 *
		 * @return string
		 */
		public function get_icon() {
			return 'eicon-global-settings';
		}

		/**
		 * Get categories.
		 *
		 * @return array
		 */
		public function get_categories() {
			return array( 'jms' );
		}


		/**
		 * Register control.
		 */
		protected function _register_controls() {
			$data             = get_option( 'jms_covid_data', array() );
			$option_countries = array();
			if ( isset( $data['country'] ) ) {
				$option_countries = wp_list_pluck( $data['country'], 'country' );
			}
			$this->start_controls_section(
				'content_section',
				array(
					'label' => __( 'General', 'jms-covid-19' ),
					'tab'   => Controls_Manager::TAB_CONTENT,
				)
			);

			$this->add_control(
				'style',
				array(
					'label'       => __( 'Style', 'jms-covid-19' ),
					'type'        => Controls_Manager::SELECT,
					'options'     => array(
						'1' => __( 'Style 1', 'jms-covid-19' ),
						'2' => __( 'Style 2', 'jms-covid-19' ),
						'3' => __( 'Style 3', 'jms-covid-19' ),
						'4' => __( 'Style 4', 'jms-covid-19' ),
						'5' => __( 'Style 5', 'jms-covid-19' ),
					),
					'default'     => '1',
					'label_block' => true,
				)
			);
			$this->add_control(
				'country',
				array(
					'label'       => __( 'Country', 'jms-covid-19' ),
					'type'        => Controls_Manager::SELECT2,
					'options'     => array_combine( $option_countries, $option_countries ),
					'default'     => '',
					'label_block' => true,
					'condition'   => array(
						'style' => array( '1', '3' ),
					),
				)
			);
			$this->add_control(
				'countries',
				array(
					'label'       => __( 'Countries', 'jms-covid-19' ),
					'type'        => Controls_Manager::SELECT2,
					'options'     => array_combine( $option_countries, $option_countries ),
					'default'     => '',
					'label_block' => true,
					'multiple'    => true,
					'condition'   => array(
						'style' => '2',
					),
				)
			);
			$this->add_control(
				'title',
				array(
					'label'       => __( 'Title', 'jms-covid-19' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => __( 'Title', 'jms-covid-19' ),
					'label_block' => true,
					'condition'   => array(
						'style' => array( '1', '2', '3', '5' ),
					),
				)
			);
			$this->end_controls_section();
		}

		/**
		 * Render.
		 */
		protected function render() {
			$settings = $this->get_settings_for_display();
			if ( $settings['style'] ) {
				$this->add_render_attribute( 'shortcode', 'style', $settings['style'] );
			}

			if ( $settings['country'] ) {
				$this->add_render_attribute( 'shortcode', 'country', $settings['country'] );
			}

			if ( $settings['countries'] ) {
				$this->add_render_attribute( 'shortcode', 'country', implode( ',', $settings['countries'] ) );
			}

			if ( $settings['title'] ) {
				$this->add_render_attribute( 'shortcode', 'title', $settings['title'] );
			}
			echo do_shortcode( '[jms-covid19 ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );

		}
	}

	Plugin::instance()->widgets_manager->register_widget_type( new JmsCovid_Elementor_Widget() );
}
