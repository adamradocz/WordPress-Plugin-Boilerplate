<?php

// If this file is called directly, abort.
if (!defined('ABSPATH')) exit;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */
class Plugin_Name {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

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
		$this->version = PLUGIN_NAME_VERSION;
		$this->plugin_name = 'plugin-name';

		$this->load_dependencies();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Plugin_Name_i18n. Defines internationalization functionality.
	 * - Plugin_Name_Admin. Defines all hooks for the admin area.
	 * - Plugin_Name_Public. Defines all hooks for the public side of the site.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-plugin-name-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-plugin-name-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'public/class-plugin-name-public.php';

	}

	/**
	 * Register all the hooks of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_hooks() {

		/*
		 * Admin hooks - Register all of the hooks related to the admin area functionality of the plugin.
		 */
		if (is_admin())
		{
			$plugin_admin = new Plugin_Name_Admin( $this->plugin_name, $this->version );
			add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_scripts' ) );
		}
		/*
		 * Frontend hooks - Register all of the hooks related to the public-facing functionality of the plugin.
		 */
		else
		{
			$plugin_public = new Plugin_Name_Public( $this->plugin_name, $this->version );
			add_action( 'wp_enqueue_scripts', array( $plugin_public, 'enqueue_styles' ) );
			add_action( 'wp_enqueue_scripts', array( $plugin_public, 'enqueue_scripts' ) );			 
		}

		/*
		 * Includes hooks - Register all of the hooks related both to the admin area and to the public-facing functionality of the plugin.
		 */
		 
		 // Set the domain for this plugin for internationalization.
		 $plugin_i18n = new Plugin_Name_i18n($this->plugin_name);
		 add_action( 'plugins_loaded', array( $plugin_i18n, 'load_plugin_textdomain' ) );		
	}

	/**
	 * Execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->define_hooks();
	}
	
}
