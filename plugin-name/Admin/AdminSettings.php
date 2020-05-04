<?php 

namespace PluginName\Admin;

// If this file is called directly, abort.
if (!defined('ABSPATH')) exit;

/**
 * Settings of the admin area.
 *
 * @since      1.0.0
 *
 * @package    PluginName
 * @subpackage PluginName/Admin
 *
 */
class AdminSettings
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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $pluginSlug       The name of this plugin.
	 */
	public function __construct($pluginSlug)
	{
		$this->pluginSlug = $pluginSlug;
	}

	/**
	 * This function introduces the plugin options into the Main menu.
	 */
	public function setupSettingsMenu()
	{
		//Add the menu item to the Main menu
		add_menu_page(
			'Plugin Name Options',						// The title to be displayed in the browser window for this page.
			'Plugin Name',								// The text to be displayed for this menu item
			'manage_options',							// Which type of users can see this menu item
			'plugin_name_options',						// The unique ID - that is, the slug - for this menu item
			array($this, 'renderSettingsPageContent'),	// The name of the function to call when rendering this menu's page
			'dashicons-smiley',							// Icon
			81											// The position in the menu order this item should appear.
		);
	}

	/**
	 * Renders the Settings page to display for the Settings menu defined above.
	 *
	 * @since    1.0.0
	 * @param    string    $activeTab       The name of the active tab.
	 */
	public function renderSettingsPageContent($activeTab = '')
	{
		?>
		<!-- Create a header in the default WordPress 'wrap' container -->
		<div class="wrap">

			<h2><?php _e('Plugin Name Options', 'plugin-name'); ?></h2>

			<?php $activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'general_options'; ?>

			<h2 class="nav-tab-wrapper">
				<a href="?page=plugin_name_options&tab=general_options" class="nav-tab <?php echo $activeTab == 'general_options' ? 'nav-tab-active' : ''; ?>"><?php _e('General', 'plugin-name'); ?></a>
				<a href="?page=plugin_name_options&tab=input_examples" class="nav-tab <?php echo $activeTab == 'input_examples' ? 'nav-tab-active' : ''; ?>"><?php _e('Input Examples', 'plugin-name'); ?></a>
			</h2>

			<form method="post" action="options.php">
				<?php				
				if($activeTab == 'general_options')
				{
					settings_fields('plugin_name_general_options');
					do_settings_sections('plugin_name_general_options');
				}
				else
				{
					settings_fields('plugin_name_input_examples');
					do_settings_sections('plugin_name_input_examples');
				}
				
				submit_button();
				?>
			</form>

		</div><!-- /.wrap -->
	<?php
	}

#region GENERAL OPTIONS

	/**
	 * Initializes the General Options by registering the Sections, Fields, and Settings.
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function initializeGeneralOptions()
	{
		// If the options don't exist, create them.
		if (get_option('plugin_name_general_options') === false)
		{
			update_option('plugin_name_general_options', $this->defaultGeneralOptions());
		}

		add_settings_section(
			'general_settings_section',						// ID used to identify this section and with which to register options
			__('General', 'plugin-name'),					// Title to be displayed on the administration page
			array($this, 'generalOptionsCallback'),			// Callback used to render the description of the section
			'plugin_name_general_options'					// Page on which to add this section of options
		);
		
		// Next, we'll introduce the fields for toggling the visibility of content elements.
		add_settings_field(
			'debug',									// ID used to identify the field throughout the theme
			__('Debug', 'plugin-name'),					// The label to the left of the option interface element
			array($this, 'debugCallback'),				// The name of the function responsible for rendering the option interface
			'plugin_name_general_options',				// The page on which this option will be displayed
			'general_settings_section'					// The name of the section to which this field belongs
		);

		// Finally, we register the fields with WordPress
		register_setting(
			'plugin_name_general_options',
			'plugin_name_general_options',
			array($this, 'sanitizeGeneralOptionsCallback')
		);
	}
	
	/**
	 * Provide default values for the General Options.
	 *
	 * @return array
	 */
	public function defaultGeneralOptions()
	{
		return array(
			'debug' => false
		);
	}

	/**
	 * This function provides a simple description for the General Options page.
	 *
	 * It's called from the initializeGeneralOptions function by being passed as a parameter
	 * in the add_settings_section function.
	 */
	public function generalOptionsCallback()
	{
		$options = get_option('plugin_name_general_options');
		//var_dump($options);
		echo '<p>' . __('General options.', 'plugin-name') . '</p>';
	}

	public function debugCallback()
	{
		// First, we read the General Options collection
		$options = get_option('plugin_name_general_options');		

		// Next, we update the name attribute to access this element's ID in the context of the display options array
		// We also access the show_header element of the options collection in the call to the checked() helper function
		$html = '<input type="checkbox" id="debug" name="plugin_name_general_options[debug]" value="1"' . checked(1, $options['debug'], false) . '/>';
		$html .= '&nbsp;';
		
		// Here, we'll take the first argument of the array and add it to a label next to the checkbox
		$html .= '<label for="debug">This is an example of a checkbox</label>';

		echo $html;
	}
	
	/**
	 * Sanitization callback for the General Options. Since each of the General Options are text inputs,
	 * this function loops through the incoming option and strips all tags and slashes from the value
	 * before serializing it.
	 *
	 * @params	$input	The unsanitized collection of options.
	 *
	 * @returns			The collection of sanitized values.
	 */
	public function sanitizeGeneralOptionsCallback($input)
	{
		// Define the array for the sanitized options
		$output = array();

		// Loop through each of the incoming options
		foreach($input as $key => $val)
		{
			// Check to see if the current option has a value. If so, process it.
			if(isset($input[$key]))
			{
				// Strip all HTML and PHP tags and properly handle quoted strings
				$output[$key] = strip_tags(stripslashes($input[$key]));
			}
		}

		// Return the sanitized collection
		return $output;
	}
	
#endregion
	
#region EXAMPLES OPTIONS
	
	/**
	 * Initializes the plugins's input example by registering the Sections, Fields, and Settings.
	 * This particular group of options is used to demonstration validation and sanitization.
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function initializeInputExamples()
	{
		if (get_option('plugin_name_input_examples') === false)
		{
			update_option('plugin_name_input_examples', $this->defaultInputOptions());
		}

		add_settings_section(
			'input_examples_section',
			__('Input Examples', 'plugin-name'),
			array($this, 'inputExamplesCallback'),
			'plugin_name_input_examples'
		);

		add_settings_field(
			'Input Element',
			__('Input Element', 'plugin-name'),
			array($this, 'inputElementCallback'),
			'plugin_name_input_examples',
			'input_examples_section'
		);

		add_settings_field(
			'Textarea Element',
			__('Textarea Element', 'plugin-name'),
			array($this, 'textareaElementCallback'),
			'plugin_name_input_examples',
			'input_examples_section'
		);

		add_settings_field(
			'Checkbox Element',
			__('Checkbox Element', 'plugin-name'),
			array($this, 'checkboxElementCallback'),
			'plugin_name_input_examples',
			'input_examples_section'
		);

		add_settings_field(
			'Radio Button Elements',
			__('Radio Button Elements', 'plugin-name'),
			array($this, 'radioElementCallback'),
			'plugin_name_input_examples',
			'input_examples_section'
		);

		add_settings_field(
			'Select Element',
			__('Select Element', 'plugin-name'),
			array($this, 'selectElementCallback'),
			'plugin_name_input_examples',
			'input_examples_section'
		);

		register_setting(
			'plugin_name_input_examples',
			'plugin_name_input_examples',
			array($this, 'sanitizeInputExamplesOptionsCallback')
		);
	}

	/**
	 * Provides default values for the Input Options.
	 *
	 * @return array
	 */
	public function defaultInputOptions()
	{
		return array(
			'input_example'		=>	'default input example',
			'textarea_example'	=>	'',
			'checkbox_example'	=>	'',
			'radio_example'		=>	'2',
			'time_options'		=>	'default'
		);
	}

	/**
	 * This function provides a simple description for the Input Examples page.
	 */
	public function inputExamplesCallback()
	{
		$options = get_option('plugin_name_input_examples');
		//var_dump($options);
		echo '<p>' . __('Provides examples of the five basic element types.', 'plugin-name') . '</p>';
	}

	public function inputElementCallback()
	{
		$options = get_option('plugin_name_input_examples');

		// Render the output
		echo '<input type="text" id="input_example" name="plugin_name_input_examples[input_example]" value="' . $options['input_example'] . '" />';
	}

	public function textareaElementCallback()
	{
		$options = get_option('plugin_name_input_examples');

		// Render the output
		echo '<textarea id="textarea_example" name="plugin_name_input_examples[textarea_example]" rows="5" cols="50">' . $options['textarea_example'] . '</textarea>';
	}

	/**
	 * This function renders the interface elements for toggling the visibility of the checkbox element.
	 *
	 * It accepts an array or arguments and expects the first element in the array to be the description
	 * to be displayed next to the checkbox.
	 */
	public function checkboxElementCallback()
	{
		// First, we read the options collection
		$options = get_option('plugin_name_input_examples');

		// Next, we update the name attribute to access this element's ID in the context of the display options array
		// We also access the show_header element of the options collection in the call to the checked() helper function
		$html = '<input type="checkbox" id="checkbox_example" name="plugin_name_input_examples[checkbox_example]" value="1"' . checked(1, $options['checkbox_example'], false) . '/>';
		$html .= '&nbsp;';
		
		// Here, we'll take the first argument of the array and add it to a label next to the checkbox
		$html .= '<label for="checkbox_example">This is an example of a checkbox</label>';

		echo $html;
	}

	public function radioElementCallback()
	{
		$options = get_option('plugin_name_input_examples');

		$html = '<input type="radio" id="radio_example_one" name="plugin_name_input_examples[radio_example]" value="1"' . checked(1, $options['radio_example'], false) . '/>';
		$html .= '&nbsp;';
		$html .= '<label for="radio_example_one">Option One</label>';
		$html .= '&nbsp;';
		$html .= '<input type="radio" id="radio_example_two" name="plugin_name_input_examples[radio_example]" value="2"' . checked(2, $options['radio_example'], false) . '/>';
		$html .= '&nbsp;';
		$html .= '<label for="radio_example_two">Option Two</label>';

		echo $html;
	}

	public function selectElementCallback()
	{
		$options = get_option('plugin_name_input_examples');

		$html = '<select id="time_options" name="plugin_name_input_examples[time_options]">';
		$html .= '<option value="default">' . __('Select a time option...', 'plugin-name') . '</option>';
		$html .= '<option value="never"' . selected($options['time_options'], 'never', false) . '>' . __('Never', 'plugin-name') . '</option>';
		$html .= '<option value="sometimes"' . selected($options['time_options'], 'sometimes', false) . '>' . __('Sometimes', 'plugin-name') . '</option>';
		$html .= '<option value="always"' . selected($options['time_options'], 'always', false) . '>' . __('Always', 'plugin-name') . '</option>';	$html .= '</select>';

		echo $html;
	}

	public function sanitizeInputExamplesOptionsCallback($input)
	{
		// Define the array for the sanitized options
		$output = array();

		// Loop through each of the incoming options
		foreach($input as $key => $value)
		{
			// Check to see if the current option has a value. If so, process it.
			if(isset($input[$key]))
			{
				// Strip all HTML and PHP tags and properly handle quoted strings
				$output[$key] = strip_tags(stripslashes($input[$key]));
			}
		}

		// Return the array processing any additional functions filtered by this action
		return $output;
	}

#endregion

}