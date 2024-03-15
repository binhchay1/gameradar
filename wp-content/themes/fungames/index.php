<?php get_header(); ?>

<h2 style="font-size: 20px; font-weight: bold; color: #fff">Top categories</h2>
<?php
$topCategory = get_option('top_category_homepage');
if ($topCategory) {
  $arrTopCategory = json_decode(json_encode(json_decode($topCategory)), true); ?>

  <div class="top-cate-homepage">

    <div class="list-top-cate">
      <?php foreach ($arrTopCategory as $category => $status) {
        if ($status == 'true') { ?>
          <a href="<?php echo get_category_link(get_cat_ID($category)) ?>">
            <div class="in-list-top-cate"><?php echo ucfirst(str_replace('-', ' ', $category)) ?></div>
          </a>
      <?php }
      } ?>
    </div>
  </div>
<?php }
?>

<?php $args = array(
  'posts_per_page'   => -1,
  'post_type'        => 'post',
  'meta_key'         => 'top_game_homepage',
  'meta_value'       => '1'
);

$query = new WP_Query($args);

?>
<div class="top-cate-homepage">
  <h2 style="font-size: 20px; font-weight: bold; color: #fff">Top games</h2>
  <div class="list-top-cate">
    <?php foreach ($query->posts as $post) {
      $img_url = get_post_meta($post->ID, 'mabp_thumbnail_url');
    ?>

      <article class="games" style="margin-left: 30px">
        <a href="<?php echo get_permalink($post->ID) ?>" data-hasqtip="27" oldtitle="<?php echo $post->post_title ?>" title="">
          <div class="thumb">
            <div class="play">
              <span class="icon icon-link"></span>
            </div>

            <img src="<?php echo $img_url[0] ?>" width="100" height="100" alt="<?php echo $post->post_title ?>">
          </div>
        </a>
      </article>
    <?php } ?>
  </div>
</div>

<div id="content" class="content<?php echo get_option('fungames_sidebar_position'); ?>">

  <?php
  // Include Slider if activated
  get_template_part('bxslider');

  // Include Contest Promo Box
  get_template_part('contest', 'promo');

  // Include Sortable Game Box
  get_template_part('games', 'sortable');

  // Include Hall Of Fame
  get_template_part('hall-of-fame');

  // Get index banner
  $catcount = 0;
  $banner = stripslashes(get_option('fungames_adindex'));

  // Generate Game Boxes

  // Get box count. If emty then set to 6 (default)
  $fungames_box_count = get_option('fungames_box_count');
  if (empty($fungames_box_count)) $fungames_box_count = 6;

  // Calculate the height of a game box
  $height = fungames_calculate_height($fungames_box_count);

  // Get the post order
  if (get_option('fungames_order_games') == 'Random') {
    $order = '&orderby=rand';
  } else {
    $order = '';
  }

  // Get categories, hide empty, exclude defined
  $exclude = fungames_get_excluded_categories();
  $categories = get_categories($exclude);

  $mobile_tag = ('enable' == get_option('fungames_mobile') && wp_is_mobile()) ? '&tag=mobile' : '';

  // Generate the query string.
  $get_post_query = 'numberposts=' . intval($fungames_box_count) . $order . $mobile_tag . '&category=';

  // ***************************************************************************
  // Generate Game Boxes... Loop through all categories
  // ***************************************************************************
  foreach ($categories as $category) {
    // Get the current category ID
    $cat_id = $category->cat_ID;

    // Get games from this category
    $games = get_posts($get_post_query . $cat_id);

    // Check if we have some games in this category
    if ($games) {
      // There are some games.. Create the game box
      get_template_part('index', get_option('fungames_box_design'));
    }
  } // END category loop

  // Do some actions after game category boxes
  do_action('fungames_after_boxes');

  // show game list if plugin exists
  if (function_exists('get_game_list')) {
    get_game_list();
  }

  // Display front page text if exists

  if (get_option("fungames_frontpage_text_status") == "enable") {
    $frontpage_text = stripslashes(get_option('fungames_frontpage_text'));
  ?>
    <div class="clear"></div>
    <div class="contentbox customtext">
      <?php echo $frontpage_text; ?>
    </div>
  <?php
  }

  // Do some actions before the content wrap ends
  do_action('fungames_before_content_end');
  ?>

</div><?php // end content 
      ?>

<?php get_sidebar(); ?>

<?php $description_homepage = get_option('description_homepage') ?>
<div class="description_homepage">
  <?php echo html_entity_decode($description_homepage) ?>
</div>
<?php get_footer(); ?>