<?php

use const Avifinfo\FOUND;

add_action('rest_api_init', 'universityLikeRoutes');

function universityLikeRoutes()
{
  // register route for liking and unliking professors
  register_rest_route('university/v1', 'manageLike', array(
    'methods' => 'POST', // POST, Create Like
    'callback' => 'createLike'
  ));

  // register route for liking and unliking professors
  register_rest_route('university/v1', 'manageLike', array(
    'methods' => 'DELETE', // DELETE Like
    'callback' => 'deleteLike'
  ));
}

function createLike($data)
{
  // only work if user logged in
  if (is_user_logged_in()) {
    $professor = sanitize_text_field($data['professorId']);

    // check if user has already liked the professor
    $existQuery = new WP_Query(array(
      'author' => get_current_user_id(),
      'post_type' => 'like',
      'meta_query' => array(
        array(
          'key' => 'liked_professor_id',
          'compare' => '=',
          'value' => $professor
        )
      )
    ));

    // only like a professor once for each user, and 
    // only like professors, not other post types
    if ($existQuery->found_posts == 0 && get_post_type($professor) == 'professor') {
      // programmatically create post in php
      return wp_insert_post(
        array(
          'post_type' => 'like',
          'post_status' => 'publish',
          'post_title' => 'professor ❤️',
          'meta_input' => array(
            'liked_professor_id' => $professor
          ),
        )
      );
    } else {
      die("Invalid professor id");
    }
  } else {
    die("Only logged in users can create a like");
  }
}

function deleteLike($data)
{
  $likeId = sanitize_text_field($data['like']);
  // only delete the like if the current_user has the same ID as the author of the like post to be deleted
  if (get_current_user_id() == get_post_field('post_author', $likeId) && get_post_type($likeId) == 'like') {
    wp_delete_post($likeId, true);
    return 'Congrats, Like delted';
  } else {
    die("You do not have permission to delete that");
  }
}
