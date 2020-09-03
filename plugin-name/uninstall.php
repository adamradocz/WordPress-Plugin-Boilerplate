<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 */

declare(strict_types=1);

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN'))
{
    exit;
}

$currentNetworkId = get_current_network_id();
deleteConfigOptions($currentNetworkId);

// If Multisite is enabled, then uninstall the plugin on every site.
if (is_multisite())
{
    // Permission check
    if (!current_user_can('manage_network_plugins'))
    {
        wp_die('You don\'t have proper authorization to delete a plugin!');
    }

    /**
     * Delete the Network options
     */
    deleteNetworkOptions($currentNetworkId);

    /**
     * Delete the site specific options
     */
    foreach (get_sites(['fields'=>'ids']) as $blogId)
    {
        switch_to_blog($blogId);
        // Site specific uninstall code starts here...
        deleteOptions();
        restore_current_blog();
    }
}
else
{
    // Permission check
    if (!current_user_can('activate_plugins'))
    {
        wp_die('You don\'t have proper authorization to delete a plugin!');
    }

    deleteOptions();
}

/**
 * Delete the plugin's configuration data.
 *
 * @since    1.0.0
 */
function deleteConfigOptions(int $currentNetworkId): void
{
    delete_network_option($currentNetworkId, 'plugin-name-configuration');
}

/**
 * Delete the plugin's network options.
 *
 * @since    1.0.0
 */
function deleteNetworkOptions(int $currentNetworkId): void
{
    delete_network_option($currentNetworkId, 'plugin-name-network-general');
}

/**
 * Delete the plugin's options.
 *
 * @since    1.0.0
 */
function deleteOptions(): void
{
    delete_option('plugin-name-general');
    delete_option('plugin-name-example');
}
