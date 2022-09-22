<?php

get_header();

while (have_posts()) {
    the_post();

    pageBanner();
?>
    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    All Campuses
                </a>
                <span class="metabox__main"><?php the_title(); ?></span>
            </p>
        </div>
        <div class="generic-content"><?php the_content(); ?></div>

        <div class="acf-map">
            <?php
            $mapLocation = get_field('map_location');
            ?>
            <div class="marker" data-lat="<?php echo $mapLocation['lat']; ?>" data-lng="<?php echo $mapLocation['lng']; ?>">
                <h3><?php the_title(); ?></h3>
                <?php echo $mapLocation['address'] ?? ''; ?>
            </div>
        </div>

        <?php
        $today = date('Ymd');

        $relatedPrograms = new WP_Query([
            'post_type' => 'program',
            'posts_per_page' => -1,
            'orderby' => 'title', 
            'order' => 'ASC',               
            'meta_query' => [               
                [
                    'key' => 'related_campus',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"', // Checks for the ID in serialized related programs array 
                ],
            ],
        ]);

        if ($relatedPrograms->have_posts()) {
        ?>
            <hr class="section-break">
            <h2 class="headline headline--medium">Programs Available At This Campus</h2>

            <ul class="min-list link-list">
                <?php
                while ($relatedPrograms->have_posts()) {
                    $relatedPrograms->the_post();
                ?>
                    <li>
                        <a href="<?php echo get_the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </li>
                <?php
                }
                wp_reset_postdata();
                ?>
            </ul>

        <?php
        }

        $today = date('Ymd');

        $upcomingEvents = new WP_Query([
            'post_type' => 'event',
            'posts_per_page' => 2,
            'meta_key' => 'event_date',     // Custom field we want to use for ordering
            'orderby' => 'meta_value_num',  // Tells WordPress that the field should be ordered numerically
            'order' => 'ASC',               // Order direction (ASC, DESC),
            'meta_query' => [               // Custom where query to use
                [
                    'key' => 'event_date',
                    'compare' => '>=',
                    'value' => $today,
                    'type' => 'numeric',
                ],
                [
                    'key' => 'related_programs',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"', // Checks for the ID in serialized related programs array 
                ],
            ],
        ]);

        if ($upcomingEvents->have_posts()) {
        ?>
            <hr class="section-break">
            <h2 class="headline headline--medium">Upcoming <?php echo get_the_title(); ?> Events</h2>

            <?php
            while ($upcomingEvents->have_posts()) {
                $upcomingEvents->the_post();

                get_template_part('template-parts/content-event');
            }

            wp_reset_postdata();
            ?>
        <?php
        }
        ?>
    </div>
<?php
}

get_footer();

?>