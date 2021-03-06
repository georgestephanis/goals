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
		add_action( 'init',         array( __CLASS__, 'register_scripts_styles' ) );
		add_action( 'init',         array( __CLASS__, 'register_post_types' ) );
		add_action( 'init',         array( __CLASS__, 'register_taxonomies' ) );
		add_action( 'init',         array( __CLASS__, 'register_shortcodes' ) );
		add_action( 'widgets_init', array( __CLASS__, 'register_widgets' ) );
		add_action( 'save_post',    array( '\GeorgeStephanis\Goals\Goal', 'save_post' ), 10, 2 );
	}

	public static function register_scripts_styles() {
		$min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		wp_register_style( 'goals', plugins_url( "css/goals{$min}.css", __GOALS_PLUGIN_FILE__ ) );
	}

	public static function register_post_types() {
		$labels = array(
			'name'                => _x( 'Goals', 'Post Type General Name', 'goals' ),
			'singular_name'       => _x( 'Goal', 'Post Type Singular Name', 'goals' ),
			'menu_name'           => __( 'Goals', 'goals' ),
			'name_admin_bar'      => __( 'Goals', 'goals' ),
			'parent_item_colon'   => __( 'Parent Goal:', 'goals' ),
			'all_items'           => __( 'All Goals', 'goals' ),
			'add_new_item'        => __( 'Add New Goal', 'goals' ),
			'add_new'             => __( 'Add New', 'goals' ),
			'new_item'            => __( 'New Goal', 'goals' ),
			'edit_item'           => __( 'Edit Goal', 'goals' ),
			'update_item'         => __( 'Update Goal', 'goals' ),
			'view_item'           => __( 'View Goal', 'goals' ),
			'search_items'        => __( 'Search Goals', 'goals' ),
			'not_found'           => __( 'Not found', 'goals' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'goals' ),
		);
		$args = array(
			'label'               => __( 'goal', 'goals' ),
			'description'         => __( 'A Goal', 'goals' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'author', 'comments', 'revisions', ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 25,
			'menu_icon'           => 'dashicons-chart-area',
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'register_meta_box_cb' => array( __CLASS__, 'register_meta_boxes' ),
		);
		register_post_type( self::POST_TYPE, $args );
	}

	public static function register_taxonomies() {
		// TODO: Create taxonomies for sorting goals by user, team?
	}

	public static function register_shortcodes() {
		add_shortcode( 'goal', array( '\GeorgeStephanis\Goals\Goal', 'shortcode' ) );
	}

	public static function register_widgets() {
		// TODO: Create widgets for displaying single goals, goals by user, goals by team, or all goals?
	}

	public static function register_meta_boxes() {
		add_meta_box( 'goal_details', __( 'Details', 'goals' ), array( '\GeorgeStephanis\Goals\Goal', 'goal_details_meta_box' ), null, 'normal', 'high' );
	}

	public static function get_all_dashicons() {
		$dashicons_css = file_get_contents( ABSPATH . 'wp-includes/css/dashicons.css' );
		preg_match_all( '/\.(?<class>dashicons-[a-z\d-]+):before {/', $dashicons_css, $dashicons );
		$classes = $dashicons['class'];
		array_shift( $classes ); // Remove .dashicons-before
		return array_unique( $classes );
	}
}
