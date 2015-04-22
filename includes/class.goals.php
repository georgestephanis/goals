<?php

namespace GeorgeStephanis\Goals;

class Goals {

	const POST_TYPE = 'goals';

	private static $instance;

	/**
	 * Yay singletons.
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Make __construct() a private so it must be instantiated through `self::get_instance()`;
	 */
	private function __construct() {
		
	}

	public static function register_post_types() {
		
	}

	public static function register_taxonomies() {
		
	}

	public static function register_shortcodes() {
		
	}

	public static function register_widgets() {
		
	}

}
