<?php
get_header();

while (have_posts()) {
  the_post();

  pageBanner();
?>



  <div class="container container--narrow page-section">
    <?php
    // wp_get_post_parent_id() returns parent page id or 0 if none
    $theParent = wp_get_post_parent_id();
    // only show metabox if current page is a child page
    if ($theParent) {
    ?>
      <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
          <a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($theParent); ?></a> <span class="metabox__main"><?php the_title(); ?></span>
        </p>
      </div>

    <?php  }   ?>

    <?php

    // array will be empty, return Null,0 if page has no children; is not a parent
    $isParentArray = get_pages(
      array(
        'child_of' => get_the_ID(),
      )
    );

    // Only show child menu if page is a child page or parent page
    if ($theParent or $isParentArray) { ?>

      <div class="page-links">
        <h2 class="page-links__title"><a href="<?php echo get_permalink($theParent); ?>"><?php echo get_the_title($theParent); ?></a></h2>
        <ul class="min-list">
          <?php
          if ($theParent) {
            $findChildrenOf = $theParent;
          } else {
            $findChildrenOf = get_the_ID();
          }

          wp_list_pages(array(
            'title_li' => NULL,
            'child_of' => $findChildrenOf,
            'sort_column' => 'menu_order'
          ));
          ?>
          <!--  <li class="current_page_item"><a href="#">Our History</a></li>
          <li><a href="#">Our Goals</a></li> -->
        </ul>
      </div>

    <?php } ?>

    <div class="generic-content">
      <?php the_content(); ?>
    </div>
  </div>



<?php
}
get_footer();
?>