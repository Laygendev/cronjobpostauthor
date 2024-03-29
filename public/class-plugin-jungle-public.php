<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://jimmylatour.fr
 * @since      1.0.0
 *
 * @package    Plugin_Jungle
 * @subpackage Plugin_Jungle/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Plugin_Jungle
 * @subpackage Plugin_Jungle/public
 * @author     Jimmy L <latour.jimmy@gmail.com>
 */
class Plugin_Jungle_Public {

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
	 * @param      string    $plugin_jungle       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_jungle, $version ) {

		$this->plugin_jungle = $plugin_jungle;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_jungle, plugin_dir_url( __FILE__ ) . 'css/plugin-jungle-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_jungle, plugin_dir_url( __FILE__ ) . 'js/plugin-jungle-public.js', array( 'jquery' ), $this->version, false );

	}

}
