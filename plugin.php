<?php

/**
 * A Newsletter mailer should extend the base class to get advantage of some already defined
 * methods and behaviors.
 * 
 * A class can be named freely, of course, we ask the kindness to not start the class name
 * with "Newsletter" to avoid conflicts with the main plugin. 
 * 
 * Select your own prefix.
 */
class NewsletterDummyMailerAddon extends NewsletterMailerAddon {

    /**
     * @var NewsletterDummyMailerAddon
     */
    static $instance;

    function __construct($version) {
        self::$instance = $this;
        
        // The base class can add a menu entry for you both as submenu entry of the
        // Newsletter side menu and as menu entry on the admin pages top menu (under
        // "Settings").
        $this->menu_title = 'Dummy Mailer';
        $this->menu_description = 'A real dummy mailer';

        // The prefix is used to create a unique name for this plugin options (it will
        // be "newsletter-[prefix]", the version is used to trigger the "upgrade()" method
        // (typically not used on mailer addons) and the __DIR__ to link the menu entries to the
        // index.php file which should contain the admin configuration page.
        parent::__construct('dummymailer', $version, __DIR__);
    }

    /**
     * Method triggered on version change.
     * 
     * @param boolean $first_install
     */
    function upgrade($first_install = false) {
        // Useful to be sure a set of default values are present in the options array
        // (we'll see it used on other mathods).
        $this->merge_defaults(['enabled' => 0]);
    }

    /**
     * Any initialization that should take place after Newsletter initialization. Remember to
     * call the parent::init(), or remove totally this method.
     */
    function init() {
        parent::init();
    }

    /**
     * This is mandatory: it should return an object based on the class NewsletterMailer which is
     * the base class able to manage emails delivery. Implementation is up to you, this is just an example,
     * it must only return the right class type.
     * 
     * Be sure, as in this example, to "cache" the mailer.
     * 
     * @return \NewsletterDummyMailer
     */
    function get_mailer() {
        static $mailer = null;

        if (!$mailer) {
            $mailer = new NewsletterDummyMailer($this);
        }

        return $mailer;
    }

}

/**
 * The real dummy mailer as an extension of the base mailer. This is the absolute 
 * simplest version possible.
 */
class NewsletterDummyMailer extends NewsletterMailer {

    /**
     * This is not exactly clear but believe me it works. You can change the constructor
     * signature since it is used only by you in the get_mailer() method.
     * 
     * @param NewsletterDummyMailerAddon $addon
     */
    function __construct($addon) {
        // The name is actually the prefix used on NewsletterDummyMailerAddon and used to
        // build log file names. The options can be any array, but usually the options used by this
        // mailer are part of the options collected by this addon.
        parent::__construct($addon->name, $addon->options);
    }


    /**
     * Just send a single message.
     * @param TNP_Mailer_Message $message
     * @return \WP_Error|boolean return a WP_Error or true. See the code for comments about the WP_Error codes
     */
    function send($message) {
        $logger = $this->get_logger();
        $logger->debug('Sending to ' . $message->to);
        
        // To be implemented using $this->options. For example $this->options['api_key'] if
        // you collected an API key on the configuration panel.
        
        // If a WP_Error needs to be returned, you can choose between two error codes
        // 
        // NewsletterMailer::ERROR_FATAL
        // NewsletterMailer::ERROR_GENERIC
        //
        // The "fatal" error blocks the delivery of the whole newsletter and ad admin message is shown
        // so the administratror can check the problem and restart the delivery.
        //
        // Example: return new WP_Error(NewsletterMailer::ERROR_GENERIC, 'Something not so terrible happened');
                
        
        return true;
    }

}
