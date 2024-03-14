<?php get_header(); ?>

<div id="content" class="content<?php echo get_option('fungames_sidebar_position'); ?>">

  <h2 class="pagetitle">
    <?php esc_html_e("404 - Sorry, Can't find that Game. But maybe you like one of these games:", "fungames"); ?>
  </h2>

  <?php
  // Show some random games
  get_template_part('games', 'random');

  // Do some actions after random games
  do_action('fungames_after_404_content');
  ?>

</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>