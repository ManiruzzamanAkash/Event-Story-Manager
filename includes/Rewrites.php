<?php

namespace Akash\EventsStory;

/**
 * EventsStory rewrite rules class
 *
 * @package EventsStory
 */
class Rewrites {

    /**
     * Hook into the functions
     */
    public function __construct() {
        add_filter( 'template_include', [ $this, 'events_template' ], 99 );
    }

    /**
     * Include store template
     *
     * @param type $template
     *
     * @return string
     */
    public function events_template( $template ) {
        return $template;
    }
}
