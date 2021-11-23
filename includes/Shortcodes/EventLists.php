<?php

namespace Akash\EventsStory\Shortcodes;

use Akash\EventsStory\Abstracts\EventStoryShortcode;

class EventLists extends EventStoryShortcode {

    protected $shortcode = 'event-story-events';

    /**
     * Load template files
     *
     * Based on the query vars, load the appropriate template files
     * in the frontend user dashboard.
     *
     * @param array $atts
     *
     * @return void
     */
    public function render_shortcode( $atts ) {
        ob_start();

        $events = new \WP_Query( [
            'post_type'      => 'event_story_event',
            'posts_per_page' => -1,
            'orderby'        => 'meta_value',
            'meta_key'       => 'event_date',
            'order'          => 'DESC'
        ] );

        event_story_get_template( '/events/list.php', [
            'events' => $events,
        ], EVENT_STORY_TEMPLATE_PATH );

        return ob_get_clean();
    }
}
