<?php
get_header();
pageBanner(array(
  'title' => 'Past Events',
  'subtitle' => 'A recap of our past events'
));
?>

<div class="container container--narrow page-section">
  <?php
  $today = date('Ymd');

  $pastEvents = new WP_Query(
    array(
      'paged' => get_query_var('paged', 1), // get page no from url for pagination
      'post_type' => 'event',
      'orderby' => 'meta_value_num',
      'meta_key' => 'event_date',
      'order' => 'ASC', // Default order
      'meta_query' => array(
        // only return posts if event date is less than today
        array(
          'key' => 'event_date',
          'compare' => '<=',
          'value' => $today,
          'type' => 'numeric'
        )
      )
    )
  );
  while ($pastEvents->have_posts()) {
    $pastEvents->the_post();

    get_template_part('template-parts/content-event');
  }
  echo paginate_links(array(
    // get the number of pages
    'total' => $pastEvents->max_num_pages
  ));

  wp_reset_postdata();
  ?>

</div>

<?php
get_footer();
?>