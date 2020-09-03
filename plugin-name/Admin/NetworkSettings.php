<?php

declare(strict_types=1);

namespace PluginName\Admin;

use PluginName\Admin\SettingsBase;

// If this file is called directly, abort.
if (!defined('ABSPATH')) exit;

/**
 * Network-wide Settings of the admin area.
 * Add the appropriate suffix constant for every field ID to take advantake the standardized sanitizer.
 *
 * @since      1.0.0
 *
 * @package    PluginName
 * @subpackage PluginName/Admin
 */
class NetworkSettings extends SettingsBase
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     */
    private string $pluginSlug;

    /**
     * The slug name for the menu.
     * Should be unique for this menu page and only include
     * lowercase alphanumeric, dashes, and underscores characters to be compatible with sanitize_key().
     *
     * @since    1.0.0
     */
    private string $menuSlug;

    /**
     * General settings' group name.
     *
     * @since    1.0.0
     */
    private string $generalOptionGroup;

    /**
     * General settings' section.
     * The slug-name of the section of the settings page in which to show the box.
     *
     * @since    1.0.0
     */
    private string $generalSettingsSectionId;

    /**
     * General settings page.
     * The slug-name of the settings page on which to show the section.
     *
     * @since    1.0.0
     */
    private string $generalPage;

    /**
     * Name of general options. Expected to not be SQL-escaped.
     *
     * @since    1.0.0
     */
    private string $networkGeneralOptionName;

    /**
     * Collection of network options.
     *
     * @since    1.0.0
     */
    private array $networkGeneralOptions;

    /**
     * Ids of setting fields.
     */
    private string $debugId;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param    $pluginSlug       The name of this plugin.
     */
    public function __construct(string $pluginSlug)
    {
        $this->pluginSlug = $pluginSlug;
        $this->menuSlug = $this->pluginSlug;

        /**
         * General
         */
        $this->generalOptionGroup = $pluginSlug . '-network-general-option-group';
        $this->generalSettingsSectionId = $pluginSlug . '-network-general-section';
        $this->generalPage = $pluginSlug . '-network-general';
        $this->networkGeneralOptionName = $pluginSlug . '-network-general';

        $this->debugId = 'debug' . self::CHECKBOX_SUFFIX;
    }

    /**
     * Register all the hooks of this class.
     *
     * @since    1.0.0
     * @param   $isNetworkAdmin    Whether the current request is for a network administrative interface page.
     */
    public function initializeHooks(bool $isNetworkAdmin): void
    {
        // Network Admin
        if ($isNetworkAdmin)
        {
            add_action('network_admin_menu', array($this, 'setupNetworkSettingsMenu'));
            add_action('network_admin_edit_plugin_name_update_network_options', array($this, 'plugin_name_update_network_options'));
        }
    }

    /**
     * This function introduces the plugin options into the Network Main menu.
     */
    public function setupNetworkSettingsMenu(): void
    {
        //Add the menu item to the Main menu
        add_menu_page(
            'Plugin Name Network Options',                      // Page title: The title to be displayed in the browser window for this page.
            'Plugin Name',                                      // Menu title: The text to be used for the menu.
            'manage_network_options',                           // Capability: The capability required for this menu to be displayed to the user.
            $this->menuSlug,                                    // Menu slug: The slug name to refer to this menu by. Should be unique for this menu page.
            array($this, 'renderNetworkSettingsPageContent'),   // Callback: The name of the function to call when rendering this menu's page
            'dashicons-smiley',                                 // Icon
            81                                                  // Position: The position in the menu order this item should appear.
        );

        // Get the values of the setting we've registered with register_setting(). It used in the callback functions.
        $this->networkGeneralOptions = $this->getNetworkGeneralOptions();

        // Add a new section to a settings page.
        add_settings_section(
            $this->generalSettingsSectionId,                // ID used to identify this section and with which to register options
            __('Network General', 'plugin-name'),           // Title to be displayed on the administration page
            array($this, 'networkGeneralOptionsCallback'),  // Callback used to render the description of the section
            $this->generalPage                              // Page on which to add this section of options
        );

        // Next, we'll introduce the fields for toggling the visibility of content elements.
        add_settings_field(
            $this->debugId,                        // ID used to identify the field throughout the theme.
            __('Debug', 'plugin-name'),            // The label to the left of the option interface element.
            array($this, 'debugCallback'),         // The name of the function responsible for rendering the option interface.
            $this->generalPage,                    // The page on which this option will be displayed.
            $this->generalSettingsSectionId,       // The name of the section to which this field belongs.
            array('label_for' => $this->debugId)   // Extra arguments used when outputting the field. CSS Class can also be added to the <tr> element with the 'class' key.
        );

        // 'register_setting()' is useless in the Network Admin area.
    }

    /**
     * Renders the Settings page to display for the Settings menu defined above.
     *
     * @since   1.0.0
     * @param   activeTab       The name of the active tab.
     */
    public function renderNetworkSettingsPageContent(string $activeTab = ''): void
    {
        // Check user capabilities
        if (!current_user_can('manage_network_options'))
        {
            return;
        }

        // Add error/update messages.
        // Check if the user have submitted the settings. Wordpress will add the "updated" $_GET parameter to the url
        if (isset($_GET['updated']))
        {
            // Add settings saved message with the class of "updated"
            add_settings_error($this->pluginSlug, $this->pluginSlug . '-message', __('Settings saved.'), 'success');
        }

        // Show error/update messages
        settings_errors($this->pluginSlug);

        ?>
        <!-- Create a header in the default WordPress 'wrap' container -->
        <div class="wrap">

            <h2><?php esc_html_e('Plugin Name Network Options', 'plugin-name'); ?></h2>

            <?php $activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'general_options'; ?>

            <h2 class="nav-tab-wrapper">
                <a href="?page=<?php echo $this->menuSlug; ?>&tab=general_options" class="nav-tab <?php echo $activeTab === 'general_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('General', 'plugin-name'); ?></a>
                <a href="?page=<?php echo $this->menuSlug; ?>&tab=examples" class="nav-tab <?php echo $activeTab === 'examples' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Examples', 'plugin-name'); ?></a>
            </h2>

            <form method="post" action="edit.php?action=plugin_name_update_network_options">
                <?php
                if ($activeTab === 'general_options')
                {
                    settings_fields($this->generalOptionGroup);
                    do_settings_sections($this->generalPage);
                }
                else
                {
                    
                }

                submit_button();
                ?>
            </form>

        </div><!-- /.wrap -->
        <?php
    }

    /**
     * This function here is hooked up to a special action and necessary to process
     * the saving of the options. This is the big difference with a normal options page.
     */
    public function plugin_name_update_network_options()
    {
        // Security check.
        // On the settings page we used the '$this->generalOptionGroup' slug when calling 'settings_fields'
        // but we must add the '-options' postfix when we check the nonce.
        if (wp_verify_nonce($_POST[_wpnonce], $this->generalOptionGroup . '-options') === false)
        {
            wp_die(__('Failed security check.', 'plugin-name'));
        }

        // Get the options.
        $options = $_POST[$this->networkGeneralOptionName];

        // Sanitize the option values
        $sanitizedOptions = $this->sanitizeOptionsCallback($options);

        // Update the options
        update_network_option(get_current_network_id(), $this->networkGeneralOptionName, $sanitizedOptions);

        // At last we redirect back to our options page.
        wp_redirect(add_query_arg(array('page' => $this->menuSlug, 'updated' => 'true'), network_admin_url('settings.php')));
        exit;
    }

#region GENERAL OPTIONS

    /**
     * Return the General options.
     */
    public function getNetworkGeneralOptions(): array
    {
        if (isset($this->networkGeneralOptions))
        {
            return $this->networkGeneralOptions;
        }

        $currentNetworkId = get_current_network_id();
        $this->networkGeneralOptions = get_network_option($currentNetworkId, $this->networkGeneralOptionName, array());

        // If options don't exist, create them.
        if ($this->networkGeneralOptions === array())
        {
            $this->networkGeneralOptions = $this->defaultNetworkGeneralOptions();
            update_network_option($currentNetworkId, $this->networkGeneralOptionName, $this->networkGeneralOptions);
        }

        return $this->networkGeneralOptions;
    }

    /**
     * Provide default values for the Network General Options.
     *
     * @return array
     */
    private function defaultNetworkGeneralOptions(): array
    {
        return array(
            $this->debugId => false
        );
    }

    /**
     * This function provides a simple description for the General Options page.
     *
     * It's called from the initializeNetworkGeneralOptions function by being passed as a parameter
     * in the add_settings_section function.
     */
    public function networkGeneralOptionsCallback(): void
    {
        // Display the settings data for easier examination. Delete it, if you don't need it.
        echo '<p>Display the settings as stored in the database:</p>';
        $this->networkGeneralOptions = $this->getNetworkGeneralOptions();
        var_dump($this->networkGeneralOptions);

        echo '<p>' . esc_html__('Network General Options.', 'plugin-name') . '</p>';
    }

    public function debugCallback(): void
    {
        printf('<input type="checkbox" id="%s" name="%s[%s]" value="1" %s />', $this->debugId, $this->networkGeneralOptionName, $this->debugId, checked($this->networkGeneralOptions[$this->debugId], true, false));
    }

    /**
     * Get Debug option.
     */
    public function getDebug(): bool
    {
        $this->networkGeneralOptions = $this->getNetworkGeneralOptions();
        return (bool)$this->networkGeneralOptions[$this->debugId];
    }

#endregion

}
