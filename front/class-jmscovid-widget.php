<?php
/**
 * JmsCovid Widget
 *
 * @class   JmsCovid_Widget
 * @package jms-covid-19
 */

if ( ! class_exists( 'JmsCovid_Widget' ) ) {
	/**
	 * Class JmsCovid_Widget
	 */
	class JmsCovid_Widget extends WP_Widget {

		/**
		 * JmsCovid_Widget constructor.
		 */
		public function __construct() {
			$widget_ops = array(
				'classname'   => 'widget_jms_covid_19',
				'description' => 'JMS Covid 19',
			);
			parent::__construct( 'widget_jms_covid_19', esc_html__( 'JMS Covid 19', 'jms-covid-19' ), $widget_ops );
		}


		/**
		 * Outputs the content for the current JMS Covid widget instance.
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title',
		 *                        'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the current Pages widget instance.
		 */
		public function widget( $args, $instance ) {

			$style   = ! empty( $instance['style'] ) ? $instance['style'] : '';
			$country = ! empty( $instance['country'] ) ? $instance['country'] : array( 'global' );
			$country = implode( ',', $country );
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}
			echo do_shortcode( "[jms-covid19 style={$style} country={$country} ]" );
			echo $args['after_widget'];
		}

		/**
		 * Handles updating settings for the current JMS Covid widget instance.
		 *
		 * @param array $new_instance New settings for this instance as input by the user via
		 *                            WP_Widget::form().
		 * @param array $old_instance Old settings for this instance.
		 * @return array Updated settings to save.
		 */
		public function update( $new_instance, $old_instance ) {
			parent::update( $new_instance, $old_instance );

			$instance = $old_instance;

			$instance['title']   = wp_strip_all_tags( $new_instance['title'] );
			$instance['style']   = wp_strip_all_tags( $new_instance['style'] );
			$instance['country'] = esc_sql( $new_instance['country'] );

			return $instance;
		}

		/**
		 * Outputs the settings form for the JMS Covid widget.
		 *
		 * @param array $instance Current settings.
		 */
		public function form( $instance ) {
			$instance         = wp_parse_args(
				(array) $instance,
				array(
					'title' => esc_html__( 'Covid 19', 'jms-covid-19' ),
					'style' => '',
				)
			);
			$country          = ! empty( $instance['country'] ) ? (array) $instance['country'] : array();
			$data             = get_option( 'jms_covid_data', array() );
			$option_countries = array();
			if ( isset( $data['country'] ) ) {
				$option_countries = wp_list_pluck( $data['country'], 'country' );
			}
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'jms-covid-19' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php esc_html_e( 'Style:', 'jms-covid-19' ); ?></label>
				<select class="widefat jms-covid-style" id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>">
					<?php
					for ( $i = 1; $i <= 5; ++ $i ) {
						/* translators: %d: $i */
						echo "<option value='" . esc_attr( $i ) . "' " . selected( $instance['style'], $i, false ) . '>' . esc_html( sprintf( __( 'Style %d', 'jms-covid-19' ), $i ) ) . '</option>';
					}
					?>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'country' ) ); ?>" style="display: block;"><?php esc_html_e( 'Country:', 'jms-covid-19' ); ?></label>
				<select class="widefat jms-covid-country" id="<?php echo esc_attr( $this->get_field_id( 'country' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'country' ) ); ?>[]"
					<?php
					if ( '2' === $instance['style'] ) {
						echo 'multiple ';}
					?>
					>
					<option value=""><?php esc_html_e( 'Global', 'jms-covid-19' ); ?></option>
					<?php
					$selected = '';
					foreach ( $option_countries as $val ) {
						$selected = in_array( $val, $country, true ) ? 'selected="selected"' : '';
						echo "<option value='" . esc_attr( $val ) . "' " . esc_attr( $selected ) . ' >' . esc_html( $val ) . '</option>';
					}
					?>
				</select></p>
			<?php

		}
	}
}
