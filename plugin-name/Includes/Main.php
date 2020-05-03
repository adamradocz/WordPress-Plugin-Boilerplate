<?php

namespace PluginName\Includes;

use PluginName\Admin\Admin;
use PluginName\Frontend\Frontend;
use PluginName\Includes\i18n;

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
 * @package    PluginName
 * @subpackage PluginName/Includes
 * @author     Your Name <email@example.com>
 */
class Main
{
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $pluginSlug    The string used to uniquely identify this plugin.
	 */
	protected $pluginSlug;

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
	public function __construct()
	{
		$this->version = PLUGIN_NAME_VERSION;
		$this->pluginSlug = 'plugin-name';
	}

	/**
	 * Register all the hooks of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function defineHooks()
	{
		/*
		 * Admin hooks - Register all of the hooks related to the admin area functionality of the plugin.
		 */
		if (is_admin())
		{
			$plugin_admin = new Admin($this->pluginSlug, $this->version);
			add_action('admin_enqueue_scripts', array($plugin_admin, 'enqueueStyles'));
			add_action('admin_enqueue_scripts', array($plugin_admin, 'enqueueScripts'));
		}
		/*
		 * Frontend hooks - Register all of the hooks related to the public-facing functionality of the plugin.
		 */
		else
		{
			$plugin_frontend = new Frontend($this->pluginSlug, $this->version);
			add_action('wp_enqueue_scripts', array($plugin_frontend, 'enqueueStyles'));
			add_action('wp_enqueue_scripts', array($plugin_frontend, 'enqueueScripts'));			 
		}

		/*
		 * Includes hooks - Register all of the hooks related both to the admin area and to the public-facing functionality of the plugin.
		 */
		 
		 // Set the domain for this plugin for internationalization.
		 $plugin_i18n = new i18n($this->pluginSlug);
		 add_action('plugins_loaded', array($plugin_i18n, 'loadPluginTextdomain'));		
	}

	/**
	 * Execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->defineHooks();
	}
	
}
