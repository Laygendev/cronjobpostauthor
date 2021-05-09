<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Jungle
 * @subpackage Plugin_Jungle/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Plugin_Jungle
 * @subpackage Plugin_Jungle/includes
 * @author     Your Name <email@example.com>
 */
class Plugin_Jungle {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Plugin_Jungle_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_jungle    The string used to uniquely identify this plugin.
	 */
	protected $plugin_jungle;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PLUGIN_JUNGLE_VERSION' ) ) {
			$this->version = PLUGIN_JUNGLE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_jungle = 'plugin-jungle';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Plugin_Jungle_Loader. Orchestrates the hooks of the plugin.
	 * - Plugin_Jungle_i18n. Defines internationalization functionality.
	 * - Plugin_Jungle_Admin. Defines all hooks for the admin area.
	 * - Plugin_Jungle_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-plugin-jungle-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-plugin-jungle-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-plugin-jungle-admin.php';

		/**
		 * The class responsible for defining all actions controls users and their data.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-plugin-jungle-admin-users.php';

		/**
		 * The class responsible for defining all actions controls news and their data.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-plugin-jungle-admin-news.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-plugin-jungle-public.php';

		/**
		 * The class init news post type and other things about news in the front.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-plugin-jungle-public-news.php';

		$this->loader = new Plugin_Jungle_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Jungle_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Plugin_Jungle_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Plugin_Jungle_Admin( $this->get_plugin_jungle(), $this->get_version() );
		$plugin_admin_users = new Plugin_Jungle_Admin_Users();
		$plugin_admin_news = new Plugin_Jungle_Admin_News();

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'cron_update_users', $plugin_admin_users, 'cron_update_users' );
		$this->loader->add_action( 'cron_update_news', $plugin_admin_news, 'cron_update_news' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Plugin_Jungle_Public( $this->get_plugin_jungle(), $this->get_version() );
		$plugin_news = new Plugin_Jungle_Public_News();


		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'init', $plugin_news, 'init' );

		$this->loader->add_action( 'wp_ajax_pj_news_by_author_id', $plugin_news, 'load_by_author_id' );
		$this->loader->add_action( 'wp_ajax_nopriv_pj_news_by_author_id', $plugin_news, 'load_by_author_id' );

		$this->loader->add_shortcode( 'display_news', $plugin_news, 'display' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_jungle() {
		return $this->plugin_jungle;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Plugin_Jungle_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
