<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://rohsigma.com
 * @since             1.0.0
 * @package           Rohsigma_Any_Api_Connector
 *
 * @wordpress-plugin
 * Plugin Name:       rohsigma any api connector
 * Plugin URI:        https://rohsigma.com/any-api/
 * Description:       The plugin allows users to connect any REST API to WordPress using a simple configuration form. Once connected, the API becomes available globally to JavaScript or any frontend script without requiring additional backend development.
 * Version:           1.0.0
 * Author:            rohsigma
 * Author URI:        https://rohsigma.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rohsigma-any-api-connector
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ROHSIGMA_ANY_API_CONNECTOR_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rohsigma-any-api-connector-activator.php
 */
function rohsigma_any_api_connector_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rohsigma-any-api-connector-activator.php';
	Rohsigma_Any_Api_Connector_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rohsigma-any-api-connector-deactivator.php
 */
function rohsigma_any_api_connector_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rohsigma-any-api-connector-deactivator.php';
	Rohsigma_Any_Api_Connector_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'rohsigma_any_api_connector_activate' );
register_deactivation_hook( __FILE__, 'rohsigma_any_api_connector_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-rohsigma-any-api-connector.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function rohsigma_any_api_connector_run() {

	$plugin = new Rohsigma_Any_Api_Connector();
	$plugin->run();

}
rohsigma_any_api_connector_run();
