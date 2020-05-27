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
     * @since   1.0.0
     * @param   $pluginSlug     The name of the plugin.
     * @param   $version        The version of this plugin.
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
    public function enqueueStyles(): void
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * The reason to register the style before enqueue it:
         * - Conditional loading: When initializing the plugin, do not enqueue your styles, but register them.
         *                        You can enque the style on demand.
         * - Shortcodes: In this way you can load your style only where shortcode appears.
         *              If you enqueue it here it will be loaded on every page, even if the shortcode isn’t used.
         *              Plus, the style will be registered only once, even if the shortcode is used multiple times.
         * - Dependency: The style can be used as dependency, so the style will be automatically loaded, if one style is depend on it.
         */
        $styleId = $this->pluginSlug . '-frontend';
        $styleUrl = plugin_dir_url(__FILE__) . 'css/plugin-name-frontend.css';
        if(wp_register_style($styleId, $styleUrl, array(), $this->version, 'all') === false)
        {
            exit(__('Style could not be registered: ', 'communal-marketplace') . $styleUrl);
        }
        
        /**
         * If you enque the style here, it will be loaded on every page on the frontend.
         * To load only with a shortcode, move the wp_enqueue_style to the callback function of the add_shortcode.
         */
        wp_enqueue_style($styleId);
    }

    /**
     * Register the JavaScript for the frontend side of the site.
     *
     * @since    1.0.0
     */
    public function enqueueScripts(): void
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * The reason to register the script before enqueue it:
         * - Conditional loading: When initializing the plugin, do not enqueue your scripts, but register them.
         *                        You can enque the script on demand.
         * - Shortcodes: In this way you can load your script only where shortcode appears.
         *              If you enqueue it here it will be loaded on every page, even if the shortcode isn’t used.
         *              Plus, the script will be registered only once, even if the shortcode is used multiple times.
         * - Dependency: The script can be used as dependency, so the script will be automatically loaded, if one script is depend on it.
         */
        $scriptId = $this->pluginSlug . '-frontend';
        $scriptUrl = plugin_dir_url(__FILE__) . 'js/plugin-name-frontend.js';
        if(wp_register_script($scriptId, $scriptUrl, array('jquery'), $this->version, false) === false)
        {
            exit(__('Script could not be registered: ', 'plugin-name') . $scriptUrl);
        }
        
        /**
         * If you enque the script here, it will be loaded on every page on the frontend.
         * To load only with a shortcode, move the wp_enqueue_script to the callback function of the add_shortcode.
         * If you use the wp_localize_script function, you should place it under the wp_enqueue_script.
         */
        wp_enqueue_script($scriptId);
    }
}
