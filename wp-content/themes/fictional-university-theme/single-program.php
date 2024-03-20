<?php

get_header();

while (have_posts()) {
  the_post();

  pageBanner();
?>

  <div class="container container--narrow page-section">
    <!-- metabox will go here -->
    <div class="metabox metabox--position-up metabox--with-home-link">
      <p>
        <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>"><i class="fa fa-home" aria-hidden="true"></i>&nbsp;All Programs</a>
        <span class="metabox__main"> <?php the_title(); ?></span>
      </p>
    </div>
    <div class="generic-content"><?php the_content(); ?></div>

    <?php
    // List professors who teach the current subject
    // Custom post type (professors) query, sorted by custom field value (meta_key)
    $relatedProfessors = new WP_Query(array(
      'posts_per_page' => -1,
      'post_type' => 'professor',
      'orderby' => 'title', // order by custom field or inbuilt field
      'order' => 'ASC',
      'meta_query' => array(
        // only return professors whose related_programs field includes current program
        array(
          'key' => 'related_programs', //related program is an object therefore stored in a serialized manner in database
          'compare' => 'LIKE', // search for value in key of serialized object / data
          'value' => '"' . get_the_ID() . '"' // ID of current program
        )
      ),
    ));

    // Only Show related events content + heading if related events present
    if ($relatedProfessors->have_posts()) {
      echo '<hr class="section-break">';
      echo '<h2 class="headline headline--medium">' . get_the_title() . ' Professors</h2>';

      echo '<ul class="professor-cards">';

      while ($relatedProfessors->have_posts()) {
        $relatedProfessors->the_post();
    ?>
        <li class="professor-card__list-item">
          <a class="professor-card" href="<?php the_permalink(); ?>">
            <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); ?>" alt="">
            <span class="professor-card__name"><?php the_title(); ?></span>
          </a>
        </li>
      <?php
      };
      echo '</ul>';
    };

    // reset the global post object and query variables to the default query particulars
    wp_reset_postdata();


    $today = date('Ymd');
    // Custom post type query, sorted by custom field value (meta_key)
    $homepageEvents = new WP_Query(array(
      'posts_per_page' => 2,
      'post_type' => 'event',
      'orderby' => 'meta_value_num', // order by custom field of type number
      'meta_key' => 'event_date', // name of custom field to order by
      'order' => 'ASC',
      'meta_query' => array(
        // only return posts if event date is greater than today
        array(
          'key' => 'event_date',
          'compare' => '>',
          'value' => $today,
          'type' => 'numeric'
        ),
        // only return events whose related_programs field includes current program
        array(
          'key' => 'related_programs', //related program is an object therefore stored in a serialized manner in database
          'compare' => 'LIKE', // search for value in key of serialized object / data
          'value' => '"' . get_the_ID() . '"' // ID of current program
        )
      ),
    ));

    // Only Show related events content + heading if related events present
    if ($homepageEvents->have_posts()) {
      echo '<hr class="section-break">';
      echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h2>';

      while ($homepageEvents->have_posts()) {
        $homepageEvents->the_post();

        get_template_part('template-parts/content-event');
      }
    }
    // reset the global post object and query variables to the default query particulars
    wp_reset_postdata();

    $relatedCampuses = get_field('related_campus');

    if ($relatedCampuses) {
      echo '<hr class="section-break">';
      echo '<h2 class="headline headline--medium">' . get_the_title() . ' is Available At These Campuses:</h2>';
    }
    echo '<ul class="min-list link-list">';
    foreach ($relatedCampuses as $campus) {
      ?>
      <li><a href="<?php the_permalink($campus); ?>"><?php echo get_the_title($campus); ?></a></li>
    <?php } ?>
  </div>
<?php }
get_footer();
?>