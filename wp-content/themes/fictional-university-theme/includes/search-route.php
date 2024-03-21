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
        'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
      ));
    }

    // populate program array with 'program' post-type
    if (get_post_type() == 'program') {
      array_push($results['programs'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'id' => get_the_id(),
      ));
    }
    // populate event array with 'event' post-type
    if (get_post_type() == 'event') {
      // get event date
      $eventDate = new DateTime(get_field('event_date'));
      // get description
      $description = null;

      if (has_excerpt()) { // if post has handcrafted excerpt
        $description = get_the_excerpt() . '...';
      } else {
        $description = wp_trim_words(get_the_content(), 18); // get the first 18 words of the content
      };

      array_push($results['events'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'month' => $eventDate->format('M'),
        'day' => $eventDate->format('d'),
        'description' => $description,
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

  // 2nd query to post professors teaching specific programs, only runs if programs array has values
  if ($results['programs']) {
    // wp_reset_postdata();
    // OR relation -> match any query not all queries which is the default for multiple meta queries
    $programsMetaQuery = array('relation' => 'OR');
    // programmatically create metaquery to filter/return professors teaching programs returned by search
    foreach ($results['programs'] as $item) {
      array_push($programsMetaQuery, [
        'key' => 'related_programs',
        'compare' => 'LIKE',
        'value' => '"' . $item['id'] . '"',
      ]);
    }

    $programRelationshipQuery = new WP_Query(array(
      'post_type' => 'professor',
      'meta_query' => $programsMetaQuery,
    ));

    // push professors teaching programs to professor results array
    while ($programRelationshipQuery->have_posts()) {
      $programRelationshipQuery->the_post();

      // populate professor array with 'professor' post-type
      if (get_post_type() == 'professor') {
        array_push($results['professors'], array(
          'title' => get_the_title(),
          'permalink' => get_the_permalink(),
          'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
        ));
      }
    }

    // remove duplicates in professor results array, and return array_values only, not key,val pairs
    $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));
  }
  // WordPress automatically converts PHP data into JSON data structures when serving API requests.
  return $results;
}
