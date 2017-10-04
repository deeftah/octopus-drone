<?php

/**
 * This code is inspired by WPML Widgets (https://wordpress.org/plugins/wpml-widgets/),
 * created by Jeroen Sormani
 *
 * @author OnTheGo Systems
 */
class WPML_Widgets_Support_Frontend implements IWPML_Action {
	private $current_language;

	/**
	 * WPML_Widgets constructor.
	 *
	 * @param string $current_language
	 */
	public function __construct( $current_language ) {
		$this->current_language = $current_language;
	}

	public function add_hooks() {
		add_filter( 'widget_display_callback', array( $this, 'display' ), 10, 1 );
	}

	/**
	 * @param array $instance
	 *
	 * @return bool|array
	 */
	public function display( $instance ) {
		if ( $this->it_must_display( $instance ) ) {
			return $instance;
		}

		return false;
	}

	/**
	 * @param array $instance
	 *
	 * @return bool
	 */
	private function it_must_display( $instance ) {
		return ! array_key_exists( 'wpml_language', $instance )
		       || $instance['wpml_language'] === $this->current_language
		       || 'all' === $instance['wpml_language'];
	}
}
