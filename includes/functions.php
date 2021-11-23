<?php

/**
 * Locate a template and return the path for inclusion.
 *
 * @since 1.0.0
 *
 * @param mixed  $template_name
 * @param string $template_path (default: '')
 * @param string $default_path  (default: '')
 *
 * @return string
 */
function event_story_locate_template( $template_name, $template_path = '', $default_path = '', $pro = false ) {
    if ( ! $template_path ) {
        $template_path = event_story()->template_path();
    }

    if ( ! $default_path ) {
        $default_path = event_story()->plugin_path() . '/templates/';
    }

    // Look within passed path within the theme - this is priority
    $template = locate_template(
        [
            trailingslashit( $template_path ) . $template_name,
        ]
    );

    // Get default template
    if ( ! $template ) {
        $template = $default_path . $template_name;
    }

    // Return what we found
    return apply_filters( 'event_story_locate_template', $template, $template_name, $template_path );
}

/**
 * Get template part implementation for templates.
 *
 * Looks at the theme directory first
 *
 * @since 1.0.0
 *
 * @param mixed  $slug
 * @param string $name (default: '')
 * @param array  $array (default: array())
 */
function event_story_get_template_part( $slug, $name = '', $args = [] ) {
    if ( $args && is_array( $args ) ) {
        extract( $args );
    }

    $template = '';

    // Look in yourtheme/event-story/slug-name.php and yourtheme/event-story/slug.php
    $template = locate_template( [ event_story()->template_path() . "{$slug}-{$name}.php", event_story()->template_path() . "{$slug}.php" ] );
    $template_path = event_story()->plugin_path() . '/templates';

    // Get default slug-name.php
    if ( ! $template && $name && file_exists( $template_path . "/{$slug}-{$name}.php" ) ) {
        $template = $template_path . "/{$slug}-{$name}.php";
    }

    if ( ! $template && ! $name && file_exists( $template_path . "/{$slug}.php" ) ) {
        $template = $template_path . "/{$slug}.php";
    }

    // Allow 3rd party plugin filter template file from their plugin
    $template = apply_filters( 'event_story_get_template_part', $template, $slug, $name );

    if ( $template ) {
        include $template;
    }
}

/**
 * Get other templates (e.g. product attributes) passing attributes and including the file.
 *
 * @since 1.0.0
 *
 * @param mixed  $template_name
 * @param array  $args          (default: array())
 * @param string $template_path (default: '')
 * @param string $default_path  (default: '')
 *
 * @return void
 */
function event_story_get_template( $template_name, $args = [], $template_path = '', $default_path = '' ) {
    if ( $args && is_array( $args ) ) {
        extract( $args );
    }

    $located = event_story_locate_template( $template_name, $template_path, $default_path );

    if ( ! file_exists( $located ) ) {
        _doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', esc_html( $located ) ), '1.0.0.' );

        return;
    }

    do_action( 'event_story_before_template_part', $template_name, $template_path, $located, $args );

    include $located;

    do_action( 'event_story_after_template_part', $template_name, $template_path, $located, $args );
}