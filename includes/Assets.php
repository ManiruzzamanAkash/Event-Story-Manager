<?php

namespace Akash\EventsStory;

/**
 * Asset Handler class.
 *
 * @author Akash
 * @since 1.0.0
 */
class Assets {

    /**
     * The constructor
     */
    public function __construct() {
        add_action( 'init', [ $this, 'register_all_scripts' ], 10 );

        if ( is_admin() ) {
            add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
        } else {
            add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_front_scripts' ] );
        }
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts() {
        wp_enqueue_script( 'event-story-admin' );

        do_action( 'event_story_enqueue_admin_scripts' );
    }

    /**
     * Register all scripts and styles
     */
    public function register_all_scripts() {
        $styles  = $this->get_styles();
        $scripts = $this->get_scripts();

        $this->register_styles( $styles );
        $this->register_scripts( $scripts );

        do_action( 'event_story_register_scripts' );
    }

    /**
     * Get registered styles
     *
     * @return array
     */
    public function get_styles() {
        $styles = [
            'event-story-style' => [
                'src'     => EVENT_STORY_PLUGIN_ASSEST . '/css/frontend-style.css',
                'version' => filemtime( EVENT_STORY_DIR . '/assets/css/frontend-style.css' ),
            ],
            'event-story-admin-css' => [
                'src'     => EVENT_STORY_PLUGIN_ASSEST . '/css/admin-style.css',
                'version' => filemtime( EVENT_STORY_DIR . '/assets/css/admin-style.css' ),
            ],
            'event-story-fontawesome' => [
                'src'     => EVENT_STORY_PLUGIN_ASSEST . '/vendors/font-awesome/font-awesome.min.css',
            ],
            'event-story-bootstrap-css' => [
                'src'     => EVENT_STORY_PLUGIN_ASSEST . '/vendors/bootstrap/css/bootstrap.min.css',
            ],
        ];

        return $styles;
    }

    /**
     * Get all registered scripts
     *
     * @return array
     */
    public function get_scripts() {
        $asset_url  = EVENT_STORY_PLUGIN_ASSEST;
        $asset_path = EVENT_STORY_DIR . '/assets/';

        $scripts = [
            'event-story-admin' => [
                'src'       => $asset_url . '/js/event-story-admin.js',
                'deps'      => [ 'jquery' ],
                'version'   => filemtime( $asset_path . '/js/event-story-admin.js' ),
            ],
            'event-story-script' => [
                'src'       => $asset_url . '/js/event-story.js',
                'deps'      => [ 'jquery' ],
                'version'   => filemtime( $asset_path . '/js/event-story.js' ),
            ],
            'event-story-bootstrap-script' => [
                'src'       => $asset_url . '/vendors/bootstrap/js/bootstrap.bundle.min.js',
                'deps'      => [ 'jquery' ],
                'version'   => filemtime( $asset_path . '/vendors/bootstrap/js/bootstrap.bundle.min.js' ),
            ],
        ];

        return $scripts;
    }

    /**
     * Enqueue front-end scripts
     */
    public function enqueue_front_scripts() {
        wp_enqueue_style( 'event-story-style' );
        wp_enqueue_style( 'event-story-fontawesome' );
        wp_enqueue_style( 'event-story-bootstrap-css' );

        // $this->event_story_single_event_page();
    }

    /**
     * Load Event Single Page Scripts
     *
     * @since 1.0.0
     *
     * @global type $wp
     */
    public function event_story_single_event_page() {
        $this->load_map_script();
    }

    /**
     * Load google map script
     *
     * @since 1.0.0
     */
    public function load_map_script() {
        // wp_enqueue_script( 'event-story-gmap-script' );
    }

    /**
     * Get file prefix
     *
     * @return string
     */
    public function get_prefix() {
        $prefix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

        return $prefix;
    }

    /**
     * Register scripts
     *
     * @param array $scripts
     *
     * @return void
     */
    public function register_scripts( $scripts ) {
        foreach ( $scripts as $handle => $script ) {
            $deps      = isset( $script['deps'] ) ? $script['deps'] : false;
            $in_footer = isset( $script['in_footer'] ) ? $script['in_footer'] : true;
            $version   = isset( $script['version'] ) ? $script['version'] : EVENT_STORY_PLUGIN_VERSION;

            wp_register_script( $handle, $script['src'], $deps, $version, $in_footer );
        }
    }

    /**
     * Register styles
     *
     * @param array $styles
     *
     * @return void
     */
    public function register_styles( $styles ) {
        foreach ( $styles as $handle => $style ) {
            $deps    = isset( $style['deps'] ) ? $style['deps'] : false;
            $version = isset( $style['version'] ) ? $style['version'] : EVENT_STORY_PLUGIN_VERSION;

            wp_register_style( $handle, $style['src'], $deps, $version );
        }
    }

    /**
     * Enqueue the scripts
     *
     * @param array $scripts
     *
     * @return void
     */
    public function enqueue_scripts( $scripts ) {
        foreach ( $scripts as $handle => $script ) {
            wp_enqueue_script( $handle );
        }
    }

    /**
     * Enqueue styles
     *
     * @param array $styles
     *
     * @return void
     */
    public function enqueue_styles( $styles ) {
        foreach ( $styles as $handle => $script ) {
            wp_enqueue_style( $handle );
        }
    }
}
