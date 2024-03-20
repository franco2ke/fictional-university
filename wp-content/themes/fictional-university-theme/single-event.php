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
        <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('event'); ?>"><i class="fa fa-home" aria-hidden="true"></i>&nbsp;Events Home</a>
        <span class="metabox__main"> <?php the_title(); ?>
      </p></span>
      </p>
    </div>
    <div class="generic-content"><?php the_content(); ?></div>
    <h2></h2>

    <?php
    $relatedPrograms = get_field('related_programs');

    if ($relatedPrograms) :
      echo '<hr class="section-break">';
      echo '<h2 class="headline headline--medium">Related Program(s)</h2>';
      echo '<ul class="link-list min-list">';

      foreach ($relatedPrograms as $program) {
    ?>
        <li><a href="<?= get_the_permalink($program) ?>"><?= get_the_title($program); ?></a></li>
        <!-- <pre>
          <?php /* print_r($program); */ ?>
        </pre> -->
    <?php
      }
    // print_r($relatedPrograms);
    endif;
    ?>
    </ul>
  </div>

<?php }
get_footer();
?>