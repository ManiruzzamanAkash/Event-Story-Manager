<?php

namespace Akash\EventsStory\Admin;

/**
 * Events
 *
 * @since 1.0.0
 */
class Events {

    /**
     * Custom Post Type Name
     *
     * @since 1.0.0
     * @var string
     */
    public $post_type = 'event_story_event';

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {

        // Register post type.
        add_action( 'init', [ $this, 'register_events_type' ] );

        // Add the custom columns to the event post type:
        add_filter( "manage_{$this->post_type}_posts_columns", [ $this, 'set_custom_columns' ] );

        // Render custom columns for the event post type:
        add_action( "manage_{$this->post_type}_posts_custom_column" , [ $this,'fill_custom_columns' ], 10, 2 );

        // Add the meta boxes to the event post type:
        add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );

        // Save the meta box data for the event post type:
        add_action( 'save_post', [ $this, 'save_meta_boxes' ] );

        // Register a Top Menu Item:
        add_action( 'admin_menu', array( $this, 'add_events_menu' ) );
        add_action( 'admin_init', array( $this, 'register_events_settings' ) );
    }

    /**
     * Register Custom Post type for Events
     *
     * @since 1.0
     *
     * @return void
     */
    public function register_events_type() {
        $labels = array(
            'name'               => __( 'Events', 'Post Type General Name', 'event-story' ),
            'singular_name'      => __( 'Event', 'Post Type Singular Name', 'event-story' ),
            'menu_name'          => __( 'Events', 'event-story' ),
            'name_admin_bar'     => __( 'Events', 'event-story' ),
            'parent_item_colon'  => __( 'Parent Item', 'event-story' ),
            'all_items'          => __( 'All Events', 'event-story' ),
            'add_new_item'       => __( 'Add New Event', 'event-story' ),
            'add_new'            => __( 'Add New', 'event-story' ),
            'new_item'           => __( 'New Event', 'event-story' ),
            'edit_item'          => __( 'Edit Event', 'event-story' ),
            'update_item'        => __( 'Update Event', 'event-story' ),
            'view_item'          => __( 'View Event', 'event-story' ),
            'search_items'       => __( 'Search Event', 'event-story' ),
            'not_found'          => __( 'Not found', 'event-story' ),
            'not_found_in_trash' => __( 'Not found in Trash', 'event-story' ),
        );

        $args   = array(
            'label'              => __( 'Events', 'event-story' ),
            'description'        => __( 'Events Manage', 'event-story' ),
            'labels'             => $labels,
            'supports'           => array( 'title', 'author', 'editor' ),
            'hierarchical'       => false,
            'public'             => false,
            'publicly_queryable' => true,
            'show_in_menu'       => true,
            'show_in_nav_menus'  => true,
            'show_in_admin_bar'  => true,
            'menu_icon'          => 'dashicons-calendar-alt',
            // 'show_in_rest'    => true, // open it in Gutenberg
            'menu_position'      => 6,
            'show_in_admin_bar'  => true,
            'rewrite'            => array( 'slug' => 'events' ),
            'can_export'         => true,
            'has_archive'        => true,
            'show_ui'            => true,
        );

        register_post_type( $this->post_type, $args );
    }

    /**
     * Add the custom columns to the events post type.
     *
     * @since 1.0.0
     *
     * @param array $columns An array of columns.
     *
     * @return array An array of columns.
     */
    public function set_custom_columns( $columns ) {
        // Unset the date column and author column.
        unset( $columns['date'] );
        unset( $columns['author'] );

        // Add the custom columns.
        $columns['event_date']     = __( 'Event Date', 'event-story' );
        $columns['event_location'] = __( 'Event Location', 'event-story' );
        $columns['event_url']      = __( 'Event URL', 'event-story' );

        return $columns;
    }

    /**
     * Render the custom columns for the events post type.
     *
     * @since 1.0.0
     *
     * @param string $column  The name of the column.
     * @param int    $post_id The ID of the post.
     */
    public function fill_custom_columns( $column, $post_id ) {

        switch ( $column ) {
            case 'event_date':
                $event_date = get_post_meta( $post_id, 'event_date', true );
                echo esc_html( $event_date );
                break;

            case 'event_location':
                $event_location = get_post_meta( $post_id, 'event_location', true );
                echo esc_html( $event_location );
                break;

            case 'event_url':
                $event_url = get_post_meta( $post_id, 'event_url', true );
                echo '<a href="'. esc_url( $event_url ) .'" target="_blank"> <i class="dashicons dashicons-admin-links"></i>&nbsp;' . esc_url( $event_url ) . '</a>' ;
                break;
        }
    }

    /**
     * Add the meta boxes to the events post type.
     *
     * @since 1.0.0
     */
    public function add_meta_boxes() {
        add_meta_box(
            'event_story_additional_info',
            __( 'Event\'s Data', 'event-story' ),
            [ $this, 'render_meta_boxes' ],
            $this->post_type,
            'normal',
            'high'
        );
    }

    /**
     * Render meta boxes.
     *
     * @since 1.0.0
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_boxes( $post ) {
        wp_nonce_field( 'event_story_additional_data', 'event_story_additional_data_nonce' );

        $event_date     = get_post_meta( $post->ID, 'event_date', true );
        $event_location = get_post_meta( $post->ID, 'event_location', true );
        $event_url      = get_post_meta( $post->ID, 'event_url', true );
        ?>

        <label for="event_date"><?php esc_html_e( 'Event Date', 'event-story' ); ?></label>
        <br>
        <input type="date" name="event_date" id="event_date" value="<?php echo esc_attr( $event_date ); ?>" />

        <br><br>
        <label for="event_location"><?php esc_html_e( 'Event Location', 'event-story' ); ?></label>
        <br>
        <input type="text" name="event_location" id="event_location" value="<?php echo esc_attr( $event_location ); ?>" />

        <br><br>
        <label for="event_url"><?php esc_html_e( 'Event URL', 'event-story' ); ?></label>
        <br>
        <input type="url" name="event_url" id="event_url" value="<?php echo esc_url( $event_url ); ?>" />

        <?php
    }

    /**
     * Save the meta boxes for the events post type.
     *
     * @since 1.0.0
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save_meta_boxes( $post_id ) {

        // Verify the nonce before proceeding.
        if ( ! isset( $_POST['event_story_additional_data_nonce'] ) || ! wp_verify_nonce( $_POST['event_story_additional_data_nonce'], 'event_story_additional_data' ) ) {
            return;
        }

        // Stop WP from clearing custom fields on autosave.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Prevent quick edit from clearing custom fields.
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            return;
        }

        // Check the user's permissions.
        if ( isset( $_POST['post_type'] ) && $this->post_type === $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        }

        // Sanitize user input.
        $event_date     = sanitize_text_field( wp_unslash( $_POST['event_date'] ) );
        $event_location = sanitize_text_field( wp_unslash( $_POST['event_location'] ) );
        $event_url      = sanitize_text_field( wp_unslash( $_POST['event_url'] ) );

        // Update the meta field in the database.
        update_post_meta( $post_id, 'event_date', $event_date );
        update_post_meta( $post_id, 'event_location', $event_location );
        update_post_meta( $post_id, 'event_url', $event_url );
    }

    /**
     * Add Menu Page.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function add_events_menu() {
        add_submenu_page(
            "edit.php?post_type={$this->post_type}",
            __( 'Events Settings', 'event-story' ),
            __( 'Settings', 'event-story' ),
            'manage_options',
            'event-settings',
            array( $this, 'events_story_page' ),
            3
        );
    }

    /**
     * Register Event Settings.
     *
     * @return void
     */
    public function register_events_settings() {
        register_setting( 'events-story-settings-group', 'events_story_settings' );
    }

    public function events_story_page() {
        ?>
        <div class="wrap">
            <h1>
                <?php esc_html_e( 'Events Settings', 'event-story' ); ?>
            </h1>
            <p>
                <?php esc_html_e( 'Here you can set the default settings for the events.', 'event-story' ); ?>
            </p>
        </div>
        <?php
    }
}