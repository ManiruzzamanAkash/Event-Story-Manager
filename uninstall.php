<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

/**
 * Event Story Uninstall Plugin.
 *
 * Uninstalling Event Story will deletes some pages, and options.
 *
 * @since 1.0.0
 */
class Event_Story_Uninstaller {
    /**
     * Constructor for the class Event_Story_Uninstaller
     *
     * @since 1.0.0
     */
    public function __construct() {

        // Delete Pages created by event_story
        $pages = get_option( 'event_story_pages', [] );
        foreach ( $pages as $page_id ) {
            wp_delete_post( $page_id, true );
        }

        // Delete options
        delete_option( 'event_story_pages_created' );
    }
}

new Event_Story_Uninstaller();
