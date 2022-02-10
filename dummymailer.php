<?php

/*
  Plugin Name: Newsletter - Dummy Mailer
  Plugin URI: https://www.thenewsletterplugin.com/documentation/developers/
  Description: Example of a mailer for Newsletter. Please customize and rename it!
  Version: 1.0.0
  Requires PHP: 5.6
  Requires at least: 4.6
  Author: The Newsletter Team
  Author URI: https://www.thenewsletterplugin.com
  Disclaimer: Use at your own risk. No warranty expressed or implied is provided.
 */

add_action('newsletter_loaded', function ($version) {
    // Conditional loading based on the Newsletter plugin version
    if ($version < '7.3.0') {
        add_action('admin_notices', function () {
            echo '<div class="notice notice-error"><p>Newsletter plugin upgrade required for Dummy Mailer Addon.</p></div>';
        });
    } else {
        include_once __DIR__ . '/plugin.php';
        new NewsletterDummyMailerAddon('1.0.0');
    }
});
