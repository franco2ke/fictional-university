<?php

get_header();

while (have_posts()) {
  // retrieve post
  the_post();

  // retrieve markup
  pageBanner();
?>

  <div class="container container--narrow page-section">
    <!-- metabox will go here -->
    <div class="metabox metabox--position-up metabox--with-home-link">
      <p>
        <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>"><i class="fa fa-home" aria-hidden="true"></i>&nbsp;All Campuses</a>
        <span class="metabox__main"> <?php the_title(); ?></span>
      </p>
    </div>
    <div class="generic-content"><?php the_content(); ?></div>

    <?php $mapLocation = get_field('map_location'); ?>

    <div class="acf-map">
      <div class="marker" data-lat="<?php echo $mapLocation['lat'] ?>" data-lng="<?php echo $mapLocation['lng'] ?>">
        <h3><?php the_title(); ?></h3>
        <?php echo $mapLocation['address']; ?>
      </div>
    </div>

    <?php
    // List programs available at the current Campus
    $relatedPrograms = new WP_Query(array(
      'posts_per_page' => -1,
      'post_type' => 'program',
      'orderby' => 'title', // order by custom field or inbuilt field
      'order' => 'ASC',
      'meta_query' => array(
        // filter: only return programs whose related_campus field includes current campus
        array(
          'key' => 'related_campus', //related campus holds an object, which is stored in a serialized manner in database
          'compare' => 'LIKE', // search for value in key of serialized object / data
          'value' => '"' . get_the_ID() . '"' // ID of current campus
        )
      ),
    ));

    // Only Show related programs + heading if related programs are actually present
    if ($relatedPrograms->have_posts()) {
      echo '<hr class="section-break">';
      echo '<h2 class="headline headline--medium">Programs Available At This Campus</h2>';
      echo '<ul class="min-list link-list">';

      while ($relatedPrograms->have_posts()) {
        // retrieve the current post object
        $relatedPrograms->the_post();
    ?>
        <li>
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </li>
    <?php
      };
      echo '</ul>';
    };

    // reset the global post object and query variables to the default query particulars
    wp_reset_postdata();
    ?>

  </div>

<?php }
get_footer();
?>