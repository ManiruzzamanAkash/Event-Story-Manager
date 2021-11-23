<?php

namespace Akash\EventsStory\Admin;

/**
 * WordPress settings API For Event Story Admin Settings class
 *
 * @since 1.0.0
 */
class AdminBar {

    /**
     * Class constructor
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @return void
     */
    public function __construct() {
        add_action( 'wp_before_admin_bar_render', [ $this, 'event_story_admin_toolbar' ] );
    }

    /**
     * Add Menu in Dashboard Top bar
     *
     * @return void
     */
    public function event_story_admin_toolbar() {
        global $wp_admin_bar;

        $args = [
            'id'     => 'event_story',
            'title'  => __( 'Manage Events', 'event-story' ),
            'href'   => admin_url( 'edit.php?post_type=event_story_event' ),
        ];

        $wp_admin_bar->add_menu( $args );

        /*
         * Add new or remove toolbar
         *
         * @since 1.0.0
         */
        do_action( 'event_story_render_admin_toolbar', $wp_admin_bar );
    }
}
