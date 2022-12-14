<?php

get_header();

while (have_posts()) {
    the_post();

    pageBanner();
?>
    <div class="container container--narrow page-section">
        <div class="generic-content">
            <div class="row group">
                <div class="one-third"><?php the_post_thumbnail('professor-portrait'); ?></div>
                <div class="two-thirds">
                    <?php
                    $like_count = new WP_Query([
                        'post_type'  => 'like',
                        'meta_query' => [
                            [
                                'key'     => 'liked_professor_id',
                                'compare' => '=',
                                'value'   => get_the_ID(),
                            ],
                        ],
                    ]);

                    $current_user_liked_professor = 'no';
                    $like_id = null;

                    if (is_user_logged_in()) {
                        $like_exists_query = new WP_Query([
                            'author_id'  => get_current_user_id(),
                            'post_type'  => 'like',
                            'meta_query' => [
                                [
                                    'key'     => 'liked_professor_id',
                                    'compare' => '=',
                                    'value'   => get_the_ID(),
                                ],
                            ],
                        ]);

                        if ($like_exists_query->found_posts) {
                            $current_user_liked_professor = 'yes';
                            $like_id = $like_exists_query->posts[0]->ID;
                        }
                    }
                    ?>

                    <span class="like-box" data-like="<?php echo $like_id; ?>" data-professor="<?php the_ID(); ?>" data-exists="<?php echo $current_user_liked_professor; ?>">
                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                        <i class="fa fa-heart" aria-hidden="true"></i>
                        <span class="like-count"><?php echo $like_count->found_posts; ?></span>
                    </span>
                    <?php the_content(); ?>
                </div>
            </div>
        </div>

        <?php
        $relatedPrograms = get_field('related_programs');

        if ($relatedPrograms) {
        ?>
            <hr class="section-break">
            <h2 class="headline headline--medium">Subject(s) Taught</h2>

            <ul class="link-list min-list">
                <?php
                foreach ($relatedPrograms as $program) {
                ?>
                    <li>
                        <a href="<?php echo get_the_permalink($program); ?>"><?php echo get_the_title($program); ?></a>
                    </li>
                <?php
                }
                ?>
            </ul>
        <?php
        }
        ?>
    </div>
<?php
}

get_footer();

?>