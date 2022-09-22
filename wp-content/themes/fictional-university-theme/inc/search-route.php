<?php

add_action('rest_api_init', 'universityRegisterSearch');

function universityRegisterSearch() {
    register_rest_route('university/v1', 'search', [
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'universitySearchResults',
    ]);
}

function universitySearchResults($data) {
    $mainQuery = new WP_Query([
        'post_type' => ['post', 'page', 'professor', 'program', 'campus', 'event'],
        's' => sanitize_text_field($data['term']),
    ]);

    $results = [
        'generalInfo' => [],
        'professors'  => [],
        'programs'    => [],
        'events'      => [],
        'campuses'    => [],
    ];

    while ($mainQuery->have_posts()) {
        $mainQuery->the_post();

        $type = match (get_post_type()) {
            'page', 'post' => 'generalInfo',
            'professor'    => 'professors',
            'program'      => 'programs',
            'event'        => 'events',
            'campus'       => 'campuses',
        };

        $results[$type][] = [
            'title'     => get_the_title(),
            'permalink' => get_the_permalink(),
        ];
    }

    return $results;
}