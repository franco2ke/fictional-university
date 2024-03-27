<?php
// include additional code as external php file to keep functions.php organized
require get_theme_file_path('/includes/search-route.php');

// customize fields in JSON returned by WP rest api
function university_custom_rest()
{
  // create custom field to be returned by rest api for the 'post' post type
  register_rest_field('post', 'authorName', array(
    // callback function that returns the value for the new field: 'authorName'
    'get_callback' => function () {
      return get_the_author();
    }
  ));
}
add_action('rest_api_init', 'university_custom_rest');

// create customized pageBanner markup
function pageBanner($args = NULL)
{
  // guard clause
  if (!isset($args['title'])) {
    $args['title'] = get_the_title();
  };

  if (!isset($args['subtitle'])) {
    $args['subtitle'] = get_field('page_banner_subtitle');
  }

  if (!isset($args['photo'])) {
    // only run if the post has banner image, and current page is not an archive or the blog page
    if (get_field('page_banner_background_image') && !is_archive() && !is_home()) {
      // gets url of correct image size from the image array object
      $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
    } else {
      $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
    }
  }

?>
  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>)">
    </div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
      <div class="page-banner__intro">
        <p><?php echo $args['subtitle'] ?></p>
      </div>
    </div>
  </div>

<?php
}

// enqueue css and js files
function university_files()
{
  // Load map js from google maps
  wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyBozRvDjhNyHQDJWe8m6xf2Po7E59vbZUI', NULL, '1.0', true);
  // Load slider js 
  wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);

  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  // Load custom icons
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
  wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));

  // make php variable available to frontend js, via inline script
  wp_localize_script('main-university-js', 'universityData', array(
    'root_url' => get_site_url(),
    'nonce' => wp_create_nonce('wp_rest')
  ));
}

//NOTE Action 1: run this function to load the css / js files, when loading scripts
add_action('wp_enqueue_scripts', 'university_files');

function university_features()
{
  // automatically generate titles for each page, post, updates in wp_head()
  add_theme_support('title-tag');
  // enable featured images for blog posts
  add_theme_support('post-thumbnails');
  // set image sizes to extract from uploaded images, crop or not, to center of photo
  add_image_size('professorLandscape', 400, 260, true);
  add_image_size('professorPortrait', 480, 650, true);
  add_image_size('pageBanner', 1500, 350, true); // for page banners
  // add nav menu support to theme
  // register_nav_menu('headerMenuLocation', 'Header Menu Location');
  // add footer menu support to theme
  // register_nav_menu('footerLocationOne', 'Footer Menu Location One');
  // register_nav_menu('footerLocationTwo', 'Footer Menu Location Two');
}

// NOTE Action 2: Run function to enable theme feature after setting up theme, title in this case
add_action('after_setup_theme', 'university_features');

// Very powerful and modifies all queries throughout website. Used for adjusting default queries
function university_adjust_queries($query)
{
  // NOTE: 1 Modify query for archive-campus.php - retrieve all posts / no pagination
  if (!is_admin() && is_post_type_archive('campus') && $query->is_main_query()) {
    $query->set('posts_per_page', -1);
  }

  // NOTE: 2 Modify query for archive-programs.php - display programs in ascending alphabetical order
  if (!is_admin() && is_post_type_archive('program') && $query->is_main_query()) {
    $query->set('orderby', 'title');
    $query->set('order', 'ASC');
    $query->set('posts_per_page', -1);
  }

  // NOTE: 3 Modify archive page of event post type to only return future events in ascending order; closest first
  $today = date('Ymd');
  // Only run on frontend, for main/default queries, and for the archive page of the event post type only
  if (
    !is_admin() && is_post_type_archive('event')  && $query->is_main_query()
  ) {
    $query->set('orderby', 'meta_value_num');
    $query->set('meta_key', 'event_date');
    $query->set('order', 'ASC'); // earlier dates to show first
    $query->set('meta_query', array(
      // filter / only return posts where event date (custom field) is greater than or equal to today
      array(
        'key' => 'event_date',
        'compare' => '>',
        'value' => $today,
        'type' => 'numeric'
      )
    ));
  }
};


// NOTE 3 : Modify default queries for archive-events.php & archive-programs.php before loading posts
add_action('pre_get_posts', 'university_adjust_queries');

// NOTE 4 :
add_filter('acf/fields/google_map/api', 'universityMapKey');

function universityMapKey($api)
{
  $api['key'] = 'AIzaSyBozRvDjhNyHQDJWe8m6xf2Po7E59vbZUI';
  return $api;
}

// NOTE: Redirect subscriber accounts out of admin page to homepage
add_action('admin_init', 'redirectSubsToFrontend');

function redirectSubsToFrontend()
{
  $ourCurrentUser = wp_get_current_user();
  // Only redirect user to frontend if user only has subscriber role
  if (count($ourCurrentUser->roles) == 1 && $ourCurrentUser->roles[0] == 'subscriber') {
    wp_redirect(site_url('/'));
    exit;
  }
}

// NOTE: Hide top admin bar for subscriber roles
add_action('wp_loaded', 'noSubsAdminBar');

function noSubsAdminBar()
{
  $ourCurrentUserRoles = wp_get_current_user()->roles;
  if (count($ourCurrentUserRoles) == 1 && $ourCurrentUserRoles[0] == 'subscriber') {
    show_admin_bar(false);
  }
}

// NOTE: Customize Login Screen
add_filter('login_headerurl', 'ourHeaderUrl');

// Change the URL of Wordpress Login page Logo to site homepage
function ourHeaderUrl()
{
  return esc_url(site_url('/'));
}

// NOTE: Load custom css on Login screen
add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginCSS()
{
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  // Load custom icons
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
  wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
}

// NOTE: Load custom login page header title - site name
add_filter('login_headertitle', 'ourLoginTitle');

function ourLoginTitle()
{
  return get_bloginfo('name');
}
