<?php

declare(strict_types=1);

namespace PluginName\Frontend;

// If this file is called directly, abort.
if (!defined('ABSPATH')) exit;

/**
 * The frontend functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the frontend stylesheet and JavaScript.
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
	 * @since	1.0.0
	 * @param	$pluginSlug		The name of the plugin.
	 * @param	$version		The version of this plugin.
	 */
	public function __construct(string $pluginSlug, string $version)
	{
		$this->pluginSlug = $pluginSlug;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the frontend side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueueStyles()
	{
		/**
		 * This function is provided for demonstration purposes only.
		 */

		wp_enqueue_style($this->pluginSlug, plugin_dir_url(__FILE__) . 'css/plugin-name-frontend.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the frontend side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueueScripts()
	{
		/**
		 * This function is provided for demonstration purposes only.		 
		 */

		if(wp_enqueue_script($this->pluginSlug, plugin_dir_url(__FILE__) . 'js/plugin-name-frontend.js', array('jquery'), $this->version, false) === false)
		{
			exit(__('Script could not be registered: ', 'plugin-name') . plugin_dir_url(__FILE__) . 'js/plugin-name-frontend.js');
		}
	}

}
