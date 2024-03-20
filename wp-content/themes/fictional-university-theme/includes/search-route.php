<?php
// Register custom route for search
add_action('rest_api_init', 'universityRegisterSearch');

function universityRegisterSearch()
{
  // register_rest_route($nameSpace, $route, c);
  register_rest_route('university/v1', 'search', array(
    'methods' => WP_REST_SERVER::READABLE, // 'GET'
    'callback' => 'universitySearchResults'
  ));
}
// callback function that also receives URL parameters e.g. search terms
function universitySearchResults($data)
{
  $mainQuery = new WP_Query(array(
    'post_type' => array('post', 'page', 'professor', 'program', 'campus', 'event'), // search for multiple post types
    's' => sanitize_text_field($data['term']), // s for search search keyword
  ));

  // initialize empty array for each post type
  $results = [
    'generalInfo' => array(),
    'professors' => array(),
    'programs' => array(),
    'events' => array(),
    'campuses' => array(),
  ];

  // loop through each post
  while ($mainQuery->have_posts()) {
    $mainQuery->the_post();

    // populate generalInfo array with returned posts & pages
    if (get_post_type() == 'post' || get_post_type() == 'page') {
      // push an array variable to the generalInfo array.
      array_push($results['generalInfo'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'postType' => get_post_type(),
        'authorName' => get_the_author(),
      ));
    }

    // populate professor array with 'professor' post-type
    if (get_post_type() == 'professor') {
      array_push($results['professors'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
      ));
    }

    // populate program array with 'program' post-type
    if (get_post_type() == 'program') {
      array_push($results['programs'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),

      ));
    }
    // populate event array with 'event' post-type
    if (get_post_type() == 'event') {
      array_push($results['events'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
      ));
    }
    // populate campus array with 'campus' post-type
    if (get_post_type() == 'campus') {
      array_push($results['campuses'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
      ));
    }
  }

  // WordPress automatically converts PHP data into JSON data structures when serving API requests.
  return $results;
}
