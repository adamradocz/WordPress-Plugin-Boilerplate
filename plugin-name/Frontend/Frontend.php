<?php

declare(strict_types=1);

namespace PluginName\Frontend;

use PluginName\Admin\Settings;

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
     */
    private string $pluginSlug;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     */
    private string $version;

    /**
     * The settings of this plugin.
     *
     * @since    1.0.0
     */
    private Settings $settings;

    /**
     * Initialize the class and set its properties.
     *
     * @since   1.0.0
     * @param   $pluginSlug     The name of the plugin.
     * @param   $version        The version of this plugin.
     * @param   $settings       The Settings object.
     */
    public function __construct(string $pluginSlug, string $version, Settings $settings)
    {
        $this->pluginSlug = $pluginSlug;
        $this->version = $version;
        $this->settings = $settings;
    }

    /**
     * Register all the hooks of this class.
     *
     * @since    1.0.0
     * @param   $isAdmin    Whether the current request is for an administrative interface page.
     */
    public function initializeHooks(bool $isAdmin): void
    {
        // Frontend
        if (!$isAdmin)
        {
            add_action('wp_enqueue_scripts', array($this, 'enqueueStyles'), 10);
            add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'), 10);
        }
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
        $styleFileName = ($this->settings->getDebug() === true) ? 'plugin-name-frontend.css' : 'plugin-name-frontend.min.css';
        $styleUrl = plugin_dir_url(__FILE__) . 'css/' . $styleFileName;
        if (wp_register_style($styleId, $styleUrl, array(), $this->version, 'all') === false)
        {
            exit(esc_html__('Style could not be registered: ', 'communal-marketplace') . $styleUrl);
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
        $scripFileName = ($this->settings->getDebug() === true) ? 'plugin-name-frontend.js' : 'plugin-name-frontend.min.js';
        $scriptUrl = plugin_dir_url(__FILE__) . 'js/' . $scripFileName;
        if (wp_register_script($scriptId, $scriptUrl, array('jquery'), $this->version, false) === false)
        {
            exit(esc_html__('Script could not be registered: ', 'plugin-name') . $scriptUrl);
        }

        /**
         * If you enque the script here, it will be loaded on every page on the frontend.
         * To load only with a shortcode, move the wp_enqueue_script to the callback function of the add_shortcode.
         * If you use the wp_localize_script function, you should place it under the wp_enqueue_script.
         */
        wp_enqueue_script($scriptId);

        /**
         * Register the Contact Form script which is used in the Contact Form shortcode.
         */
        $contactFormScripFileName = ($this->settings->getDebug() === true) ? 'plugin-name-contact-form.js' : 'plugin-name-contact-form.min.js';
        $contactFormScriptUrl = plugin_dir_url(__FILE__) . 'js/' . $contactFormScripFileName;
        if (wp_register_script($this->pluginSlug . 'contact-form', $contactFormScriptUrl, array('jquery'), $this->version, false) === false)
        {
            exit(esc_html__('Script could not be registered: ', 'plugin-name') . $contactFormScriptUrl);
        }
    }
}
