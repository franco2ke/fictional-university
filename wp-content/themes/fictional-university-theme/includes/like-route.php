<?php

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
  $professor = sanitize_text_field($data['professorId']);
  // programmatically create post in php
  wp_insert_post(
    array(
      'post_type' => 'like',
      'post_status' => 'publish',
      'post_title' => '2nd PHP Post Test',
      'meta_input' => array(
        'liked_professor_id' => $professor
      ),

    )
  );
}

function deleteLike()
{
  return 'Thanks for trying to delete a like';
}
