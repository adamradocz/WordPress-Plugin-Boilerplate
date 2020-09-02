<?php

declare(strict_types=1);

namespace PluginName\Includes;

use PluginName\Includes\I18n;
use PluginName\Admin\Admin;
use PluginName\Admin\Updater;
use PluginName\Admin\Settings;
use PluginName\Admin\NetworkSettings;
use PluginName\Frontend\Frontend;
use PluginName\Frontend\ContactForm;

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
     */
    protected string $pluginSlug;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     */
    protected string $version;

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
        $this->pluginSlug = PLUGIN_NAME_SLUG;
    }

    /**
     * Create the objects and register all the hooks of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function defineHooks(): void
    {
        $isAdmin = is_admin();
        $isNetworkAdmin = is_network_admin();

        /**
         * Includes objects - Register all of the hooks related both to the admin area and to the public-facing functionality of the plugin.
         */
        $i18n = new I18n($this->pluginSlug);
        $i18n->initializeHooks();

        // The Settings' hook initialization runs on Admin area only.
        $settings = new Settings($this->pluginSlug);

        // Contact form and shortcode template. Insert [add_form] shortcode to a page to see the result.
        $contactForm = new ContactForm($this->pluginSlug);
        $contactForm->initializeHooks($isAdmin);

        /**
         * Network Admin objects - Register all of the hooks related to the network admin area functionality of the plugin.
         */
        if ($isNetworkAdmin)
        {
            $networkSettings = new NetworkSettings($this->pluginSlug);
            $networkSettings->initializeHooks($isNetworkAdmin);
        }

        /**
         * Admin objects - Register all of the hooks related to the admin area functionality of the plugin.
         */
        if ($isAdmin)
        {
            $admin = new Admin($this->pluginSlug, $this->version, $settings);
            $admin->initializeHooks($isAdmin);

            $settings->initializeHooks($isAdmin);
        }
        /**
         * Frontend objects - Register all of the hooks related to the public-facing functionality of the plugin.
         */
        else
        {
            $frontend = new Frontend($this->pluginSlug, $this->version, $settings);
            $frontend->initializeHooks($isAdmin);
        }
    }

    /**
     * Run the plugin.
     *
     * @since    1.0.0
     */
    public function run(): void
    {
        $this->defineHooks();
    }
}
