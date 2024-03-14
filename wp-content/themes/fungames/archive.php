<?php get_header(); ?>
<?php
// Query mobile games
if ( 'enable' == get_option('fungames_mobile') && wp_is_mobile() ) {
  global $query_string;

  if ( is_tag() ) {
    $tag_query = '+mobile';
  }
  else {
    $tag_query = '&tag=mobile';
  }

  query_posts( $query_string . $tag_query );
}
?>
<div id="content" class="content<?php echo get_option('fungames_sidebar_position'); ?>">
  <?php
  // Do some actions just before game category boxes
  do_action('fungames_before_archive');
  ?>

  <div class="archive_view">
    <?php
    // Generate Archive title
    if ( have_posts() ) : ?>
      <h1>
        <?php the_archive_title(); ?>
      </h1>
      <?php
      if ( is_category() && 'enable' == get_option('fungames_show_catdesc') ) :
        $category_description = category_description();

        if ( $category_description ) : ?>
          <div class="contentbox customtext">
            <?php echo $category_description; ?>
          </div>
          <?php
        endif;
      elseif ( is_tag() && 'enable' == get_option('fungames_show_tagdesc') ) :
        $tag_description = tag_description();
        if ( $tag_description ) : ?>
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
                <?php if ( function_exists('is_leaderboard_game') && is_leaderboard_game() ) : ?>
                  <div class="score_ribbon"></div>
                <?php endif; ?>
                <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(esc_attr__('Permanent Link to %s', 'fungames'), the_title_attribute('echo=0')) ?>">
                    <?php
                    myarcade_thumbnail( array(
                      'width' => 80,
                      'height' => 80,
                      'class' => 'alignleft',
                      'lazy_load' => ( get_option('fungames_lazy_load') == 'enable' ) ? true : false
                    ));
                    ?>
                </a>
              </div>
              <?php myarcade_excerpt(180); ?>
              <div class="clear"></div>
            </div>
          </div>

          <?php if(function_exists('the_ratings')) : ?>
            <div align="left">
              <?php the_ratings(); ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endwhile; ?>

      <div class="clear"></div>

      <?php fungames_navigation(); ?>

    <?php else: ?>
      <h1 class="title"><?php esc_html_e("Not Found", "fungames"); ?></h1>
      <p><?php esc_html_e("Sorry, but you are looking for something that isn't here.", "fungames"); ?></p>
    <?php endif; ?>
  </div> <?php // end archive ?>

  <?php
  // Do some actions before the content wrap ends
  do_action('fungames_after_archive');
  ?>

</div> <?php // end content ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>