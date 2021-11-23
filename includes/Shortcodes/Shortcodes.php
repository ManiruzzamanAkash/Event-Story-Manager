<?php

namespace Akash\EventsStory\Shortcodes;

class Shortcodes {

    private $shortcodes = [];

    /**
     *  Register shortcodes
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function __construct() {
        $this->shortcodes = apply_filters(
            'event_story_shortcodes', [
				'event-story-events' => new EventLists(),
			]
        );
    }

    /**
     * Get registered shortcode classes
     *
     * @since 3.0.0
     *
     * @return array
     */
    public function get_shortcodes() {
        return $this->shortcodes;
    }
}
