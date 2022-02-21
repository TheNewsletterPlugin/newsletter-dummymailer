<?php
/**
 * The settings page for this addon. It is loaded by the main class, so you can reference it
 * as $this. Below the declaration so you can use the code completion from your IDE - do you 
 * use an IDE, uh?
 */
/* @var $this NewsletterDummyMailerAddon */

require_once NEWSLETTER_INCLUDES_DIR . '/controls.php';
$controls = new NewsletterControls();

// Our mailer, used to send tests and to check if there is a different mailer laready active.
$mailer = $this->get_mailer();

if (!$controls->is_action()) {
    $controls->data = $this->options;
} else {

    // Save the options (stored under "newsletter_[addon prefix] - see the class construtor).
    // The admin nonce check is included.
    if ($controls->is_action('save')) {
        $this->save_options($controls->data);
        $controls->add_message_saved();
    }

    if ($controls->is_action('test')) {
        // Get a test message
        $message = $this->get_test_message($controls->data['test_email'], '');
        // This is a special "send" emthod that uses your method bu collects some stats
        $result = $mailer->send_with_stats($message);

        if (is_wp_error($result)) {
            $controls->errors .= 'Delivery error: ' . $result->get_error_message() . '<br>';
        } else {
            $controls->messages = 'Success. You should see the test message in SendGrid console panel.';
            $controls->messages .= '<br>Max speed: ' . $mailer->get_capability() . ' emails per hour';
        }
    }
}

if (!isset($controls->data['enabled'])) {
    $controls->warnings[] = 'The addon is not enabled. After you configured and tested it, remember to enable it.';
}

$current_mailer = Newsletter::instance()->get_mailer();
if (get_class($current_mailer) != get_class($mailer) && !empty($controls->data['enabled'])) {
    $controls->warnings[] = 'There is another integration active ' . $current_mailer->get_description();
}
?>

<div class="wrap" id="tnp-wrap">
    
    <?php include NEWSLETTER_DIR . '/tnp-header.php' ?>
    
    <div id="tnp-heading">

        <h2>Dummy Mailer</h2>
        <p>This is the configuration example of our dummy mailer!</p>

        <?php $controls->show(); ?>

    </div>
    
    <div id="tnp-body">

        <form action="" method="post">
            <?php $controls->init(); ?>

            <table class="form-table">
                
                <!-- If you use this specific option with this specific control, the addon recognize it automatically, so why do differently? -->
                <tr valign="top">
                    <th>Enabled?</th>
                    <td>
                        <?php $controls->enabled(); ?>
                        <p class="description">
                            When not enabled the addon suspends all its activities.
                        </p>
                    </td>
                </tr>

                <tr valign="top">
                    <th>API key</th>
                    <td>
                        <?php $controls->text('api_key'); ?>
                        <p class="description">
                            An example of option to be used by your mailer.
                        </p>
                    </td>
                </tr>

                <!-- Always add a test option: the code on top of this page already manage it -->
                <tr>
                    <th>To test this configuration</th>
                    <td>
                        <?php $controls->text_email('test_email', 30); ?>
                        <?php $controls->button_primary('test', 'Send a message to this email'); ?>
                        <p class="description">
                            The test is made using the configuration you see, without saving it. The test works even if the
                            addon is set as "disabled".
                        </p>
                    </td>
                </tr>
                
            </table>

            <p>
                <?php $controls->button_primary('save', 'Save'); ?>
            </p>
        </form>
    </div>
    
    <?php include NEWSLETTER_DIR . '/tnp-footer.php' ?>
    
</div>
