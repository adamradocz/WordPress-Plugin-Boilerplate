<?php

declare(strict_types=1);

namespace PluginName\Includes;

// If this file is called directly, abort.
if (!defined('ABSPATH')) exit;

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @link       http://example.com
 * @since      1.0.0
 * @package    PluginName
 * @subpackage PluginName/Includes
 * @author     Your Name <email@example.com>
 */
class Deactivator
{
    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @param   $networkWide                Plugin is network-wide activated or not.
     * @since    1.0.0
     */
    public static function deactivate(bool $networkWide): void
    {
        if (is_multisite() && $networkWide)
		{
            // Permission check
            if (!current_user_can('manage_network_plugins'))
            {
                // Localization class hasn't been loaded yet.
                wp_die('You don\'t have proper authorization to deactivate a plugin!');
            }

			// Loop through the sites
            foreach (get_sites(['fields'=>'ids']) as $blogId)
            {
                switch_to_blog($blogId);
                self::onDeactivation();
                restore_current_blog();
            }
		}
		else
		{
            // Permission check
            if (!current_user_can('activate_plugins'))
            {
                // Localization class hasn't been loaded yet.
                wp_die('You don\'t have proper authorization to deactivate a plugin!');
            }

			self::onDeactivation();
		}
    }

    /**
	 * The actual tasks performed during deactivation of a plugin.
	 * Should handle only stuff that happens during a single site deactivation,
	 * as the process will repeated for each site on a Multisite/Network installation
	 * if the plugin is deactivated network wide.
	 */
	public static function onDeactivation()
	{

	}
}
