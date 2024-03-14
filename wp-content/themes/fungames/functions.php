<?php
/**
 * FunGames
 *
 * @author Daniel Bakovic
 * @uri https://myarcadeplugin.com
 *
 * @package WordPress
 * @subpackage FunGames
 */

define ( 'MYAPB_THEMEVERSION', '5.8.0');
define ( 'MYAPB_THEMENAME', 'Fungames');

// Activate this if you want to use development CSS and JS files instead of
// compressed files. ONLY FOR DEVELOPMENT PURPOSE!! Don't use on live sites!
define ( 'FUNGAMES_DEVELOP', false);

// FunGames only works in WordPress 4.1 or later
if ( version_compare( $GLOBALS['wp_version'], '4.1-alpha', '<') ) {
  require( get_template_directory() . '/inc/back-compat.php' );
}

/** Include MyArcadePlugin Theme API **/
include_once( get_template_directory() . '/inc/myarcade_api.php' );
/** Include Theme Setup **/
include_once( get_template_directory() . '/inc/setup.php' );
/** Include MyArcadePlugin Theme Options **/
include_once( get_template_directory() . '/inc/myabp_themeoptions.php' );
/** Include MyArcadePlugin Theme Default Settings **/
include_once( get_template_directory() . '/inc/myabp_settings.php' );
/** Include custom widgets **/
include_once( get_template_directory() . '/inc/widgets.php' );
/** Include theme actions API **/
include_once( get_template_directory() . '/inc/actions.php' );
// Include BuddyPress integration
include_once( get_template_directory() . '/inc/buddypress.php' );

add_action('init', 'fungames_init', 0);

add_action( 'widgets_init', 'fungames_widgets_init' );
add_action( 'wp_head', 'fungames_wp_head' );
add_action( 'wp_footer', 'fungames_wp_footer' );
add_action( 'wp_enqueue_scripts', 'fungames_scripts' );

// Add filter for blog template
add_filter('single_template', 'fungames_blog_template');

/**
 * Fungames activation function
 */
function fungames_activation( $oldname, $oldtheme = false ) {
  add_rewrite_endpoint( 'play', EP_PERMALINK );
  add_rewrite_endpoint('fullscreen', EP_PERMALINK);
  flush_rewrite_rules();
}
add_action('after_switch_theme', 'fungames_activation', 0);

/**
 * FunGames init function - called when WordPress is initialized
 */
function fungames_init() {
  // Check if pre-game page is enabled
  if ( get_option('fungames_pregame_page') == 'enable' ) {
    $endpoint = get_option('fungames_endpoint_play');
    if ( empty($endpoint) ) $endpoint = 'play';
    add_rewrite_endpoint( $endpoint, EP_PERMALINK );
    add_action( 'template_redirect', 'fungames_play_template_redirect' );
  }

  // Check if fullscreen option is enabled
  if ( get_option('fungames_button_fullscreen') == 'enable' ) {
    add_rewrite_endpoint('fullscreen', EP_PERMALINK);
    add_action('template_redirect', 'fungames_fullscreen_teplate_redirect');
  }
}

/**
 * Sets the various customised styling according to the options set for the theme
 */
function fungames_wp_head() {

  // Display custom Header code here (NO HTML OR TEXT!)
  if ( get_option('fungames_custom_headercode_status') == 'enable' ) {
    echo stripslashes( get_option('fungames_custom_headercode') );
  }

  if ( 'enable' == get_option('fungames_custom_favicon_status') && get_option('fungames_custom_favicon') ) {
    echo '<link rel="shortcut icon" type="image/x-icon" href="' . get_option('fungames_custom_favicon') . '" />';
  }

  $background = get_theme_mod('background_image', false);
  $bgcolor = get_theme_mod('background_color', false);

  if (!$background && $bgcolor) {
    echo '<style type="text/css">body { background-image:none; }</style>';
  }

  // Print game schema
  fungames_schema();

  // Open Graph Meta Tags
  if ( is_single() ) {
    $post_id = get_the_ID();

    if ( $post_id ) {
      $thumbnail_id = get_post_thumbnail_id();
      $thumbnail = '';

      if ( ! empty( $thumbnail_id ) ) {
        $thumbnail_array = wp_get_attachment_image_src( $thumbnail_id );
        if ( ! empty( $thumbnail_array ) ) {
          $thumbnail = $thumbnail_array[0];
        }
      }

      if ( ! $thumbnail ) {
        $thumbnail = get_post_meta($post_id, "mabp_thumbnail_url", true);
      }

      if ( $thumbnail ) {
        ?>
        <meta property="og:image" content="<?php echo $thumbnail; ?>" />
        <?php
      }
    }
  }
}

/**
 * Hook into wp_footer
 *
 * @version 5.2.0
 * @since   5.2.0
 * @access  public
 * @return  void
 */
function fungames_wp_footer() {

  // custom footer code
  if ( get_option('fungames_custom_footercode_status') == 'enable' ) {
    echo stripslashes(get_option('fungames_custom_footercode'));
  }
}

/**
 * Generate the copyright text
 *
 */
function fungames_copyright() {

  $credit = get_option('fungames_footer_credit');

  if ( empty( $credit ) ) {
    return;
  }
  ?>
  <div class="footer-copyright">
    <?php echo stripslashes($credit); ?>
  </div>
  <?php
}

/**
 * Handles game display when user comes from the pre-game page (game landing page)
 *
 * @global type $wp_query
 * @return type
 */
function fungames_play_template_redirect() {
  global $wp_query;

  $endpoint = get_option('fungames_endpoint_play');
  if ( empty($endpoint) ) return;

  // if this is not a request for game play then bail
  if ( !is_singular() || !isset($wp_query->query_vars[$endpoint]) ) {
    return;
  }

  // Include game play template
  include dirname( __FILE__ ) . '/single-play.php';
  exit;
}

/**
 * Handles full screen redirect
 *
 * @global type $wp_query
 * @return type
 */
function fungames_fullscreen_teplate_redirect() {
  global $wp_query;

  // if this is not a fullscreen request then bail
  if ( !is_singular() || !isset($wp_query->query_vars['fullscreen']) ) {
    return;
  }

  // Include fullscreen template
  include dirname( __FILE__ ) . '/single-fullscreen.php';
  exit;
}

/**
 * Generate play permalink
 *
 * @return type
 */
function fungames_play_link() {
  $endpoint = get_option('fungames_endpoint_play');
  if ( empty($endpoint) ) return;
  ?>
  <br />
  <a href="<?php echo get_permalink() . $endpoint . '/'; ?>" title="<?php echo esc_attr__("Play", "fungames"); ?> <?php the_title_attribute(); ?>" rel="bookmark nofollow" class="btn-play">
    <?php esc_html_e("Play Game", "fungames"); ?>
  </a>
  <?php
}

/**
 * Enqueue scripts and styles
 *
 * @version 5.2.0
 * @since   5.2.0
 * @access  public
 * @return  void
 */
function fungames_scripts() {

  $prefix = '';

  // Load our main stylesheet.
  if ( defined('FUNGAMES_DEVELOP') && FUNGAMES_DEVELOP ) {
    // Uncompressed style for development
    wp_enqueue_style( 'fungames-style', get_stylesheet_directory_uri() . '/css/style.css' );
  }
  else {
    // Compressed css
    $prefix = '.min';
    wp_enqueue_style( 'fungames-style', get_stylesheet_uri() );
  }

  wp_enqueue_style( 'fungames-color-style', get_stylesheet_directory_uri() . '/css/color-' . get_option('fungames_color_scheme') . $prefix . '.css' );

  $box_design = get_option('fungames_box_design');
  if ( empty($box_design) ) $box_design = 'Vertical';

  wp_enqueue_style( 'fungames-box-design-style', get_stylesheet_directory_uri() . '/css/box-' . $box_design . $prefix . '.css' );

  //
  // Scripts
  wp_enqueue_script( 'fungames-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery') );

  if ( get_option('fungames_lazy_load') == 'enable' ) {
    wp_enqueue_script( 'lazyload', get_template_directory_uri() . '/js/jquery.lazyload.min.js', array('jquery'), '', true );
  }

  // Do this only on the front page
  if ( is_front_page() || is_home() ) {
    // Which slider should be displayed..
    if ( get_option('fungames_nivoslider') == 'enable' ) {
      // load bxslider
      wp_enqueue_script('bxslider', get_template_directory_uri() . '/js/jquery.bxslider.min.js', '', true );
    }

    if ( get_option('fungames_step_carousel') == 'enable' ) {
      // load belt slider script
      wp_enqueue_script('fungames-belt-slider-script', get_template_directory_uri() . '/js/scroll.js' );
    }

    if ( get_option('fungames_games_box') == 'enable' ) {
      // load sortable box script
      wp_enqueue_script('fungames-sortable-script', get_template_directory_uri() . '/js/sortable-box.js' );
    }
  }

  if ( is_singular() ) {
    if ( get_option( 'fungames_button_lights' ) == 'enable' ) {
      wp_enqueue_script( 'fungames-lights-script', get_template_directory_uri() . '/js/lights.js' );
    }

    if ( function_exists('wpfp_link') ) {
      wp_enqueue_script('fungames_favorites',get_template_directory_uri() . '/js/favorites.js' );
    }

    wp_enqueue_script('fungames_resize',get_template_directory_uri() . '/js/resize.js' );

    if ( comments_open() && get_option( 'thread_comments' ) ) {
      wp_enqueue_script( 'comment-reply' );
    }
  }
}

function fungames_calculate_height($fungames_box_count) {

  switch ( get_option('fungames_box_design') ) {
    case 'Vertical': {
      $count = round(intval($fungames_box_count) / 2);
      $height = 60 + 110 * $count;
      return 'style="height:'.$height.'px;"';
    } break;

    case 'Half': {
      $count = round(intval($fungames_box_count) );
      $height = 65 + 110 * $count;
      return 'style="height:'.$height.'px;"';
    } break;

    case 'Miniclip': {
      $count = round(intval($fungames_box_count) );
      $height = 155 + 20 * $count;
      return 'style="height:'.$height.'px;"';
    } break;

    default: {
      return false;
    }
  }

 return '';
}

function fungames_default_header_menu() {
  echo '<ul>';
  wp_list_categories('sort_column=name&title_li=&depth=1');
  echo '</ul>';
}

if ( !function_exists('fungames_favorite_link') ) {
  function fungames_favorite_link($post_id, $opt, $action) {
    $img = get_template_directory_uri().'/images/'.$action.'.png';
    $link  = "<img src='".get_stylesheet_directory_uri()."/images/loading_small.gif' alt='Loading' title='Loading' class='fungames-fav-img' />";
    $link .= "<a href='?wpfpaction=".$action."&amp;postid=".$post_id."' title='". $opt ."' rel='nofollow' class='fungames-favorites-link'><img src='".$img."' title='".$opt."' alt='".$opt."' class='favoritos fungames-fav-link' /></a>";
    $link = apply_filters( 'wpfp_link_html', $link );
    return $link;
  }
}

if ( !function_exists('fungames_favorite') ) {
  function fungames_favorite() {
    global $post, $action;
    // Works only when WP Favorite Post is active
    if (function_exists('wpfp_link')) {
      if ($action == "remove") {
        $str = fungames_favorite_link($post->ID, wpfp_get_option('remove_favorite'), "remove");
       } elseif ($action == "add") {
        $str = fungames_favorite_link($post->ID, wpfp_get_option('add_favorite'), "add");
       } elseif (wpfp_check_favorited($post->ID)) {
        $str = fungames_favorite_link($post->ID, wpfp_get_option('remove_favorite'), "remove");
       } else {
        $str = fungames_favorite_link($post->ID, wpfp_get_option('add_favorite'), "add");
       }
       echo $str;
    }
  }
}

/**
 * Check if at least one button is active and return true / false
 *
 * @return bool
 */
function fungames_display_buttons() {

  if ( (get_option('fungames_button_fullscreen') == 'enable')
     ||(get_option('fungames_button_lights') == 'enable')
     ||(get_option('fungames_button_favorite') == 'enable') ) {
    return true;
  }
  return false;
}

if ( !function_exists('fungames_blog_template') ) {
  /**
  * Blog template redirection
  */
  function fungames_blog_template($template) {
    global $post;

    // Get the blog category
    $blog_cat = get_option('fungames_blog_category');

    if ($blog_cat == '-- none --') return $template;

    $blog_category = get_cat_ID( $blog_cat );
    $post_cat = get_the_category();


    if ( is_singular() && !empty($post_cat) && ( in_category($blog_category) || ($blog_category == $post_cat[0]->category_parent) ) ) {
      // overwrite the template file if exist
      if ( file_exists(get_template_directory() . '/template-blog-post.php' ) ) {
        $template = get_template_directory() . '/template-blog-post.php';
      }
    }

    return $template;
  }
}

function fungames_get_excluded_categories() {
  $result = 'exclude=';
  $blog = get_cat_ID( get_option('fungames_blog_category') );
  if ( $blog ) {
    $result = 'exclude='.$blog.',';
  }

  $result .= get_option('fungames_exclude_front_cat');

  return $result;
}

/**
 * prints out the page logo
 */
function fungames_logo() {
  $logo = get_option('fungames_custom_logo');

  if ( empty($logo) ) {
    $logo = get_template_directory_uri() . '/images/logo.png';
  }

  ?>
  <img id="logo" itemprop="logo" src="<?php echo $logo; ?>" alt="<?php bloginfo('name');?>" />
  <?php
}

/**
* generates output for the breadcrumb navigation
*/
function fungames_breadcumb() {

  if ( is_home() || is_front_page() ) {
    return;
  }

  if ( function_exists('bcn_display') ) {
    ?>
    <div class="breadcrumb">
      <?php
      esc_html_e("You Are Here: ", "fungames");
       bcn_display();
      ?>
    </div>
    <?php
  }
}

/**
* generates output for the post navigation
*/
function fungames_navigation() {
  ?>
  <div id="navigation">
    <?php
    if(function_exists('wp_pagenavi')) {
      wp_pagenavi();
    } else {
      ?>
      <div class="post-nav clearfix">
        <p id="previous"><?php next_posts_link(esc_html__('Older games &laquo;', 'fungames')) ?></p>
        <p id="next-post"><?php previous_posts_link(esc_html__('&raquo; Newer games', 'fungames')) ?></p>
      </div>
      <?php
    }
    ?>
  </div>
  <?php
}

/**
 * Generate game manage buttons - only for admins
 *
 * @global type $post
 */
function fungames_admin_links() {
  global $post;

  if ( current_user_can('delete_posts') ) {
    // Show edit and delete links
    echo '<div class="clear"></div>';
    echo "<div class='admin_actions'><strong>Admin Actions: </strong><a href='" . wp_nonce_url("/wp-admin/post.php?action=delete&amp;post=".$post->ID, 'delete-post_' . $post->ID) . "'>Delete</a>";
    echo " | ";
    echo "<a href='" . wp_nonce_url("/wp-admin/post.php?post=".$post->ID."&action=edit") . "'>Edit</a></div>";
  }
}

function fungames_get_best_players( $count = 5 ) {
  global $wpdb;

  return $wpdb->get_results("SELECT h.user_id, COUNT(*) as highscores, u.plays as plays
                             FROM ".MYARCADE_HIGHSCORES_TABLE." AS h
                               INNER JOIN ".MYARCADE_USER_TABLE." AS u ON h.user_id=u.user_id
                                 GROUP BY h.user_id
                                 ORDER BY highscores DESC LIMIT ".$count);
}

function fungames_contest_alert() {
  if ( !function_exists('myarcadecontest_get_contest_id_for_this_game') )
    return;

  $contest_id = myarcadecontest_get_contest_id_for_this_game();
  $user_id    = get_current_user_id();

  if (!$contest_id || myarcadecontest_check_user_is_in_contest($contest_id, $user_id) )
    return;

  $permalink_open = '<a href="'.get_permalink($contest_id).'" title="'.get_the_title($contest_id).'">';
  $permalink_close = '</a>';

  ?>
  <div class="info">
    <p>
      <strong><?php esc_html_e('Howdy!', 'fungames'); ?></strong> <?php echo sprintf( esc_html__('There is an active contest available for this game. Click %shere%s to join the contest!', 'fungames'), $permalink_open, $permalink_close); ?>
    </p>
  </div>
  <?php
}

/**
 * Display related or random games
 */
function fungames_related() {
  if ( 'enable' == get_option('fungames_display_related') ) {
    if ( function_exists('related_entries') ) {
      related_entries();
    } else {
      get_template_part('games', 'random');
    }
  }
}

/**
 * Display Google Rich Snippet
 *
 * @global type $post
 */
function fungames_rich_snippet() {
  global $post;

  if ( function_exists('the_ratings') && get_option('fungames_rich_snippet') == 'enable' ) {
    $ratings_user = intval(get_post_meta($post->ID, 'ratings_users', true));
    $rating_average = get_post_meta($post->ID, 'ratings_average', true);
    $ratings_max = intval(get_option('postratings_max'));

    if ( empty($rating_average) ) $rating_average = 0;
    echo "\n";
    ?>
    <!-- Google Rich Snipet -->
    <div itemscope itemtype="https://schema.org/SoftwareApplication">
      <meta itemprop="name" content="<?php the_title_attribute(); ?>" />
      <meta itemprop="image" content="<?php echo myarcade_thumbnail_url(); ?>" />
      <meta itemprop="description" content="<?php echo get_the_excerpt(); ?>" />
      <meta itemprop="softwareApplicationCategory" content="GameApplication" />
      <div itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
        <span itemprop="ratingCount"><?php echo $ratings_user; ?></span> votes, average: <span itemprop="ratingValue"><?php echo $rating_average; ?></span>/<span itemprop="bestRating"><?php echo $ratings_max; ?></span>
      </div>
    </div>
    <?php
    echo "\n";
  }
}

/**
 * Display shareholic icons
 *
 * @version 5.0.0
 * @return  void
 */
function fungames_share() {
  if ( class_exists( 'ShareaholicPublic' ) ) {
    echo '<div class="clear"></div>';
    echo ShareaholicPublic::canvas(NULL, 'share_buttons');
  }
}

/**
 * Filter search query if mobile games option has been enabled
 * and if we are on the search page
 *
 * @version 5.3.0
 * @since   4.3.0
 * @param   object $query
 * @return  void
 */
function fungames_query_filter( $query ) {
  if ( 'enable' == get_option('fungames_mobile') && wp_is_mobile() && !is_admin() && $query->is_main_query() && is_search() ) {
    $query->set('tag', 'mobile');
  }
}
add_action( 'pre_get_posts', 'fungames_query_filter' );


/**
 *
 * Adds JSON LD MarkUp
 *
 */
function fungames_schema() {

  // Skip if we are not on the single page
  if ( ! is_singular('post') ) {
    return;
  }

  if ( function_exists( 'is_game' ) && is_game() ) {
    if ( ! function_exists( 'the_ratings' ) ) {
      // Skip if WP-PostRatings isn't installed
      return;
    }

    $category = get_the_category();
    ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "type": "VideoGame",
      "aggregateRating": {
        "type": "aggregateRating",
        "ratingValue": "<?php echo get_post_meta( get_the_ID(), 'ratings_average', true ); ?>",
        "reviewCount": "<?php echo get_post_meta( get_the_ID(), 'ratings_users', true ); ?>",
        "bestRating": "5",
        "worstRating": "1"
      },
      "applicationCategory": "Game",
      "description": "<?php myarcade_excerpt(490); ?>",
      "genre": "<?php echo $category[0]->cat_name;?>",
      "image": "<?php echo myarcade_thumbnail_url(); ?>",
      "name": "<?php myarcade_title(); ?>",
      "operatingSystem": "Web Browser",
      "url": "<?php the_permalink(); ?>"
    }
    </script>
    <?php
  }
  else {
    // Regular blog post/page
    $logo = get_option('fungames_custom_logo');

    if ( ! $logo ) {
      $logo = get_template_directory_uri() . '/images/logo.png';
    }
    ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Article",
      "headline": "<?php the_title(); ?>",
      "author": {
          "@type": "Person",
          "name": "<?php the_author(); ?>"
      },
      "datePublished": "<?php the_date('Y-n-j'); ?>",
      "dateModified": "<?php the_modified_date('Y-n-j'); ?>",
      "url": "<?php the_permalink(); ?>",
      "image": "<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>",
      "description": "<?php the_excerpt(); ?>",

      "publisher": {
          "@type": "Organization",
          "name": "<?php bloginfo( 'name' ); ?>",
          "logo":" <?php echo $logo; ?>"
      },

      "mainEntityOfPage":{
        "@type":"WebPage",
        "url": "<?php the_permalink(); ?>"
      }
    }
    </script>
    <?php
  }
}
