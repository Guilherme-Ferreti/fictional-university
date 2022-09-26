<?php

function university_post_types() {
    register_post_type('event', [
        'show_in_rest' => true,
        'supports'     => ['title', 'editor', 'excerpt'],
        'rewrite'      => [
            'slug' => 'events',
            'with_front' => false, // True if the URL should be prepended with post type URI (Ex: /posts/events/event-123)
        ],
        'capability_type' => 'event',
        'map_meta_cap'    => true,
        'has_archive'     => true,
        'public'          => true,
        'labels'          => [
            'name'          => 'Events',
            'add_new_item'  => 'Add New Event',
            'edit_item'     => 'Edit Event',
            'all_items'     => 'All Events',
            'singular_name' => 'Event',
        ],
        'menu_icon' => 'dashicons-calendar',
    ]);

    register_post_type('program', [
        'show_in_rest' => true,
        'supports'     => ['title'],
        'rewrite'      => [
            'slug' => 'programs',
            'with_front' => false,
        ],
        'has_archive' => true,
        'public'      => true,
        'labels'      => [
            'name'          => 'Programs',
            'add_new_item'  => 'Add New Program',
            'edit_item'     => 'Edit Program',
            'all_items'     => 'All Programs',
            'singular_name' => 'Program',
        ],
        'menu_icon' => 'dashicons-awards',
    ]);

    register_post_type('professor', [
        'show_in_rest' => true,
        'supports'     => ['title', 'editor', 'thumbnail'],
        'rewrite'      => [
            'with_front' => false,
        ],
        'public'      => true,
        'labels'      => [
            'name'          => 'Professor',
            'add_new_item'  => 'Add New Professor',
            'edit_item'     => 'Edit Professor',
            'all_items'     => 'All Professors',
            'singular_name' => 'Professor',
        ],
        'menu_icon' => 'dashicons-welcome-learn-more',
    ]);

    register_post_type('campus', [
        'show_in_rest' => true,
        'supports'     => ['title', 'editor', 'excerpt'],
        'rewrite'      => [
            'slug' => 'campuses',
            'with_front' => false,
        ],
        'capability_type' => 'campus',
        'map_meta_cap'    => true,
        'has_archive'     => true,
        'public'          => true,
        'labels'          => [
            'name'          => 'Campuses',
            'add_new_item'  => 'Add New Campus',
            'edit_item'     => 'Edit Campus',
            'all_items'     => 'All Campuses',
            'singular_name' => 'Campus',
        ],
        'menu_icon' => 'dashicons-location-alt',
    ]);

    register_post_type('note', [
        'show_in_rest'    => true,
        'supports'        => ['title', 'editor'],
        'public'          => false,
        'capability_type' => 'note',
        'map_meta_cap'    => true,
        'show_ui'         => true,
        'labels'          => [
            'name'          => 'Notes',
            'add_new_item'  => 'Add New Note',
            'edit_item'     => 'Edit Note',
            'all_items'     => 'All Notes',
            'singular_name' => 'Note',
        ],
        'menu_icon' => 'dashicons-welcome-write-blog',
    ]);
}

add_action('init', 'university_post_types');
