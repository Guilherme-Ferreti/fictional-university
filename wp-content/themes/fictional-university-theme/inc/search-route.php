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

        if (get_post_type() == 'post' or get_post_type() == 'page') {
            $results['generalInfo'][] = [
                'title'      => get_the_title(),
                'permalink'  => get_the_permalink(),
                'postType'   => get_post_type(),
                'authorName' => get_the_author(),
            ];
        }

        if (get_post_type() == 'professor') {
            $results['professors'][] = [
                'title'     => get_the_title(),
                'permalink' => get_the_permalink(),
                'image'     => get_the_post_thumbnail_url(0, 'professor-landscape'), // 0 means "current post"
            ];
        }

        if (get_post_type() == 'program') {
            $results['programs'][] = [
                'id'        => get_the_ID(),
                'title'     => get_the_title(),
                'permalink' => get_the_permalink(),
            ];
        }

        if (get_post_type() == 'campus') {
            $results['campuses'][] = [
                'title'     => get_the_title(),
                'permalink' => get_the_permalink(),
            ];
        }

        if (get_post_type() == 'event') {
            $eventDate = new DateTime(get_field('event_date'));
            $description = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 18);

            $results['events'][] = [
                'title'       => get_the_title(),
                'permalink'   => get_the_permalink(),
                'month'       => $eventDate->format('M'),
                'day'         => $eventDate->format('d'),
                'description' => $description,
            ];
        }
    }

    wp_reset_postdata();

    if ($results['programs']) {
        $programsMetaQuery = ['relation' => 'OR'];

        foreach ($results['programs'] as $program) {
            $programsMetaQuery[] = [
                'key'     => 'related_programs',
                'compare' => 'LIKE',
                'value'   => '"' . $program['id'] . '"',       
            ];
        }
    
        $programRelationshipQuery = new WP_Query([
            'post_type'  => 'professor',
            'meta_query' => $programsMetaQuery,
        ]);
    
        while ($programRelationshipQuery->have_posts()) {
            $programRelationshipQuery->the_post();
    
            $results['professors'][] = [
                'title'     => get_the_title(),
                'permalink' => get_the_permalink(),
                'image'     => get_the_post_thumbnail_url(0, 'professor-landscape'), // 0 means "current post"
            ];
        }
    
        $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));
    }

    return $results;
}
