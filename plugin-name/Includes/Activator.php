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
		//'WooCommerce' => 'woocommerce/woocommerce.php',
	);
    
    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @param   $configuration              The plugin's configuration data.
     * @param   $configurationOptionName    The ID for the configuration options in the database.
     * @since    1.0.0
     */
    public static function activate(array $configuration, string $configurationOptionName): void
    {
        // Permission check
        if (!current_user_can('activate_plugins'))
        {
            deactivate_plugins(plugin_basename(__FILE__));

            // Localization class hasn't been loaded yet.
            wp_die('You don\'t have proper authorization to activate a plugin!');
        }
        
         // Check dependencies
        self::checkDependencies();
        
        // Save the default configuration values
        self::ensureCreateOptions($configurationOptionName, $configuration);
    }

    /**
     * Check whether the required plugins are active.
     *
     * @since      1.0.0
     */
    private static function checkDependencies()
    {
        foreach (self::REQUIRED_PLUGINS as $pluginName => $pluginFilePath)
        {
            if (!is_plugin_active($pluginFilePath))
            {			
                // Deactivate the plugin.
                deactivate_plugins(plugin_basename(__FILE__));
                
                wp_die("This plugin requires {$pluginName} plugin to be active!");
            }
        }
    }
    
    /**
     * Initialize default option values
     *
     * @param   $configurationOptionName    The ID for getting and setting the configuration options from the database.
     * @param   $configuration              The plugin's configuration data.
     * @since      1.0.0
     */
    private static function ensureCreateOptions(string $configurationOptionName, array $configuration): void
    {
        // Save the configuration data if not exist.
        if (get_option($configurationOptionName) === false)
        {
            update_option($configurationOptionName, $configuration);
        }
    }
}
