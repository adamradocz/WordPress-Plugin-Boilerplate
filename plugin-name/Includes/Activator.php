<?php

declare(strict_types=1);

namespace PluginName\Includes;

// If this file is called directly, abort.
if (!defined('ABSPATH')) exit;

/**
 * Fired during plugin activation.
 * This class defines all code necessary to run during the plugin's activation.
 *
 * It is used to prepare custom files, tables, or any other things that the plugin may need
 * before it actually executes, and that it needs to remove upon uninstallation.
 *
 * @link       http://example.com
 * @since      1.0.0
 * @package    PluginName
 * @subpackage PluginName/Includes
 * @author     Your Name <email@example.com>
 */
class Activator
{
    /**
     * Define the plugins that our plugin requires to function.
     * The key is the plugin name, the value is the plugin file path.
     *
     * @since 1.0.0
     * @var string[]
     */
    private const REQUIRED_PLUGINS = array(
        //'Hello Dolly' => 'hello-dolly/hello.php',
        //'WooCommerce' => 'woocommerce/woocommerce.php'
    );

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @param   $networkWide                Plugin is network-wide activated or not.
     * @param   $configuration              The plugin's configuration data.
     * @param   $configurationOptionName    The ID for the configuration options in the database.
     * @since    1.0.0
     */
    public static function activate(bool $networkWide, array $configuration, string $configurationOptionName): void
    {
        // Initialize default configuration values. If exist doesn't do anyting.
        // If single site, it is saved in the option table. If multisite, it is saved in the sitemeta table.
        add_network_option(get_current_network_id(), $configurationOptionName, $configuration);

        // If Multisite is enabled, the configuration data is saved as network option.
        if (is_multisite())
        {
            // Network-wide activation
            if ($networkWide)
            {
                // Permission check
                if (!current_user_can('manage_network_plugins'))
                {
                    deactivate_plugins(plugin_basename(__FILE__));
                    // Localization class hasn't been loaded yet.
                    wp_die('You don\'t have proper authorization to activate a plugin!');
                }
                
                /**
                 * Network setup
                 */
                // Your Network setup code comes here...

                /**
                 * Site specific setup
                 */
                // Loop through the sites
                foreach (get_sites(['fields'=>'ids']) as $blogId)
                {
                    switch_to_blog($blogId);
                    self::checkDependencies(true, $blogId);
                    self::onActivation();
                    restore_current_blog();
                }
            }
        }
        else // Single site activation
        {
            // Permission check
            if (!current_user_can('activate_plugins'))
            {
                deactivate_plugins(plugin_basename(__FILE__));
                // Localization class hasn't been loaded yet.
                wp_die('You don\'t have proper authorization to activate a plugin!');
            }

            self::checkDependencies();
            self::onActivation();
        }
    }

    /**
     * Activate the newly creatied site if the plugin was network-wide activated.
     * Hook: wpmu_new_blog
     *
     * @param   $blogId                ID of the newly creatied site.
     * @since    1.0.0
     */
    public static function activateNewSite(int $blogId): void
    {
        if (is_plugin_active_for_network('plugin-name/plugin-name.php'))
        {
            switch_to_blog($blogId);
            self::checkDependencies(true, $blogId);
            self::onActivation();
            restore_current_blog();
        }
    }

    /**
     * Check whether the required plugins are active.
     * 
     * @param   $networkWideActivation  Network wide activation.
     * @param   $blogId                 On Multisite context: ID of the currently checking site.
     * @since      1.0.0
     */
    private static function checkDependencies(bool $networkWideActivation = false, int $blogId = 0): void
    {
        foreach (self::REQUIRED_PLUGINS as $pluginName => $pluginFilePath)
        {
            if (!is_plugin_active($pluginFilePath))
            {
                // Deactivate the plugin.
                deactivate_plugins(plugin_basename(__FILE__));
                
                if ($networkWideActivation)
                {
                    wp_die("This plugin requires {$pluginName} plugin to be active on site: " . $blogId);
                }
                else
                {
                    wp_die("This plugin requires {$pluginName} plugin to be active!");
                }
            }
        }
    }
    
    /**
	 * The actual tasks performed during activation of a plugin.
	 * Should handle only stuff that happens during a single site activation,
	 * as the process will repeated for each site on a Multisite/Network installation
	 * if the plugin is activated network wide.
	 */
	public static function onActivation()
	{
		
    }
}
