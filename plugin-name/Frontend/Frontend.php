<?php

namespace PluginName\Frontend;

// If this file is called directly, abort.
if (!defined('ABSPATH')) exit;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    PluginName
 * @subpackage PluginName/Frontend
 * @author     Your Name <email@example.com>
 */
class Frontend
{
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $pluginSlug    The ID of this plugin.
	 */
	private $pluginSlug;

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
	 * @param      string    $pluginSlug       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($pluginSlug, $version)
	{
		$this->pluginSlug = $pluginSlug;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueueStyles()
	{
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->pluginSlug, plugin_dir_url(__FILE__) . 'css/plugin-name-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueueScripts()
	{
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->pluginSlug, plugin_dir_url(__FILE__) . 'js/plugin-name-public.js', array('jquery'), $this->version, false);
	}

}
