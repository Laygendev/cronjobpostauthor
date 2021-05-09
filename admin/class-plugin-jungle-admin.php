<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Jungle
 * @subpackage Plugin_Jungle/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Jungle
 * @subpackage Plugin_Jungle/admin
 * @author     Your Name <email@example.com>
 */
class Plugin_Jungle_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_jungle    The ID of this plugin.
	 */
	private $plugin_jungle;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_jungle       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_jungle, $version ) {

		$this->plugin_jungle = $plugin_jungle;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		
		wp_enqueue_style( $this->plugin_jungle, plugin_dir_url( __FILE__ ) . 'css/plugin-jungle-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_jungle, plugin_dir_url( __FILE__ ) . 'js/plugin-jungle-admin.js', array( 'jquery' ), $this->version, false );
	}
}
