<?php

get_header();

while (have_posts()) {
    the_post();

    pageBanner();
?>
    <div class="container container--narrow page-section">
        <?php
        $parent_post_id = wp_get_post_parent_id(get_the_ID());

        if ($parent_post_id) {
        ?>
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p>
                    <a class="metabox__blog-home-link" href="<?php echo get_permalink($parent_post_id) ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($parent_post_id) ?></a> <span class="metabox__main"><?php the_title(); ?></span>
                </p>
            </div>
        <?php
        }

        $children_pages = get_pages([
            'child_of' => get_the_ID(),
        ]);

        if ($parent_post_id || $children_pages) {
        ?>
            <div class="page-links">
                <h2 class="page-links__title"><a href="<?php echo get_permalink($parent_post_id) ?>"><?php echo get_the_title($parent_post_id) ?></a></h2>
                <ul class="min-list">
                    <?php
                    $find_children_of = (bool) $parent_post_id ? $parent_post_id : get_the_ID();

                    wp_list_pages([
                        'title_li' => null,
                        'child_of' => $find_children_of,
                        'sort_column' => 'menu_order',
                    ]);
                    ?>
                </ul>
            </div>
        <?php
        }
        ?>

        <div class="generic-content">
            <?php get_search_form(); ?>
        </div>
    </div>
<?php
}

get_footer();

?>