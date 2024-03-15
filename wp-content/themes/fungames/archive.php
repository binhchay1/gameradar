<?php get_header(); ?>
<?php
// Query mobile games
if ('enable' == get_option('fungames_mobile') && wp_is_mobile()) {
  global $query_string;

  if (is_tag()) {
    $tag_query = '+mobile';
  } else {
    $tag_query = '&tag=mobile';
  }

  query_posts($query_string . $tag_query);
}
?>
<div id="content" class="content<?php echo get_option('fungames_sidebar_position'); ?>">
  <?php
  // Do some actions just before game category boxes
  do_action('fungames_before_archive');
  ?>
  <div style="display: flex; flex-direction: column; margin-left: 10px">
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

    <?php
    $category = get_category(get_query_var('cat'));
    $queryGet = "SELECT * FROM " . $wpdb->prefix . 'top_game_category WHERE category_id = "' . $category->term_id . '"';
    $resultTopGame = $wpdb->get_results($queryGet);

    if (empty($resultTopGame)) {
      $listTopGame = array();
    } else {
      if (empty($resultTopGame[0]->game)) {
        $listTopGame = array();
      } else {
        $listTopGame = explode(',', $resultTopGame[0]->game);
      }
    }

    ?>
    <div class="top-cate-homepage">
      <h2 style="font-size: 20px; font-weight: bold; color: #fff">Top games</h2>
      <div class="list-top-cate">
        <?php foreach ($listTopGame as $post_id) {
          $img_url = get_post_meta($post_id, 'mabp_thumbnail_url');
        ?>

          <article class="games" style="margin-left: 30px">
            <a href="<?php echo get_permalink($post_id) ?>" data-hasqtip="27" oldtitle="<?php echo get_the_title($post_id) ?>" title="">
              <div class="thumb">
                <div class="play">
                  <span class="icon icon-link"></span>
                </div>

                <img src="<?php echo $img_url[0] ?>" width="100" height="100" alt="<?php echo get_the_title($post_id) ?>">
              </div>
            </a>
          </article>
        <?php } ?>
      </div>
    </div>
  </div>



  <div class="archive_view">
    <?php
    // Generate Archive title
    if (have_posts()) : ?>
      <?php
      if (is_category() && 'enable' == get_option('fungames_show_catdesc')) :
        $category_description = category_description();

        if ($category_description) : ?>
          <div class="contentbox customtext">
            <?php echo $category_description; ?>
          </div>
        <?php
        endif;
      elseif (is_tag() && 'enable' == get_option('fungames_show_tagdesc')) :
        $tag_description = tag_description();
        if ($tag_description) : ?>
          <div class="contentbox customtext">
            <?php echo $tag_description; ?>
          </div>
        <?php
        endif;
      endif;

      while (have_posts()) : the_post();
        ?>
        <div class="cat_view">
          <div class="gametitle">
            <h2>
              <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php myarcade_title(40); ?></a>
            </h2>
          </div>

          <div class="cover">
            <div class="entry">
              <div class="img-pst">
                <?php if (function_exists('is_leaderboard_game') && is_leaderboard_game()) : ?>
                  <div class="score_ribbon"></div>
                <?php endif; ?>
                <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(esc_attr__('Permanent Link to %s', 'fungames'), the_title_attribute('echo=0')) ?>">
                  <?php
                  myarcade_thumbnail(array(
                    'width' => 80,
                    'height' => 80,
                    'class' => 'alignleft',
                    'lazy_load' => (get_option('fungames_lazy_load') == 'enable') ? true : false
                  ));
                  ?>
                </a>
              </div>
              <?php myarcade_excerpt(180); ?>
              <div class="clear"></div>
            </div>
          </div>

          <?php if (function_exists('the_ratings')) : ?>
            <div align="left">
              <?php the_ratings(); ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endwhile; ?>

      <div class="clear"></div>

      <?php fungames_navigation(); ?>

      <?php $category = get_category(get_query_var('cat'));
      $cat_id = $category->cat_ID;
      $result = $wpdb->get_results("SELECT * FROM wp_category_custom WHERE category_id = $cat_id");
      if (!empty($result)) {
      ?>
      <?php } ?>
      <div id="content-footer" class="rounded bg-white">
        <?php echo html_entity_decode($result[0]->content) ?>
      </div>
    <?php else : ?>
      <h1 class="title"><?php esc_html_e("Not Found", "fungames"); ?></h1>
      <p><?php esc_html_e("Sorry, but you are looking for something that isn't here.", "fungames"); ?></p>
    <?php endif; ?>
  </div> <?php // end archive 
          ?>

  <?php
  // Do some actions before the content wrap ends
  do_action('fungames_after_archive');
  ?>

</div> <?php // end content 
        ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>