<?php
/**
 * Plugin Name:       PressApps Document
 * Plugin URI:        http://pressapps.co/product/online-documentation/
 * Description:       Create online product or service documentation in minutes
 * Version:           1.0.0
 * Author:            PressApps
 * Author URI:        http://pressapps.co
 * Text Domain:       pressapps-document
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Skelet Config
 */
$skelet_paths[] = array(
    'prefix'      => 'pado',
    'dir'         => wp_normalize_path(  plugin_dir_path( __FILE__ ).'/includes/' ),
    'uri'         => plugin_dir_url( __FILE__ ).'/includes/skelet',
);

/**
 * Load Skelet Framework
 */
if( ! class_exists( 'Skelet_LoadConfig' ) ){
    include_once dirname( __FILE__ ) .'/includes/skelet/skelet.php';
}

/**
 * Global Variables
 */
if ( class_exists( 'Skelet' ) && ! isset( $pado ) ) {
	$pado = new Skelet( 'pado' );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pressapps-document-activator.php
 */
function activate_pressapps_document() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pressapps-document-activator.php';
	Pressapps_Document_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pressapps-document-deactivator.php
 */
function deactivate_pressapps_document() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pressapps-document-deactivator.php';
	Pressapps_Document_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pressapps_document' );
register_deactivation_hook( __FILE__, 'deactivate_pressapps_document' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pressapps-document.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pressapps_document() {

	$plugin = new Pressapps_Document();
	$plugin->run();

}
run_pressapps_document();
