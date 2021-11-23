<?php if ( ! defined('ABSPATH') ) exit; ?>

<?php get_header(); ?>

<div class="container-fluid event-story">
    <div class="row">
        <?php while ($events->have_posts()) : $events->the_post(); ?>
            <div class="col-4">
                <div class="card card-body event-story-list-content">
                    <h5 class="card-title event-title">
                        <?php the_title(); ?>
                    </h5>
                    <div>
                        <?php echo esc_html_e( 'Date', 'event-story' ); ?> :
                        <?php echo esc_html( get_post_meta( get_the_ID(), 'event_date', true ) ); ?>
                        <br>
                        <?php echo esc_html_e( 'Location', 'event-story' ); ?> :
                        <?php echo esc_html( get_post_meta( get_the_ID(), 'event_location', true ) ); ?>
                    </div>

                    <div class="mt-4">
                        <a href="<?php the_permalink(); ?>" class="btn btn-primary">
                            <?php echo esc_html_e( 'Details', 'event-story' ); ?>
                        </a>
                        <a target="_blank" href="<?php echo get_post_meta( get_the_ID(), 'event_url', true ); ?>" class="btn btn-warning ml-2">
                            <?php echo esc_html_e( 'Visit', 'event-story' ); ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>

        <!-- If no event found, render another template -->
        <?php if ( ! $events->have_posts() ) : ?>
            <?php event_story_get_template_part( 'events/no-event' ); ?>
        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>