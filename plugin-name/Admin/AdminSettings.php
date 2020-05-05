<?php 

namespace PluginName\Admin;

// If this file is called directly, abort.
if (!defined('ABSPATH')) exit;

/**
 * Settings of the admin area.
 * Add the appropriate suffix constant for every field ID to take advantake the standardized sanitizer.
 *
 * @since      1.0.0
 *
 * @package    PluginName
 * @subpackage PluginName/Admin
 */
class AdminSettings
{			
	const TEXT_SUFFIX = '-tx';
	const TEXTAREA_SUFFIX = '-ta';
	const CHECKBOX_SUFFIX = '-cb';
	const RADIO_SUFFIX = '-rb';
	const SELECT_SUFFIX = '-sl';
	
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $pluginSlug    The ID of this plugin.
	 */
	private $pluginSlug;
	
	/**
	 * The slug name for the menu.
	 * Should be unique for this menu page and only include 
	 * lowercase alphanumeric, dashes, and underscores characters to be compatible with sanitize_key().
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $menuSlug    Slug name.
	 */
	private $menuSlug;
	
	/**
	 * General settings' group name.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $generalOptionGroup    The settings group name of the general settings.
	 */
	private $generalOptionGroup;
	private $exampleOptionGroup;
	
	/**
	 * General settings' section.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $generalSettingsSection    The slug-name of the section of the settings page in which to show the box.
	 */
	private $generalSettingsSection;
	private $exampleSettingsSection;
	
	/**
	 * General settings page.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $generalPage    The slug-name of the settings page on which to show the section.
	 */
	private $generalPage;
	private $examplePage;

	/**
	 * Name of general options.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $generalOptionName    Option name. Expected to not be SQL-escaped.
	 */
	private $generalOptionName;
	private $gexampleOptionName;
	
	/**
	 * Collection of options.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $generalOptions    Options.
	 */
	private $generalOptions;
	private $exampleOptions;
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $pluginSlug       The name of this plugin.
	 */
	public function __construct($pluginSlug)
	{
		$this->pluginSlug = $pluginSlug;
		$this->menuSlug = $this->pluginSlug . '-options';
		
		/**
		 * General
		 */
		$this->generalOptionGroup = $pluginSlug . '-general-option-group';
		$this->generalSettingsSection = $pluginSlug . '-general-section';
		$this->generalPage = $pluginSlug . '-general';
		$this->generalOptionName = $pluginSlug . '-general-options';
		
		/**
		 * Input example
		 */
		$this->exampleOptionGroup = $pluginSlug . '-example-option-group';
		$this->exampleSettingsSection = $pluginSlug . '-example-section';
		$this->examplePage = $pluginSlug . '-example';
		$this->exampleOptionName = $pluginSlug . '-example-options';
	}

	/**
	 * This function introduces the plugin options into the Main menu.
	 */
	public function setupSettingsMenu()
	{
		//Add the menu item to the Main menu
		add_menu_page(
			'Plugin Name Options',						// Page title: The title to be displayed in the browser window for this page.
			'Plugin Name',								// Menu title: The text to be used for the menu.
			'manage_options',							// Capability: The capability required for this menu to be displayed to the user.
			$this->menuSlug,							// Menu slug: The slug name to refer to this menu by. Should be unique for this menu page.
			array($this, 'renderSettingsPageContent'),	// Callback: The name of the function to call when rendering this menu's page
			'dashicons-smiley',							// Icon
			81											// Position: The position in the menu order this item should appear.
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
				<a href="?page=<?php echo $this->menuSlug; ?>&tab=general_options" class="nav-tab <?php echo $activeTab === 'general_options' ? 'nav-tab-active' : ''; ?>"><?php _e('General', 'plugin-name'); ?></a>
				<a href="?page=<?php echo $this->menuSlug; ?>&tab=input_examples" class="nav-tab <?php echo $activeTab === 'input_examples' ? 'nav-tab-active' : ''; ?>"><?php _e('Input Examples', 'plugin-name'); ?></a>
			</h2>

			<form method="post" action="options.php">
				<?php				
				if($activeTab === 'general_options')
				{
					settings_fields($this->generalOptionGroup);
					do_settings_sections($this->generalPage);
				}
				else
				{
					settings_fields($this->exampleOptionGroup);
					do_settings_sections($this->examplePage);
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
		// Get the current option values.
		$this->generalOptions = get_option($this->generalOptionName);
		
		// If the options don't exist, create them.
		if ($this->generalOptions === false)
		{
			$this->generalOptions = $this->defaultGeneralOptions();
			update_option($this->generalOptionName, $this->generalOptions);
		}

		add_settings_section(
			$this->generalSettingsSection,				// ID used to identify this section and with which to register options
			__('General', 'plugin-name'),				// Title to be displayed on the administration page
			array($this, 'generalOptionsCallback'),		// Callback used to render the description of the section
			$this->generalPage							// Page on which to add this section of options
		);
		
		// Next, we'll introduce the fields for toggling the visibility of content elements.
		add_settings_field(
			'debug' . self::CHECKBOX_SUFFIX,			// ID used to identify the field throughout the theme
			__('Debug', 'plugin-name'),					// The label to the left of the option interface element
			array($this, 'debugCallback'),				// The name of the function responsible for rendering the option interface
			$this->generalPage,							// The page on which this option will be displayed
			$this->generalSettingsSection				// The name of the section to which this field belongs
		);

		// Finally, we register the fields with WordPress.
		register_setting($this->generalOptionGroup, $this->generalOptionName, array($this, 'sanitizeOptionsCallback'));
	}
	
	/**
	 * Provide default values for the General Options.
	 *
	 * @return array
	 */
	public function defaultGeneralOptions()
	{
		return array(
			'debug' . self::CHECKBOX_SUFFIX => false
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
		var_dump($this->generalOptions);
		echo '<p>' . __('General options.', 'plugin-name') . '</p>';
	}

	public function debugCallback()
	{
		$html = '<input type="checkbox" id="debug' . self::CHECKBOX_SUFFIX . '" name="' . $this->generalOptionName . '[debug' . self::CHECKBOX_SUFFIX . ']" value="1"' . checked($this->generalOptions['debug' . self::CHECKBOX_SUFFIX], true, false) . '/>';
		
		echo $html;
	}	
	
#endregion
	
#region INPUT EXAMPLES OPTIONS
	
	/**
	 * Initializes the plugins's input example by registering the Sections, Fields, and Settings.
	 * This particular group of options is used to demonstration validation and sanitization.
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function initializeInputExamples()
	{
		// Get the current option values.
		$this->exampleOptions = get_option($this->exampleOptionName);
		
		// If the options don't exist, create them.
		if ($this->exampleOptions === false)
		{
			$this->exampleOptions = $this->defaultInputOptions();
			update_option($this->exampleOptionName, $this->exampleOptions);
		}

		add_settings_section($this->exampleSettingsSection, __('Input Examples', 'plugin-name'), array($this, 'inputExamplesCallback'), $this->examplePage);

		// Next, we'll introduce the fields for toggling the visibility of content elements.
		add_settings_field('text-example' . self::TEXT_SUFFIX, __('Input Element', 'plugin-name'), array($this, 'inputElementCallback'), $this->examplePage, $this->exampleSettingsSection);
		
		add_settings_field('textarea-example' . self::TEXTAREA_SUFFIX, __('Textarea Element', 'plugin-name'), array($this, 'textareaElementCallback'), $this->examplePage, $this->exampleSettingsSection);
		
		add_settings_field('checkbox-example' . self::CHECKBOX_SUFFIX, __('Checkbox Element', 'plugin-name'), array($this, 'checkboxElementCallback'), $this->examplePage, $this->exampleSettingsSection);
		
		add_settings_field('radio-example' . self::RADIO_SUFFIX, __('Radio Button Elements', 'plugin-name'),array($this, 'radioElementCallback'), $this->examplePage, $this->exampleSettingsSection);
		
		add_settings_field('select-example' . self::SELECT_SUFFIX, __('Select Element', 'plugin-name'), array($this, 'selectElementCallback'), $this->examplePage, $this->exampleSettingsSection);

		// Finally, we register the fields with WordPress.
		register_setting($this->exampleOptionGroup,	$this->exampleOptionName, array($this, 'sanitizeOptionsCallback'));
	}

	/**
	 * Provides default values for the Input Options.
	 *
	 * @return array
	 */
	public function defaultInputOptions()
	{
		return array(
			'text-example' . self::TEXT_SUFFIX			=>	'default input example',
			'textarea-example' . self::TEXTAREA_SUFFIX	=>	'',
			'checkbox-example' . self::CHECKBOX_SUFFIX	=>	'',
			'radio-example' . self::RADIO_SUFFIX		=>	'2',
			'select-example' . self::SELECT_SUFFIX		=>	'default'
		);		
	}

	/**
	 * This function provides a simple description for the Input Examples page.
	 */
	public function inputExamplesCallback()
	{
		// Display the settings data for easier examination. Delete it, if you don't need it.
		echo '<p>Display the settings as stored in the database:</p>';
		var_dump($this->exampleOptions);
		
		echo '<p>' . __('Provides examples of the five basic element types.', 'plugin-name') . '</p>';
	}

	public function inputElementCallback()
	{
		// Render the output
		echo '<input type="text" id="text-example' . self::TEXT_SUFFIX . '" name="' . $this->exampleOptionName . '[text-example' . self::TEXT_SUFFIX . ']" value="' . $this->exampleOptions['text-example' . self::TEXT_SUFFIX] . '" />';
	}

	public function textareaElementCallback()
	{
		// Render the output
		echo '<textarea id="textarea-example' . self::TEXTAREA_SUFFIX . '" name="' . $this->exampleOptionName . '[textarea-example' . self::TEXTAREA_SUFFIX . ']" rows="5" cols="50">' . $this->exampleOptions['textarea-example' . self::TEXTAREA_SUFFIX] . '</textarea>';
	}

	/**
	 * This function renders the interface elements for toggling the visibility of the checkbox element.
	 *
	 * It accepts an array or arguments and expects the first element in the array to be the description
	 * to be displayed next to the checkbox.
	 */
	public function checkboxElementCallback()
	{
		// We update the name attribute to access this element's ID in the context of the display options array.
		// We also access the show_header element of the options collection in the call to the checked() helper function.
		$html = '<input type="checkbox" id="checkbox-example' . self::CHECKBOX_SUFFIX . '" name="' . $this->exampleOptionName . '[checkbox-example' . self::CHECKBOX_SUFFIX . ']" value="1"' . checked($this->exampleOptions['checkbox-example' . self::CHECKBOX_SUFFIX], true, false) . '/>';
		$html .= '&nbsp;';
		
		// Here, we'll take the first argument of the array and add it to a label next to the checkbox
		$html .= '<label for="checkbox-example' . self::CHECKBOX_SUFFIX . '">This is an example of a checkbox</label>';

		echo $html;
	}

	public function radioElementCallback()
	{
		$html = '<input type="radio" id="radio-example-one" name="' . $this->exampleOptionName . '[radio-example' . self::RADIO_SUFFIX . ']" value="1"' . checked($this->exampleOptions['radio-example' . self::RADIO_SUFFIX], 1, false) . '/>';
		$html .= '&nbsp;';
		$html .= '<label for="radio-example-one">Option One</label>';
		$html .= '&nbsp;';
		$html .= '<input type="radio" id="radio-example-two" name="' . $this->exampleOptionName . '[radio-example' . self::RADIO_SUFFIX . ']" value="2"' . checked($this->exampleOptions['radio-example' . self::RADIO_SUFFIX], 2, false) . '/>';
		$html .= '&nbsp;';
		$html .= '<label for="radio-example-two">Option Two</label>';

		echo $html;
	}

	public function selectElementCallback()
	{
		$html = '<select id="select-example . ' . self::SELECT_SUFFIX . '" name="' . $this->exampleOptionName . '[select-example' . self::SELECT_SUFFIX . ']">';
		$html .= '<option value="default">' . __('Select a time option...', 'plugin-name') . '</option>';
		$html .= '<option value="never"' . selected($this->exampleOptions['select-example' . self::SELECT_SUFFIX], 'never', false) . '>' . __('Never', 'plugin-name') . '</option>';
		$html .= '<option value="sometimes"' . selected($this->exampleOptions['select-example' . self::SELECT_SUFFIX], 'sometimes', false) . '>' . __('Sometimes', 'plugin-name') . '</option>';
		$html .= '<option value="always"' . selected($this->exampleOptions['select-example' . self::SELECT_SUFFIX], 'always', false) . '>' . __('Always', 'plugin-name') . '</option>';	$html .= '</select>';

		echo $html;
	}	

#endregion

	/**
	 * Sanitizes the option's value.
	 *
	 * Based on:
	 * @link https://divpusher.com/blog/wordpress-customizer-sanitization-examples/
	 *
	 * @since             1.0.0
	 * @package           PluginName
	 *
	 * @param string $input		The unsanitized collection of options.
	 * @return $output			The collection of sanitized values.
	 */
	public function sanitizeOptionsCallback($input)
	{
		// Define the array for the sanitized options
		$output = array();

		// Loop through each of the incoming options
		foreach($input as $key => $value)
		{
			// Sanitize Checkbox. Input must be boolean.
			if($this->endsWith($key, self::CHECKBOX_SUFFIX))
			{
				$output[$key] = isset($input[$key]) ? true : false;
			}
			// Sanitize Radio button. Input must be a slug: [a-z,-,_].
			else if($this->endsWith($key, self::RADIO_SUFFIX))
			{
				$output[$key] = isset($input[$key]) ? sanitize_key($input[$key]) : '';
			}
			// Sanitize Select aka Dropdown. Input must be a slug: [a-z,-,_].	
			else if($this->endsWith($key, self::SELECT_SUFFIX))
			{
				$output[$key] = isset($input[$key]) ? sanitize_key($input[$key]) : '';
			}
			// Sanitize Text
			else if($this->endsWith($key, self::TEXT_SUFFIX))
			{
				$output[$key] = isset($input[$key]) ? sanitize_text_field($input[$key]) : '';
			}
			// Sanitize Textarea
			else if($this->endsWith($key, self::TEXTAREA_SUFFIX))
			{
				$output[$key] = isset($input[$key]) ? sanitize_textarea_field($input[$key]) : '';
			}
			// Edge cases, fallback to default. Input must be Text.
			else
			{
				$output[$key] = isset($input[$key]) ? sanitize_text_field($input[$key]) : '';
			}
		}

		// Return the array processing any additional functions filtered by this action
		return $output;
	}
	
	/**
	 * Determine if a string ends with another string.
	 *
	 * @since             1.0.0
	 * @package           PluginName
	 *
	 * @param string $haystack		Base string.
	 * @param string $needle		The searched value.
	 * @return 						Boolean
	 */
	private function endsWith($haystack, $needle)
	{
		$haystackLenght = strlen($haystack);
		$needleLenght = strlen($needle);
		
		if ($needleLenght > $haystackLenght)
		{
			return false;
		}
		
		return substr_compare($haystack, $needle, -$needleLenght, $needleLenght) === 0;
	}
	
}