<?php get_header(); ?>

<div id="content_game">
  <?php
  if ( have_posts() ) {
    while (have_posts()) {
      the_post();

      // Display game and content
      get_template_part('games', 'play');
      get_template_part('single', 'content');
    } // end while
  }
  else {
    // Nothing to display
    ?>
    <h2 class="title"><?php esc_html_e("Not Found", "fungames"); ?></h2>
    <p><?php esc_html_e("I'm Sorry, you are looking for something that is not here. Try a different search.", "fungames"); ?></p>
    <?php
  }

  // Do some actions before the content wrap ends
  do_action('fungames_before_content_end');
  ?>
</div><?php // end content_game ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>