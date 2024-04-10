<?php
// NOTE Action: hook on the init action hook to register a post type
add_action('init', 'university_post_types');
// function: register a custom post type
function university_post_types()
{
  // Campus Post Type
  register_post_type('campus', array(
    'capability_type' => 'campus',
    'map_meta_cap' => 'true',
    'supports' => array('title', 'editor', 'excerpt'),
    'has_archive' => true, // supports archive template
    'public' => true, // post type visible to editors and authors of the website
    'show_in_rest' => 'true', // edit via Block Editor instead of classic editor, show in REST API
    'labels' => array(
      'name' => 'Campuses', // set the admin sidebar label name
      'add_new' => 'Add New Campus',
      'edit_item' => 'Edit Campus',
      'all_items' => 'All Campuses',
      'singular_name' => 'Campus'
    ),
    'menu_icon' => 'dashicons-location-alt', // set the menu icon
  ));

  // Event Post Type
  register_post_type('event', array(
    'capability_type' => 'event', // enable event related permissions
    'map_meta_cap' => true, // map default post permissions to this post type
    'supports' => array('title', 'editor', 'excerpt'),
    'rewrite' => array('slug' => 'events'), // rewrite the url slug /event/ -> /events/
    'has_archive' => true, // supports archive template
    'public' => true, // post type visible to editors and authors of the website
    'show_in_rest' => 'true', // edit via Block Editor instead of classic editor
    'labels' => array(
      'name' => 'Events', // set the admin sidebar label name
      'add_new' => 'Add New Event',
      'edit_item' => 'Edit Event',
      'all_items' => 'All Events',
      'singular_name' => 'Event'
    ),
    'menu_icon' => 'dashicons-calendar', // set the menu icon
  ));

  // Program Post Type
  register_post_type('program', array(
    'supports' => array('title', 'editor'),
    'rewrite' => array('slug' => 'programs'), // rewrite the url slug /program/ -> /programs/
    'has_archive' => true, // supports archive template
    'public' => true, // post type visible to editors and authors of the website
    'show_in_rest' => 'true', // edit via Block Editor instead of classic editor
    'labels' => array(
      'name' => 'Programs', // set the admin sidebar label name
      'add_new' => 'Add New Program',
      'edit_item' => 'Edit Program',
      'all_items' => 'All Programs',
      'singular_name' => 'Program'
    ),
    'menu_icon' => 'dashicons-awards', // set the menu icon
  ));

  // Professor Post Type
  register_post_type('professor', array(
    'supports' => array('title', 'editor', 'thumbnail'),
    'public' => true, // post type visible to editors and authors of the website
    'show_in_rest' => 'true', // edit via Block Editor instead of classic editor
    'labels' => array(
      'name' => 'Professors', // set the admin sidebar label name
      'add_new' => 'Add New Professor',
      'edit_item' => 'Edit Professor',
      'all_items' => 'All Professors',
      'singular_name' => 'Professor'
    ),
    'menu_icon' => 'dashicons-welcome-learn-more', // set the menu icon
  ));

  // Note Post Type
  register_post_type('note', array(
    'capability_type' => 'note', // brand new permissions for this post_type only
    'map_meta_cap' => 'true', // enforces the new custom permissions
    'show_in_rest' => 'true', // edit via Block Editor instead of classic editor
    'supports' => array('title', 'editor', 'author'),
    'public' => false, // not visible to editors and authors of the website on dashboard, private to each user
    'show_ui' => true, // show notes in the admin dashboard
    'labels' => array(
      'name' => 'Notes', // set the admin sidebar label name
      'add_new' => 'Add New Note',
      'edit_item' => 'Edit Note',
      'all_items' => 'All Notes',
      'singular_name' => 'Note'
    ),
    'menu_icon' => 'dashicons-welcome-write-blog', // set the menu icon
  ));

  // Like Post Type , with custom rest api, manual 
  register_post_type('like', array(
    'supports' => array('title', 'author'),
    'public' => false, // not visible to editors and authors of the website on dashboard, private to each user
    'show_ui' => true, // show likes in the admin dashboard
    'labels' => array(
      'name' => 'Likes', // set the admin sidebar label name
      'add_new' => 'Add New Like',
      'edit_item' => 'Edit Like',
      'all_items' => 'All Likes',
      'singular_name' => 'Like'
    ),
    'menu_icon' => 'dashicons-heart', // set the menu icon
  ));
};
