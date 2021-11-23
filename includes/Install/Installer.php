<?php

namespace Akash\EventsStory\Install;

use Exception;

/**
 * Event Story installer class.
 *
 * @since 1.0.0
 */
class Installer {

    /**
     * Install Event Story related pages and all.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function install() {
        $this->add_version_info();
        $this->setup_pages();
        flush_rewrite_rules();
    }

    /**
     * Adds plugin installation time.
     *
     * @since 1.0.0
     *
     * @return boolean
     */
    public function add_version_info() {
        if ( empty( get_option( 'event_story_installed_time' ) ) ) {
            $current_time = current_datetime()->getTimestamp();
            update_option( 'event_story_installed_time', $current_time );
        }
    }

    /**
     * Setup all pages for our plugin.
     *
     * @return void
     */
    public function setup_pages() {
        // return if pages were created before for frontend.
        $page_created = get_option( 'event_story_pages_created', false );

        if ( $page_created ) {
            return;
        }

        $pages = [
            [
                'post_title' => __( 'Events', 'event-story' ),
                'slug'       => 'events',
                'page_id'    => 'events',
                'content'    => '[event-story-events]',
            ]
        ];

        $pages_ids = [];
        if ( $pages ) {
            foreach ( $pages as $page ) {
                $pages_ids[] = $this->create_page( $page );
            }
        }

        // Update pages
        update_option( 'event_story_pages', $pages_ids );

        // set the option to true so that we don't create the pages again.
        update_option( 'event_story_pages_created', true );
    }

    /**
     * Create a page.
     *
     * @since 1.0.0
     *
     * @param array $page_data
     *
     * @return void|int
     */
    public function create_page( $page ) {
        $meta_key = '_wp_page_template';
        $page_obj = get_page_by_path( $page['post_title'] );

        if ( ! $page_obj ) {
            try {
                $page_id = wp_insert_post(
                    [
                        'post_title'     => $page['post_title'],
                        'post_name'      => $page['slug'],
                        'post_content'   => $page['content'],
                        'post_status'    => 'publish',
                        'post_type'      => 'page',
                        'comment_status' => 'closed',
                    ]
                );
            } catch ( Exception $e ) {
                error_log( $e->getMessage() );
            }

            if ( $page_id && ! is_wp_error( $page_id ) ) {
                if ( isset( $page['template'] ) ) {
                    update_post_meta( $page_id, $meta_key, $page['template'] );
                }

                return $page_id;
            }
        }

        return false;
    }
}
