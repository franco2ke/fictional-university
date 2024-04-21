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
    add_action('admin_menu', array($this, 'adminPage')); // Add admin menu page
    add_action('admin_init', array($this, 'settings')); // Initialize settings
  }

  // Function to define plugin settings
  function settings()
  {
    // Add a settings section to the plugin settings page ( with no subheadings, content)
    add_settings_section('wcp_first_section', null, null, 'word-count-settings-page');

    // Add a settings field to the 'wcp_first_section' for the display location option
    // A plugin can have multiple settings pages hence why page slug required
    add_settings_field('wcp_location', 'Display Location', array($this, 'locationHTML'), 'word-count-settings-page', 'wcp_first_section');

    // Register a setting named 'wcp_location' with the wordpress settings API
    register_setting('wordcountplugin', 'wcp_location', array(
      'sanitize_callback' => 'sanitize_text_field', // Sanitize callback function to ensure data security
      'default' => 0 // Default value for the setting
    ));
  }

  // Function to render the HTML for the location setting field
  function locationHTML()
  { ?>
    <!-- HTML markup for the select dropdown -->
    <select name="wcp_location">
      <option value="0">Beginning of post</option>
      <option value="1">End of post</option>
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
        // Output security fields (nonce)
        settings_fields('wordcountplugin');

        // Output settings sections and fields
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
