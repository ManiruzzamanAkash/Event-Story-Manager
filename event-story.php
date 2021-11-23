<?php

/**
 * Plugin Name: Event Story
 * Plugin URI: https://wordpress.org/plugins/event-story/
 * Description: An event management plugin for WordPress.
 * Version: 1.0.0
 * Author: Maniruzzaman Akash
 * Author URI: https://akash.devsenv.com/
 * Text Domain: event-story
 * Domain Path: /languages/
 * License: GPL2
 */

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class Akash_Event_Story {

    /**
     * Plugin version.
     *
     * @var string
     */
    public $version = '1.0.0';

    /**
     * Instance of this class.
     *
     * @var object
     */
    private static $instance = null;

    /**
     * Minimum PHP version required
     *
     * @since 1.0.0
     *
     * @var string
     */
    private $min_php = '5.6.0';

    /**
     * Holds various class instances
     *
     * @since 1.0.0
     *
     * @var array
     */
    private $container = [];

    /**
     * Initialize the plugin.
     */
    private function __construct() {
        require_once __DIR__ . '/vendor/autoload.php';

        $this->define_constants();

        register_activation_hook( __FILE__, [ $this, 'activate' ] );
        register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );

        $this->init_plugin();
    }

    /**
     * Define all constants
     *
     * @return void
     */
    public function define_constants() {
        $this->define( 'EVENT_STORY_PLUGIN_VERSION', $this->version );
        $this->define( 'EVENT_STORY_FILE', __FILE__ );
        $this->define( 'EVENT_STORY_DIR', __DIR__ );
        $this->define( 'EVENT_STORY_INC_DIR', __DIR__ . '/includes' );
        $this->define( 'EVENT_STORY_TEMPLATE_PATH', __DIR__ . '/templates' );
        $this->define( 'EVENT_STORY_PLUGIN_ASSEST', plugins_url( 'assets', __FILE__ ) );
    }

    /**
     * Define constant if not already defined
     *
     * @since 1.0.0
     *
     * @param string      $name
     * @param string|bool $value
     *
     * @return void
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    /**
     * Load the plugin after WP User Frontend is loaded
     *
     * @return void
     */
    public function init_plugin() {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * @return void
     */
    private function includes() {
        require_once EVENT_STORY_INC_DIR . '/functions.php';
    }

    /**
     * Init hooks
     *
     * @return void
     */
    private function init_hooks() {
        // Localize our plugin
        add_action( 'init', [ $this, 'localization_setup' ] );

        // initialize the classes
        $this->init_classes();
    }

    /**
     * Initialize plugin classes
     *
     * @return void
     */
    public function init_classes() {

        if ( is_admin() ) {
            // Add Menu in Dashboard Top bar
            new \Akash\EventsStory\Admin\AdminBar();

            // Make reusable stuffs to container.
            $this->container['event'] = new \Akash\EventsStory\Admin\Events();
        } else {
            // Do frontend stuffs.
        }

        // Necessary container.
        $this->container['shortcodes'] = new \Akash\EventsStory\Shortcodes\Shortcodes();
        $this->container['scripts']    = new \Akash\EventsStory\Assets();
    }

    /**
     * Plugin localization setup
     *
     * @return void
     */
    public function localization_setup() {
        load_plugin_textdomain( 'event-story', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * Get the plugin path.
     *
     * @return string
     */
    public function plugin_path() {
        return untrailingslashit( plugin_dir_path( __FILE__ ) );
    }

    /**
     * Get the template path.
     *
     * @return string
     */
    public function template_path() {
        return apply_filters( 'event_story_template_path', 'events-story/' );
    }

    /**
     * Activate the plugin.
     *
     * @since 1.0.0
     */
    public function activate() {
        // Create necessary pages for the plugin
        $installer = new \Akash\EventsStory\Install\Installer();
        $installer->install();

        $this->flush_rewrite_rules();
    }

    /**
     * De-active the plugin.
     *
     * @since 1.0.0
     */
    public function deactivate() {
        // TODO: Implement deactivate() method.
        delete_option( 'event_story_pages_created' );
    }

    /**
     * Flush the rewrite rules
     *
     * @since 1.0.0
     */
    private function flush_rewrite_rules() {
        flush_rewrite_rules();
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return Event_Story_Loader Orchestrates the hooks of the plugin.
     */
    public static function init() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}

/**
 * Load Event Story when all plugins loaded
 *
 * @return Akash_Event_Story
 */
function event_story() {
    return Akash_Event_Story::init();
}

// Kick the plugin
event_story();
