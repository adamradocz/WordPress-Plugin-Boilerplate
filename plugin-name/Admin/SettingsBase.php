<?php

declare(strict_types=1);

namespace PluginName\Admin;

// If this file is called directly, abort.
if (!defined('ABSPATH')) exit;

/**
 * Base Settings class.
 * Add the appropriate suffix constant for every field ID to take advantake the standardized sanitizer.
 *
 * @since      1.0.0
 *
 * @package    PluginName
 * @subpackage PluginName/Admin
 */
class SettingsBase
{
    protected const TEXT_SUFFIX = '-tx';
    protected const TEXTAREA_SUFFIX = '-ta';
    protected const CHECKBOX_SUFFIX = '-cb';
    protected const RADIO_SUFFIX = '-rb';
    protected const SELECT_SUFFIX = '-sl';

    /**
     * Initialize the class.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        
    }

    /**
     * Sanitizes the option's value.
     *
     * Based on:
     * @link https://divpusher.com/blog/wordpress-customizer-sanitization-examples/
     *
     * @since             1.0.0
     * @package           PluginName
     *
     * @param   $input      The unsanitized collection of options.
     * @return  $output     The collection of sanitized values.
     */
    public function sanitizeOptionsCallback(array $input = NULL): array
    {
        if ($input === null)
        {
            return array();
        }
        
        // Define the array for the sanitized options
        $output = array();

        // Loop through each of the incoming options
        foreach ($input as $key => $value)
        {
            // Sanitize Checkbox. Input must be boolean.
            if ($this->endsWith($key, self::CHECKBOX_SUFFIX))
            {
                $output[$key] = isset($input[$key]) ? true : false;
            }
            // Sanitize Radio button. Input must be a slug: [a-z,0-9,-,_].
            else if ($this->endsWith($key, self::RADIO_SUFFIX))
            {
                $output[$key] = isset($input[$key]) ? sanitize_key($input[$key]) : '';
            }
            // Sanitize Select aka Dropdown. Input must be a slug: [a-z,0-9,-,_].
            else if ($this->endsWith($key, self::SELECT_SUFFIX))
            {
                $output[$key] = isset($input[$key]) ? sanitize_key($input[$key]) : '';
            }
            // Sanitize Text
            else if ($this->endsWith($key, self::TEXT_SUFFIX))
            {
                $output[$key] = isset($input[$key]) ? sanitize_text_field($input[$key]) : '';
            }
            // Sanitize Textarea
            else if ($this->endsWith($key, self::TEXTAREA_SUFFIX))
            {
                $output[$key] = isset($input[$key]) ? sanitize_textarea_field($input[$key]) : '';
            }
            // Edge cases, fallback to default. Input must be Text.
            else
            {
                $output[$key] = isset($input[$key]) ? sanitize_text_field($input[$key]) : '';
            }
        }

        /**
         * Settings errors should be added inside the $sanitize_callback function.
         * Example: add_settings_error($this->pluginSlug, $this->pluginSlug . '-message', __('Error.'), 'error');
         */

        // Return the array processing any additional functions filtered by this action
        return $output;
    }

    /**
     * Determine if a string ends with another string.
     *
     * @since             1.0.0
     * @package           PluginName
     *
     * @param   $haystack       Base string.
     * @param   $needle         The searched value.
     * @return If the string ends with the another string reruen true, otherwise false
     */
    private function endsWith(string $haystack, string $needle): bool
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
