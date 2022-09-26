<?php

require get_theme_file_path('/inc/search-route.php');

function university_custom_rest() {
    register_rest_field('post', 'authorName', [
        'get_callback' => fn () => get_the_author(),
    ]);

    register_rest_field('note', 'userNoteCount', [
        'get_callback' => fn () => count_user_posts(get_current_user_id(), 'note'),
    ]);
}

add_action('rest_api_init', 'university_custom_rest');

function pageBanner(array $args = []) {
    if (! isset($args['title'])) {
        $args['title'] = get_the_title();
    }

    if (! isset($args['subtitle'])) {
        $args['subtitle'] = get_field('page_banner_subtitle');
    }

    if (! isset($args['image'])) {
        $pageBannerImage = get_field('page_banner_background_image');

        if ($pageBannerImage && !is_archive() && !is_home()) {
            $args['image'] = $pageBannerImage['sizes']['page-banner'];
        } else {
            $args['image'] = get_theme_file_uri('/images/ocean.jpg');
        }
    }
?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['image']; ?>)"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
            <div class="page-banner__intro">
                <p><?php echo $args['subtitle'] ?></p>
            </div>
        </div>
    </div>
<?php
}

function university_files() {
    wp_enqueue_script('google-map', '//maps.googleapis.com/maps/api/js?key=YOUR_API_KEY_HERE', null, '1.0', true);
    wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), ['jquery'], '1.0', true);
    wp_enqueue_style('custom-google-fonts', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));

    wp_localize_script('main-university-js', 'universityData', [
        'root_url' => get_site_url(), // Any variables can be informed
        'nonce'    => wp_create_nonce('wp_rest'),
    ]);
}

add_action('wp_enqueue_scripts', 'university_files');

function university_features() {
    // register_nav_menu('headerMenuLocation', 'Header Menu Location');
    // register_nav_menu('footerExploreLocation', 'Footer Explore Menu Location');
    // register_nav_menu('footerLearnLocation', 'Footer Learn Menu Location');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professor-landscape', 400, 260, true);
    add_image_size('professor-portrait', 468, 650, true);
    add_image_size('page-banner', 1500, 350, true);
}

add_action('after_setup_theme', 'university_features');

function university_adjust_queries($query) {
    if (!is_admin() && is_post_type_archive('event') && $query->is_main_query()) {
        $today = date('Ymd');
        
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', [
            [
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric',
            ],
        ]);
    }

    if (!is_admin() && is_post_type_archive('program') && $query->is_main_query()) {
        $query->set('posts_per_page', -1);
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
    }

    if (!is_admin() && is_post_type_archive('campus') && $query->is_main_query()) {
        $query->set('posts_per_page', -1);
    }
}

add_action('pre_get_posts', 'university_adjust_queries');

function universityMapKey($api) {
    $api['key'] = 'YOUR_API_KEY_HERE';

    return $api;
}

add_filter('acf/fields/google_map/api', 'universityMapKey');

// Redirect subscribers accounts out of admin and onto homepage
add_action('admin_init', 'redirect_subscribers_to_frontend');

function redirect_subscribers_to_frontend() {
    $current_user = wp_get_current_user();

    if (count($current_user->roles) == 1 && $current_user->roles[0] == 'subscriber') {
        wp_redirect(site_url('/'));
        exit;
    }
}

add_action('wp_loaded', 'hide_admin_bar_to_subscribers');

function hide_admin_bar_to_subscribers() {
    $current_user = wp_get_current_user();

    if (count($current_user->roles) == 1 && $current_user->roles[0] == 'subscriber') {
        show_admin_bar(false);
    }
}

// Customize Login Screen
add_filter('login_headerurl', 'our_login_header_url');

function our_login_header_url() {
    return esc_url(site_url('/'));
}

add_action('login_enqueue_scripts', 'our_login_css');

function our_login_css() {
    wp_enqueue_style('custom-google-fonts', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
}

add_filter('login_headertitle', 'our_login_header_title');

function our_login_header_title() {
    return get_bloginfo('name');
}

// Force note posts to be private
add_filter('wp_insert_post_data', 'make_note_private', 10, 2);

function make_note_private($data, $post_array) {
    if ($data['post_type'] == 'note') {
        if (count_user_posts(get_current_user_id(), 'note') > 10 && $post_array['ID']) {
            die('You have reached you note limit.');
        }

        $data['post_title'] = sanitize_text_field($data['post_title']);
        $data['post_content'] = sanitize_textarea_field($data['post_content']);
    }

    if ($data['post_type'] == 'note' && $data['post_status'] != 'trash') {
        $data['post_status'] = 'private';
    }

    return $data;
}