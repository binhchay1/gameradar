<?php get_header(); ?>

<div id="content" class="content<?php echo get_option('fungames_sidebar_position'); ?>">

  <?php
  // Do some action before the page output
  do_action('fungames_before_page');


  if (have_posts()) :
    while (have_posts()) : the_post();
      ?>
      <div <?php post_class( 'singlepage' ); ?> id="post-<?php the_ID(); ?>">
        <div class="title">
          <h2>
            <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php esc_attr_e('Permanent Link to', 'fungames'); ?> <?php the_title_attribute(); ?>">
              <?php the_title(); ?>
            </a>
          </h2>
        </div>

        <div class="cover">
          <div class="entry">
            <?php the_content(esc_html__('Read more..', 'fungames')); ?>
            <?php wp_link_pages(); ?>
            <div class="clear"></div>
          </div>
        </div>
      </div>
      <?php
    endwhile;

    // Do some action after the page output
    do_action('fungames_after_page');

  else :
    ?>
    <h2 class="pagetitle">
      <?php esc_html_e("Sorry, Can't find that Game. But maybe you like one of these games:", "fungames"); ?>
    </h2>

    <?php
    // Get some random games
    get_template_part('games', 'random');

  endif;

  // Do some actions before the content wrap ends
  do_action('fungames_before_content_end');
  ?>

</div> <?php // end content ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>