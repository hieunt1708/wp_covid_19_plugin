<?php
/**
 * JmsCovid Params.
 *
 * @class   JmsCovid_Params
 * @package jms-covid-19
 */

if ( ! class_exists( 'JmsCovid_Params' ) ) {
	/**
	 * Class JmsCovid_Params
	 */
	class JmsCovid_Params {
		/**
		 * Option.
		 *
		 * @var string
		 */
		public static $params;

		/**
		 * Messages.
		 *
		 * @var array
		 */
		private static $messages = array();

		/**
		 * Errors.
		 *
		 * @var array
		 */
		private static $errors = array();

		/**
		 * JmsCovid_Params constructor.
		 */
		public function __construct() {
			add_action( 'admin_init', array( $this, 'save_params_trigger' ) );
		}

		/**
		 * Add message error
		 *
		 * @param string $text Message.
		 */
		public static function add_error( $text ) {
			self::$errors[] = $text;
		}


		/**
		 * Params Form
		 */
		public static function params_form() {
			self::$params = get_option( 'jms_covid_params', array() );
			$data         = get_option( 'jms_covid_data', array() );

			?>
			<div class="wrap jms-notification">
				<h2><?php esc_attr_e( 'JMS Covid Settings', 'jms_covid' ); ?></h2>
				<?php self::show_messages(); ?>
				<form method="post" action="" class="ui form mt-3">
					<?php wp_nonce_field( '_jms_covid_setting_form_', '_jms_covid_nonce' ); ?>
					<ul class="nav nav-tabs">
						<li class="nav-item">
							<a class="nav-link active" data-toggle="tab" href="#general"><?php esc_html_e( 'General', 'jms-covid-19' ); ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#style1"><?php esc_html_e( 'Style 1', 'jms-covid-19' ); ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#style2"><?php esc_html_e( 'Style 2', 'jms-covid-19' ); ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#style3"><?php esc_html_e( 'Style 3', 'jms-covid-19' ); ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#style4"><?php esc_html_e( 'Style 4', 'jms-covid-19' ); ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#style5"><?php esc_html_e( 'Style 5', 'jms-covid-19' ); ?></a>
						</li>
					</ul>
					<div class="tab-content">
						<div id="general" class="tab-pane fade in active show">
							<div class="form-group row">
								<label class="col-sm-2 col-form-label-sm"><?php esc_html_e( 'Data Sources', 'jms-covid-19' ); ?></label>
								<div class="col-sm-10">
									<div class="custom-control custom-radio">
										<input type="radio" disabled id="customRadio1" name="<?php echo esc_attr( self::gen_param_name( 'data_source' ) ); ?>" class="d-none custom-control-input" value="1" <?php checked( self::get_param( 'data_source', '1' ), 1 ); ?>>
										<label class="custom-control-label-jms" for="customRadio1">
											<?php
											$lbale_1 = sprintf(
												/* translators: %s: Link Worldometers*/
												__( 'Worldometer - <a href="$s" target="_blank">www.worldometers.info</a>.', 'jmstravel' ),
												esc_url( 'https://www.worldometers.info/coronavirus/' )
											);
											printf( $lbale_1 );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											?>
										</label>
									</div>
									<div class="custom-control custom-radio">
										<input type="radio" disabled id="customRadio2" name="<?php echo esc_attr( self::gen_param_name( 'data_source' ) ); ?>" class="d-none custom-control-input" value="2" <?php checked( self::get_param( 'data_source', '1' ), 2 ); ?>>
										<label class="custom-control-label-jms" for="customRadio2">
											<?php
											$lbale_2 = sprintf(
												/* translators: %s: Link CSSEGISandData*/
												__( 'CSSEGISandData - <a href="$s" target="_blank">Source</a>.', 'jmstravel' ),
												esc_url( 'https://github.com/CSSEGISandData/COVID-19' )
											);
											printf( $lbale_2 );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											?>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div id="style1" class="tab-pane fade">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Title Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style1_title_color', '#1a1a1a' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style1_title_color' ) ); ?>" class="color-field" data-default-color="#1a1a1a"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Title Font Size', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input id="<?php echo esc_attr( self::gen_param_name( 'style1_title_size' ) ); ?>" type="number" tabindex="0" value="<?php echo esc_attr( self::get_param( 'style1_title_size', 20 ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style1_title_size' ) ); ?>"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Text Font Size', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input id="<?php echo esc_attr( self::gen_param_name( 'style1_text_size' ) ); ?>" type="number" tabindex="0" value="<?php echo esc_attr( self::get_param( 'style1_text_size', 16 ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style1_text_size' ) ); ?>"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'C(confirm) Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style1_confirmed_color', '#DE3700' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style1_confirmed_color' ) ); ?>" class="color-field" data-default-color="#767676"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'D(deaths) Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style1_death_color', '#767676' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style1_death_color' ) ); ?>" class="color-field" data-default-color="#767676"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'R(recovered)  Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style1_recovered_color ', '#60bb69' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style1_recovered_color' ) ); ?>" class="color-field" data-default-color="#60bb69"/>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<img src="<?php echo esc_url( JMS_COVID_19_IMAGES_URL . 'style-1.png' ); ?>" class="img-fluid" alt="Style 1">
									<pre><code>[jms-covid19 style='1' country='Italy' title='Italy Covid 19']</code></pre>
									<p>Shortcode Attributes:</p>
									<ul class="list-group list-group-flush">
										<li class="list-group-item border-0"><code>country</code> – Live statistics tracking the number of confirmed cases, recovered and deaths by country. If country empty show global.</li>
										<li class="list-group-item border-0"><code>title</code> – The title box . The default is attribute “country” or “Global” .</li>
									</ul>
								</div>
							</div>
						</div>
						<div id="style2" class="tab-pane fade">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Title Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style2_title_color', '#1a1a1a' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style2_title_color' ) ); ?>" class="color-field" data-default-color="#1a1a1a"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Title Font Size', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input id="<?php echo esc_attr( self::gen_param_name( 'style2_title_size' ) ); ?>" type="number" tabindex="0" value="<?php echo esc_attr( self::get_param( 'style2_title_size', 20 ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style2_title_size' ) ); ?>"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Global Title Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style2_global_title_color', '#1a1a1a' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style2_global_title_color' ) ); ?>" class="color-field" data-default-color="#1a1a1a"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Global Title Font Size', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input id="<?php echo esc_attr( self::gen_param_name( 'style2_global_title_size' ) ); ?>" type="number" tabindex="0" value="<?php echo esc_attr( self::get_param( 'style2_global_title_size', 16 ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style2_global_title_size' ) ); ?>"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'SubTitle Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style2_subtitle_color', '#1a1a1a' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style2_subtitle_color' ) ); ?>" class="color-field" data-default-color="#1a1a1a"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'SubTitle Font Size', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input id="<?php echo esc_attr( self::gen_param_name( 'style2_subtitle_size' ) ); ?>" type="number" tabindex="0" value="<?php echo esc_attr( self::get_param( 'style2_subtitle_size', 16 ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style2_subtitle_size' ) ); ?>"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'C(confirm) Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style2_confirmed_color', '#DE3700' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style2_confirmed_color' ) ); ?>" class="color-field" data-default-color="#DE3700"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'A(active) Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style2_active_color', '#f4c363' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style2_active_color' ) ); ?>" class="color-field" data-default-color="#f4c363"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'D(deaths) Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style2_death_color', '#767676' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style2_death_color' ) ); ?>" class="color-field" data-default-color="#767676"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'R(recovered)  Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style2_recovered_color ', '#60bb69' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style2_recovered_color' ) ); ?>" class="color-field" data-default-color="#60bb69"/>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Tab Background Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style2_bg_tab', '#ffffff' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style2_bg_tab' ) ); ?>" class="color-field" data-default-color="#ffffff"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Tab Text Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style2_tab_text_color', '#000000' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style2_tab_text_color' ) ); ?>" class="color-field" data-default-color="#000000"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Tab Text Font Size', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input id="<?php echo esc_attr( self::gen_param_name( 'style2_tab_text_size' ) ); ?>" type="number" tabindex="0" value="<?php echo esc_attr( self::get_param( 'style2_tab_text_size', 16 ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style2_tab_text_size' ) ); ?>"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Tab Active Background Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style2_bg_tab_active', '#65b3c4' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style2_bg_tab_active' ) ); ?>" class="color-field" data-default-color="#65b3c4"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Tab Actice Text Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style2_tab_active_text_color', '#ffffff' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style2_tab_active_text_color' ) ); ?>" class="color-field" data-default-color="#ffffff"/>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<img src="<?php echo esc_url( JMS_COVID_19_IMAGES_URL . 'style-2.png' ); ?>" class="img-fluid" alt="Style 1">
									<pre><code>[jms-covid19 style='2' country='USA,Italy,S.Korea,Vietnam' title='Covid 19']</code></pre>
									<p>Shortcode Attributes:</p>
									<ul class="list-group list-group-flush">
										<li class="list-group-item border-0"><code>country</code> – Live statistics tracking the number of confirmed cases, recovered and deaths by country. If country empty show global.</li>
										<li class="list-group-item border-0"><code>title</code> – The title box .</li>
									</ul>
								</div>
							</div>
						</div>
						<div id="style3" class="tab-pane fade">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Show title', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<div class="ui toggle checkbox">
												<input id="<?php echo esc_attr( self::gen_param_name( 'style3_show_title' ) ); ?>" type="checkbox" <?php checked( self::get_param( 'style3_show_title' ), 1 ); ?>value="1" name="<?php echo esc_attr( self::gen_param_name( 'style3_show_title' ) ); ?>">
												<label></label>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Title Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style3_title_color', '#1a1a1a' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style3_title_color' ) ); ?>" class="color-field" data-default-color="#1a1a1a"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Title Font Size', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input id="<?php echo esc_attr( self::gen_param_name( 'style3_title_size' ) ); ?>" type="number" tabindex="0" value="<?php echo esc_attr( self::get_param( 'style3_title_size', 20 ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style3_title_size' ) ); ?>"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Text Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style3_text_color', '#1a1a1a' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style3_text_color' ) ); ?>" class="color-field" data-default-color="#1a1a1a"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Text Font Size', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input id="<?php echo esc_attr( self::gen_param_name( 'style3_text_size' ) ); ?>" type="number" tabindex="0" value="<?php echo esc_attr( self::get_param( 'style3_text_size', 16 ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style3_text_size' ) ); ?>"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'C(confirm) Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style3_confirmed_color', '#DE3700' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style3_confirmed_color' ) ); ?>" class="color-field" data-default-color="#DE3700"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'A(active) Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style3_active_color', '#f4c363' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style3_active_color' ) ); ?>" class="color-field" data-default-color="#f4c363"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'D(deaths) Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style3_death_color', '#767676' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style3_death_color' ) ); ?>" class="color-field" data-default-color="#767676"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'R(recovered)  Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style3_recovered_color ', '#60bb69' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style3_recovered_color' ) ); ?>" class="color-field" data-default-color="#60bb69"/>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Tab Text Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style3_tab_text_color', '#000000' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style3_tab_text_color' ) ); ?>" class="color-field" data-default-color="#000000"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Tab Actice Text Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style3_tab_active_text_color', '#000000' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style3_tab_active_text_color' ) ); ?>" class="color-field" data-default-color="#000000"/>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<img src="<?php echo esc_url( JMS_COVID_19_IMAGES_URL . 'style-3.png' ); ?>" class="img-fluid" alt="Style 1">
									<pre><code>[jms-covid19 style='3' country='Vietnam']</code></pre>
									<p>Shortcode Attributes:</p>
									<ul class="list-group list-group-flush">
										<li class="list-group-item border-0"><code>country</code> – Live statistics tracking the number of confirmed cases, recovered and deaths by country.</li>
									</ul>
								</div>
							</div>
						</div>
						<div id="style4" class="tab-pane fade">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Text Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style4_text_color', '#1a1a1a' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style4_text_color' ) ); ?>" class="color-field" data-default-color="#1a1a1a"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Text Font Size', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input id="<?php echo esc_attr( self::gen_param_name( 'style4_text_size' ) ); ?>" type="number" tabindex="0" value="<?php echo esc_attr( self::get_param( 'style4_text_size', 16 ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style4_text_size' ) ); ?>"/>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Column Heading Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style4_column_heading_color', '#1a1a1a' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style4_column_heading_color' ) ); ?>" class="color-field" data-default-color="#1a1a1a"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Column Heading Font Size', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input id="<?php echo esc_attr( self::gen_param_name( 'style4_column_heading_font_size' ) ); ?>" type="number" tabindex="0" value="<?php echo esc_attr( self::get_param( 'style4_column_heading_font_size', 16 ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style4_column_heading_font_size' ) ); ?>"/>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Pagination Active Background Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style4_pagination_active_bg_color', '#dcdcdc' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style4_pagination_active_bg_color' ) ); ?>" class="color-field" data-default-color="#dcdcdc"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Pagination Active Text Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style4_pagination_active_text_color', '#333333' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style4_pagination_active_text_color' ) ); ?>" class="color-field" data-default-color="#333333"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Pagination Font Size', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input id="<?php echo esc_attr( self::gen_param_name( 'style4_pagination_text_font_size' ) ); ?>" type="number" tabindex="0" value="<?php echo esc_attr( self::get_param( 'style4_pagination_text_font_size', 16 ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style4_pagination_text_font_size' ) ); ?>"/>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Datatables Show', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<?php foreach ( $data['label'] as $k => $val ) : ?>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" name="<?php echo esc_attr( self::gen_param_name( 'style4_data_show' ) ); ?>[]" class="custom-control-input" id="<?php echo esc_attr( $k ); ?>" value="<?php echo esc_attr( $k ); ?>" 
																							<?php
																							if ( in_array( $k, (array) self::get_param( 'style4_data_show' ), true ) ) {
																								echo ' checked ';}
																							?>
													 >
													<label class="custom-control-label" for="<?php echo esc_attr( $k ); ?>"><?php echo esc_html( $val ); ?></label>
												</div>
											<?php endforeach; ?>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<img src="<?php echo esc_url( JMS_COVID_19_IMAGES_URL . 'style-4.png' ); ?>" class="img-fluid" alt="Style 1">
									<pre><code>[jms-covid19 style='4']</code></pre>
								</div>
							</div>
						</div>
						<div id="style5" class="tab-pane fade">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Title Color', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style5_title_color', '#333333' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style5_title_color' ) ); ?>" class="color-field" data-default-color="#333333"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Title Font Size', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input id="<?php echo esc_attr( self::gen_param_name( 'style5_title_size' ) ); ?>" type="number" tabindex="0" value="<?php echo esc_attr( self::get_param( 'style5_title_size', 18 ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style5_title_size' ) ); ?>"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Color for background', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style5_background_color', '#efefef' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style5_background_color' ) ); ?>" class="color-field" data-default-color="#efefef"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Color for country', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style5_country_color', '#44aaff' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style5_country_color' ) ); ?>" class="color-field" data-default-color="#44aaff"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Border Color for country', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style5_country_border_color', '#ffffff' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style5_country_border_color' ) ); ?>" class="color-field" data-default-color="#ffffff"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Border Color on hover', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style5_country_border_color_hover', '#333333' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style5_country_border_color_hover' ) ); ?>" class="color-field" data-default-color="#333333"/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Color type for country have confirm case', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<div class="custom-control custom-radio">
												<input type="radio" id="customType1" name="<?php echo esc_attr( self::gen_param_name( 'country_comfirm_color_type' ) ); ?>" class="custom-control-input" value="1" <?php checked( self::get_param( 'country_comfirm_color_type', '1' ), 1 ); ?>>
												<label class="custom-control-label" for="customType1"><?php esc_html_e( 'Opacity', 'jms-covid-19' ); ?></label>
											</div>
											<div class="custom-control custom-radio">
												<input type="radio" id="customType2" name="<?php echo esc_attr( self::gen_param_name( 'country_comfirm_color_type' ) ); ?>" class="custom-control-input" value="2" <?php checked( self::get_param( 'country_comfirm_color_type', '1' ), 2 ); ?>>
												<label class="custom-control-label" for="customType2"><?php esc_html_e( 'Number of case', 'jms-covid-19' ); ?></label>
											</div>
										</div>
									</div>
									<div class="form-group row form_color_opacity">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Color for country have confirm case', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<input type="text" value="<?php echo esc_attr( self::get_param( 'style5_country_confirm_color', '#ff0e19' ) ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'style5_country_confirm_color' ) ); ?>" class="color-field" data-default-color="#ff0e19"/>
										</div>
									</div>
									<div class="form-group row form_color_nnumber_case">
										<label class="col-sm-4 col-form-label-sm"><?php esc_html_e( 'Color for country by number of confirm case', 'jms-covid-19' ); ?></label>
										<div class="col-sm-8">
											<?php
											$number_value         = array();
											$color_value          = array();
											$number_of_case       = self::get_param( 'number_of_case' );
											$color_by_number_case = self::get_param( 'color_by_number_case' );
											for ( $i = 0; $i < 5; ++ $i ) :
												$number_value[ $i ] = ( is_array( $number_of_case ) && ! empty( $number_of_case[ $i ] ) ) ? $number_of_case[ $i ] : intval( '1e' . ( $i + 1 ) );
												$color_value[ $i ]  = ( is_array( $color_by_number_case ) && ! empty( $color_by_number_case[ $i ] ) ) ? $color_by_number_case[ $i ] : '#ff0e19';
												?>
												<div class="form-group d-inline-flex align-items-center">
													<span class="mr-sm-2"><?php echo esc_html( '>' ); ?></span>
													<input class="mr-sm-2" type="number" value="<?php echo esc_attr( $number_value[ $i ] ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'number_of_case', true ) ); ?>"/>
													<input type="text" value="<?php echo esc_attr( $color_value[ $i ] ); ?>" name="<?php echo esc_attr( self::gen_param_name( 'color_by_number_case', true ) ); ?>" class="color-field" data-default-color="#ff0e19"/>
												</div>
											<?php endfor; ?>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<img src="<?php echo esc_url( JMS_COVID_19_IMAGES_URL . 'style-5.png' ); ?>" class="img-fluid" alt="Style 5">
									<pre><code>[jms-covid19 style='6' title="Covid 19 Map"]</code></pre>
									<p>Shortcode Attributes:</p>
									<ul class="list-group list-group-flush">
										<li class="list-group-item border-0"><code>title</code> – The title box . The default is empty .</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<p style="position: relative; z-index: 999; margin: 25px 0; display: inline-block;">
						<input type="submit" class="h-100 ui button primary" value=" <?php esc_html_e( 'Save', 'jms-covid-19' ); ?> "/>
					</p>
				</form>
			</div>
			<?php
		}


		/**
		 * Show messages
		 */
		public static function show_messages() {
			if ( count( self::$errors ) > 0 ) {
				foreach ( self::$errors as $error ) {
					echo '<div id="message" class="error inline"><p><strong>' . esc_html( $error ) . '</strong></p></div>';
				}
			} elseif ( count( self::$messages ) > 0 ) {
				echo '<div id="message" class="updated inline"><p><strong>' . esc_html( self::$messages[0] ) . '</strong></p></div>';
			}
		}

		/**
		 * Gen param name
		 *
		 * @param string $_param Array option setting.
		 * @param bool   $multi True/False array data.
		 *
		 * @return string
		 */
		protected static function gen_param_name( $_param, $multi = false ) {
			if ( $_param ) {
				if ( $multi ) {
					return 'jms_covid_params[' . $_param . '][]';
				} else {
					return 'jms_covid_params[' . $_param . ']';
				}
			}

			return '';
		}


		/**
		 * Get Param
		 *
		 * @param string|array $_param Array option setting.
		 * @param string       $default Default value.
		 *
		 * @return string
		 */
		public static function get_param( $_param, $default = '' ) {
			$params = get_option( 'jms_covid_params', array() );
			if ( self::$params ) {
				$params = self::$params;
			} else {
				self::$params = $params;
			}
			if ( isset( $params[ $_param ] ) && $_param ) {
				return $params[ $_param ];
			} else {
				return $default;
			}
		}


		/**
		 * Save option
		 *
		 * @return bool
		 */
		public function save_params_trigger() {
			if ( ! current_user_can( 'manage_options' ) || ! isset( $_POST['_jms_covid_nonce'] ) || ! isset( $_POST['jms_covid_params'] ) ) {
				return false;
			}

			if ( isset( $_POST['jms_covid_params'], $_POST['_jms_covid_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['_jms_covid_nonce'] ), '_jms_covid_setting_form_' ) ) {
				$params = $this->jms_covid_clean( wp_unslash( $_POST['jms_covid_params'] ) ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				update_option( 'jms_covid_params', $params );
				self::add_message( __( 'Your settings have been saved.', 'jms-covid-19' ) );
			}
		}

		/**
		 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
		 *
		 * @param string|array $var Data to sanitize.
		 *
		 * @return string|array
		 */
		private function jms_covid_clean( $var ) {
			if ( is_array( $var ) ) {
				return array_map( array( $this, 'jms_covid_clean' ), $var );
			} else {
				return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
			}
		}

		/**
		 * Add message
		 *
		 * @param string $text message.
		 */
		public static function add_message( $text ) {
			self::$messages[] = $text;
		}
	}
}
?>
