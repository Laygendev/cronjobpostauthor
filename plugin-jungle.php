<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://jimmylatour.fr
 * @since             1.0.0
 * @package           Plugin_Jungle
 *
 * @wordpress-plugin
 * Plugin Name:       Jungle News
 * Plugin URI:        https://jimmylatour.fr
 * Description:       This is a plugin test for La Jungle.
 * Version:           1.0.0
 * Author:            Jimmy L.
 * Author URI:        https://jimmylatour.fr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       plugin-jungle
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
define( 'PLUGIN_JUNGLE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-jungle-activator.php
 */
function activate_plugin_jungle() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-jungle-activator.php';
	Plugin_Jungle_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-jungle-deactivator.php
 */
function deactivate_plugin_jungle() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-jungle-deactivator.php';
	Plugin_Jungle_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_jungle' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_jungle' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-plugin-jungle.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_plugin_jungle() {

	$plugin = new Plugin_Jungle();
	$plugin->run();

}
run_plugin_jungle();
