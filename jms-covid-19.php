<?php
/**
 * Covid-19 Live Tracking
 *
 * @package  jms-covid-19
 *
 * Plugin Name: Covid-19 Live Tracking 1
 * Plugin URI: https://jmsthemes.com/product/covid-19-live-tracking/
 * Description: COVID-19 Coronavirus Live Tracking.
 * Version: 1.0.0
 * Author: Joommasters
 * Author URI: http://joommasters.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: jms-covid-19
 * Domain Path:  /languages/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

define( 'JMS_COVID_19_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'JMS_COVID_19_URL', plugin_dir_url( __FILE__ ) );
define( 'JMS_COVID_19_CSS_URL', JMS_COVID_19_URL . 'css/' );
define( 'JMS_COVID_19_JS_URL', JMS_COVID_19_URL . 'js/' );
define( 'JMS_COVID_19_IMAGES_URL', JMS_COVID_19_URL . 'images/' );
define( 'JMS_COVID_19_ADMIN_PATH', JMS_COVID_19_PLUGIN_PATH . 'admin/' );
define( 'JMS_COVID_19_VERSION', '1.0.0' );

require_once 'admin/class-jmscovid-params.php';
require_once 'admin/class-jmscovid-elementor.php';
require_once 'admin/class-jmscovid-admin.php';
require_once 'front/class-jmscovid-widget.php';
require_once 'front/class-jmscovid-front.php';

register_activation_hook( __FILE__, 'jms_covid_19_activate' );

/**
 * Activation
 */
function jms_covid_19_activate() {
	global $wp_version;
	if ( version_compare( $wp_version, '5.0', '<' ) ) {
		deactivate_plugins( basename( __FILE__ ) );
		wp_die( 'This plugin requires WordPress version 5.0 or higher.' );
	}
	$json_data = '{"data_source":1,"style1_title_color":"#1a1a1a","style1_title_size":20,"style1_text_size":16,"style1_confirmed_color":"#DE3700","style1_death_color":"#767676","style1_recovered_color":"#60bb69","style2_title_color":"#1a1a1a","style2_title_size":20,"style2_global_title_color":"#1a1a1a","style2_global_title_size":16,"style2_subtitle_color":"#1a1a1a","style2_subtitle_size":16,"style2_confirmed_color":"#de3700","style2_active_color":"#f4c363","style2_death_color":"#767676","style2_recovered_color":"#60bb69","style2_bg_tab":"#ffffff","style2_tab_text_color":"#000000","style2_tab_text_size":16,"style2_bg_tab_active":"#65b3c4","style2_tab_active_text_color":"#ffffff","style3_show_title":1,"style3_title_color":"#1a1a1a","style3_title_size":18,"style3_text_color":"#8224e3","style3_text_size":16,"style3_confirmed_color":"#de3700","style3_active_color":"#dd9933","style3_death_color":"#767676","style3_recovered_color":"#60bb69","style3_tab_text_color":"#1e73be","style3_tab_active_text_color":"#000000","style4_text_color":"#1a1a1a","style4_text_size":14,"style4_column_heading_color":"#1a1a1a","style4_column_heading_font_size":16,"style4_pagination_active_bg_color":"#1e73be","style4_pagination_active_text_color":"#ffffff","style4_pagination_text_font_size":14,"style4_data_show":["countryother","totalcases","totaldeaths","totalrecovered"],"style5_title_color":"#333333","style5_title_size":18,"style5_background_color":"#efefef","style5_country_color":"#44aaff","style5_country_border_color":"#ffffff","style5_country_border_color_hover":"#333333","style5_country_confirm_color":"#ff0e19","country_comfirm_color_type":2,"number_of_case":[10,100,1000,10000,100000],"color_by_number_case":["#ffa8ab","#f78287","#fc3942","#fe212b","#ff0e19"]}';
	if ( ! get_option( 'jms_covid_params' ) ) {
		update_option( 'jms_covid_params', json_decode( $json_data, true ) );
	}

	if ( ! wp_next_scheduled( 'jms_check_covid_19' ) ) {
		wp_schedule_event( time(), 'thirty_minutes', 'jms_check_covid_19' );
	}
}

register_deactivation_hook( __FILE__, 'jms_covid_19_deactivation' );
/**
 * Deactivation
 */
function jms_covid_19_deactivation() {
	wp_clear_scheduled_hook( 'jms_check_covid_19' );
}

add_filter( 'cron_schedules', 'jms_covid_19_add_cron_interval' );

/**
 * Adds a custom cron schedule for every 10 minutes.
 *
 * @param array $schedules An array of non-default cron schedules.
 *
 * @return array Filtered array of non-default cron schedules.
 */
function jms_covid_19_add_cron_interval( $schedules ) {
	$schedules['thirty_minutes'] = array(
		'interval' => 600,
		'display'  => esc_html__( 'Every 10 minutes', 'jms-covid-19' ),
	);

	return $schedules;
}

new JmsCovid_Admin();
new JmsCovid_Params();
new JmsCovid_Widget();
new JmsCovid_Front();
