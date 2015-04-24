<?php

/*
 * Plugin Name: Goals
 * Plugin URI: http://github.com/georgestephanis/goals
 * Description: A custom post type to store and display your goals.
 * Author: George Stephanis
 * Version: 0.1.0
 * Author URI: http://stephanis.info
 * License: GPLv2+
 * Text Domain: goals
 * Domain Path: /languages/
 */

// plugin requires PHP 5.3 or greater
if ( version_compare( PHP_VERSION, '5.3.0', '<' ) ) {

	add_action( 'admin_init',    'goals_plugin_deactivate' );
	add_action( 'admin_notices', 'goals_plugin_admin_notice' );
	
	function goals_plugin_deactivate() {
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}
	
	function goals_plugin_admin_notice() {
		?>

		<div class="updated">
			<p><?php printf( __( '<strong>%1$s</strong> requires <tt>PHP 5.3.0</tt> or higher to run, your server is currently running <tt>%2$s</tt>.', 'goals' ), esc_html_x( 'Goals', 'Plugin Name', 'goals' ), esc_html( PHP_VERSION ) ); ?></p>
		</div>

		<?php
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}

	return;
}

define( '__GOALS_PLUGIN_FILE__', __FILE__ );

use GeorgeStephanis\Goals\Goals;

include( 'includes/class.goals.php' );
include( 'includes/class.goal.php' );

Goals::get_instance();
