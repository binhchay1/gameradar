<?php get_header(); ?>

<div id="content" class="content<?php echo get_option('fungames_sidebar_position'); ?>">
  <div class="cover">
    <div class="entry">
      <?php
      if ( function_exists( 'woocommerce_breadcrumb' ) ) {
        woocommerce_breadcrumb();
      }

      if ( function_exists( 'woocommerce_content' ) ) {
        woocommerce_content();
      }
      ?>

      <div class="clear"></div>
    </div>
  </div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>