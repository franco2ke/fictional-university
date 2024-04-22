<?php

/* 
* Plugin Name: Our Test Plugin
* Description: A truly amazing plugin.
* Version: 1.0
* Author: Francis
* Author URI: https://paon.co.ke
*/

class WordCountAndTimePlugin
{
  // Constructor function
  function __construct()
  {
    // Add action hooks to execute the 'adminPage' and 'settings' functions
    add_action('admin_menu', array($this, 'adminPage')); // construct the admin options/settings page
    add_action('admin_init', array($this, 'settings')); // Initialize settings (create in db, frontend and link them)
  }

  // Function to define plugin settings
  function settings()
  {
    // Create a settings section that will be added to the plugin settings page ( with no subheadings, content)
    add_settings_section('wcp_first_section', null, null, 'word-count-settings-page');

    // NOTE: Field 1: location 
    // Add a settings field to the 'wcp_first_section' for the display location option
    // A plugin can have multiple settings pages hence why page slug required
    add_settings_field('wcp_location', 'Display Location', array($this, 'locationHTML'), 'word-count-settings-page', 'wcp_first_section');
    // Register our setting named 'wcp_location' with the wordpress settings API
    // so that $_POST & security handling is done for us and our callback function just has to echo the <input>
    register_setting('wordcountplugin', 'wcp_location', array(
      'sanitize_callback' => array($this, 'sanitizeLocation'), // Sanitize callback function to ensure data security
      'default' => '0' // Default value for the setting
    ));

    // NOTE: Field 2: Headline Text
    // Add a settings field to the 'wcp_first_section' for the display location option
    // A plugin can have multiple settings pages hence why page slug required
    add_settings_field('wcp_headline', 'Headline Text', array($this, 'headlineHTML'), 'word-count-settings-page', 'wcp_first_section');
    // Register our setting named 'wcp_location' with the wordpress settings API
    // so that $_POST handling is done for us and our callback function just has to echo the <input>
    register_setting('wordcountplugin', 'wcp_headline', array(
      'sanitize_callback' => 'sanitize_text_field', // Sanitize callback function to ensure data security
      'default' => 'Post Statistics' // Default value for the setting
    ));

    // NOTE: Field 3: Wordcount
    add_settings_field('wcp_wordcount', 'Word Count', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_wordcount')); // Front end setup
    register_setting('wordcountplugin', 'wcp_wordcount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1')); // Back end setup

    // NOTE: Field 4: Charactercount
    add_settings_field('wcp_charactercount', 'Character Count', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_charactercount'));
    register_setting('wordcountplugin', 'wcp_charactercount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));

    // NOTE: Field 5: Readtime
    add_settings_field('wcp_readtime', 'Read Time', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_readtime')); // create front end
    register_setting('wordcountplugin', 'wcp_readtime', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));
  }

  // custom validation
  function sanitizeLocation($input)
  {
    // check if value of Location is valid ( 0 or 1)
    if ($input != '0' and $input != '1') {
      add_settings_error('wcp_location', 'wcp_location_error', 'Display Location must be either beginning or end');

      return get_option('wcp_location');
    }
    return $input;
  }

  // reusable checkbox function (for enabling character count, word count, read time)
  function checkboxHTML($args)
  { ?>
    <input type="checkbox" name="<?php echo $args['theName']; ?>" value="1" <?php checked(get_option($args['theName']), "1"); ?>>
  <?php }

  // Function to render the markup for the headline text field
  function headlineHTML()
  { ?>
    <input type="text" name="wcp_headline" value="<?php echo esc_attr(get_option('wcp_headline')); ?>">

  <?php }

  // Function to render the HTML for the location setting field
  function locationHTML()
  { ?>
    <!-- HTML markup for the select dropdown -->
    <select name="wcp_location">
      <option value="0" <?php selected(get_option('wcp_location'), '0'); ?>>Beginning of post</option>
      <option value="1" <?php selected(get_option('wcp_location'), '1'); ?>>End of post</option>
    </select>
  <?php }

  // Function to create the plugin's admin page
  function adminPage()
  {
    // Add an options page to the WordPress admin menu with the specified title, capability, slug, and callback function
    add_options_page('Word Count Settings', 'Word Count', 'manage_options', 'word-count-settings-page', array($this, 'ourHTML'));
  }

  // Callback function to display the HTML content of the admin page
  function ourHTML()
  { ?>
    <!-- HTML markup for the admin page -->
    <div class="wrap">
      <h1>Word Count Settings</h1>
      <!-- Form for plugin settings -->
      <form action="options.php" method="POST">
        <?php
        // display hidden fields and handle security of your options form (nonce) + name option group
        settings_fields('wordcountplugin');

        // Output settings sections and fields registered with the WordPress settings API
        do_settings_sections('word-count-settings-page');

        // Output submit button
        submit_button();
        ?>
      </form>
    </div>
<?php }
}

// Create an instance of the WordCountAndTimePlugin class
$wordCountAndTimePlugin = new WordCountAndTimePlugin();
