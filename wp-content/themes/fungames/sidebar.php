<div id="sidebar<?php echo get_option('fungames_sidebar_position'); ?>" itemscope="itemscope" itemtype="https://schema.org/WPSideBar">
  <div class="sidebar">

    <?php
    // Do some actions before the widget area
    do_action('fungames_before_sidebar_widgets');
    ?>

    <?php
    // Reset WordPress query vars
    wp_reset_query();

    if (is_single()) {
      if (is_active_sidebar('single-sidebar')) {
      dynamic_sidebar('single-sidebar');
      } else {
        ?>
        <div class="box sidebar">
          <div class="warning">
            <?php esc_html_e('This is your Game Sidebar and no widgets have been placed here, yet!', 'fungames'); ?>
            <p>Click <a href="<?php echo home_url() ?>/wp-admin/widgets.php" title="">here</a> to setup this this sidebar!</p>
          </div>
        </div>
        <?php
      }
    }
    elseif (is_page()) {
      if (is_active_sidebar('page-sidebar')) {
      dynamic_sidebar('page-sidebar');
      } else {
        ?>
        <div class="box sidebar">
          <div class="warning">
            <?php esc_html_e('This is your Page Sidebar and no widgets have been placed here, yet!', 'fungames'); ?>
            <p>Click <a href="<?php echo home_url() ?>/wp-admin/widgets.php" title="">here</a> to setup this this sidebar!</p>
          </div>
        </div>
        <?php
      }
    }
    elseif (is_category()) {
      if (is_active_sidebar('category-sidebar')) {
      dynamic_sidebar('category-sidebar');
      } else {
        ?>
        <div class="box sidebar">
          <div class="warning">
            <?php esc_html_e('This is your Category Sidebar and no widgets have been placed here, yet!', 'fungames'); ?>
            <p>Click <a href="<?php echo home_url() ?>/wp-admin/widgets.php" title="">here</a> to setup this this sidebar!</p>
          </div>
        </div>
        <?php
      }
    }
    else {
      // Home Sidebar
      if (is_active_sidebar('home-sidebar')) {
      dynamic_sidebar('home-sidebar');
      } else {
        ?>
        <div class="box sidebar">
          <div class="warning">
            <?php esc_html_e('This is your Home Sidebar and no widgets have been placed here, yet!', 'fungames'); ?>
            <p>Click <a href="<?php echo home_url() ?>/wp-admin/widgets.php" title="">here</a> to setup this this sidebar!</p>
          </div>
        </div>
        <?php
      }
    }
    ?>

    <?php
    // Do some actions after the widget area
    do_action('fungames_after_sidebar_widgets');
    ?>

  </div><?php // end class sidebar ?>
</div> <?php // end rightcol ?>
<div class="clear"></div>