<?php

add_action('rest_api_init', 'university_like_routes');

function university_like_routes() {
    register_rest_route('university/v1', 'manageLike', [
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'create_like',
    ]);

    register_rest_route('university/v1', 'manageLike', [
        'methods' => WP_REST_Server::DELETABLE,
        'callback' => 'delete_like',
    ]);
}

function create_like($data) {
    if (! is_user_logged_in()) {
        die('Only logged in user can create a like.');
    }

    $professor_id = sanitize_text_field($data['professorId']);

    if (get_post_type($professor_id) != 'professor') {
        die('Invalid professor ID.');
    }

    $like_exists_query = new WP_Query([
        'author_id'  => get_current_user_id(),
        'post_type'  => 'like',
        'meta_query' => [
            [
                'key'     => 'liked_professor_id',
                'compare' => '=',
                'value'   => $professor_id,
            ],
        ],
    ]);

    if ($like_exists_query->found_posts) {
        die('Professor already liked.');
    }

    return wp_insert_post([
        'post_type'   => 'like',
        'post_status' => 'publish',
        'meta_input'  => [
            'liked_professor_id' => $professor_id,
        ],
    ]);
}

function delete_like($data) {
    $like_id = sanitize_text_field($data['like']);
    
    if (get_current_user_id() == get_post_field('post_author', $like_id) && get_post_type($like_id) == 'like') {
        wp_delete_post($like_id, true);

        return 'Liked deleted.';
    } else {
        die('You do not have permission to delete that.');
    }
}
