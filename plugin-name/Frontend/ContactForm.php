<?php

declare(strict_types=1);

namespace PluginName\Frontend;

// If this file is called directly, abort.
if (!defined('ABSPATH')) exit;

/**
 * Contact form and Shortcode template.
 *
 * @link       http://example.com
 * @since      1.0.0
 * @package    PluginName
 * @subpackage PluginName/Includes
 * @author     Your Name <email@example.com>
 */
class ContactForm
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     */
    private string $pluginSlug;

    /**
     * Initialize the class and set its properties.
     *
     * @since   1.0.0
     * @param   $pluginSlug     The name of the plugin.
     * @param   $version        The version of this plugin.
     */
    public function __construct(string $pluginSlug)
    {
        $this->pluginSlug = $pluginSlug;
    }

    /**
     * Register all the hooks of this class.
     *
     * @since   1.0.0
     * @param   $isAdmin    Whether the current request is for an administrative interface page.
     */
    public function initializeHooks(bool $isAdmin): void
    {
        // Frontend
        if (!$isAdmin)
        {
            add_shortcode('add_form', array($this, 'formShortcode'));
        }
    }

    /**
     * Shortcode for "Add product" form.
     *
     * @link https://developer.wordpress.org/reference/functions/add_shortcode/
     * Shortcode attribute names are always converted to lowercase before they are passed into the handler function. Values are untouched.
     *
     * The function called by the shortcode should never produce output of any kind.
     * Shortcode functions should return the text that is to be used to replace the shortcode.
     * Producing the output directly will lead to unexpected results.
     *
     * @since   1.0.0
     * @param   $attributes Attributes.
     * @param   $content    The post content.
     * @param   $tag        The name of the shortcode.
     * @return  The text that is to be used to replace the shortcode.
     */
    public function formShortcode($attributes = null, $content = null, string $tag = ''): string
    {
        // Show the Form
        $html = $this->getFormHtml();
        $this->processFormData();

        return $html;
    }

    /**
     * The Form's HTML code.
     * @since    1.0.0
     * @return  The form's HTML code.
     */
    private function getFormHtml(): string
    {
        $html = '<div>
                    <form action="' . esc_url($_SERVER['REQUEST_URI']) . '" method="post">
                        <p>' . wp_nonce_field('getFormHtml', 'getFormHtml_nonce', true, false) . '</p>
                        <p>
                            <label for="email">' . esc_html__('E-mail', 'plugin-name') . '&nbsp;<span class="required">*</span></label>
                            <input type="text" id="email" name="email" value="' . (isset($_POST["email"]) ? esc_html($_POST["email"]) : '') . '" required />
                        </p>
                        <p>
                            <label for="subject">' . esc_html__('Subject', 'plugin-name') . '&nbsp;<span class="required">*</span></label>
                            <input type="text" id="subject" name="subject" value="' . (isset($_POST["subject"]) ? esc_html($_POST["subject"]) : '') . '" required />
                        </p>
                        <p>
                            <label for="body">' . esc_html__('Body', 'plugin-name') . '&nbsp;<span class="required">*</span></label>
                            <textarea rows="5" id="body" name="body" required >' . (isset($_POST["body"]) ? esc_textarea($_POST["body"]) : '') . '</textarea>
                        </p>
                        <p><input type="submit" name="form-submitted" value="' . esc_html__('Submit', 'plugin-name') . '"/></p>
                    </form>
                </div>';

        return $html;
    }

    /**
     * Validates and process the submitted data.
     * @since    1.0.0
     */
    private function processFormData(): void
    {
        // Check the Submit button is clicked
        if(isset($_POST['form-submitted']))
        {
            // Verify Nonce
            if (wp_verify_nonce($_POST['getFormHtml_nonce'], 'getFormHtml'))
            {
                $email = sanitize_email($_POST["email"]);
                $subject = sanitize_text_field($_POST["subject"]);
                $body = sanitize_text_field($_POST["body"]);

                // Process the data.
                var_dump($email, $subject, $body);
            }
            else
            {
                exit(esc_html__('Failed security check.', 'plugin-name'));
            }
        }
    }
}
