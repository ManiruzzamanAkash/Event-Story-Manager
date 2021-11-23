<?php

namespace Akash\EventsStory\Abstracts;

abstract class EventStoryShortcode {

    protected $shortcode = '';

    public function __construct() {
        if ( empty( $this->shortcode ) ) {
            error_log( static::class, __( '$shortcode property is empty.', 'event-story' ), '1.0.0' );
        }

        add_shortcode( $this->shortcode, [ $this, 'render_shortcode' ] );
    }

    public function get_shortcode() {
        return $this->shortcode;
    }

    abstract public function render_shortcode( $atts );
}
