<?php if ( get_option('fungames_show_loginform') == 'enable' ) : ?>
  <div id="loginbox">
    <?php
    $current_user = wp_get_current_user();
    if ( !$current_user->ID ) :
      // User is not logged in ...
      ?>
      <div class="reginfo">
        <?php esc_html_e('Register, upload AVATAR, save SCORES, meet FRIENDS!', 'fungames'); ?>
      </div>
      <form name="loginform" id="loginform" action="<?php echo wp_login_url(); ?>" method="post">
        <label>
          <?php esc_html_e('Username', 'fungames') ?>:
          <input type="text" name="log" id="log" value="" size="10" tabindex="7" />
        </label>
        <label>
          <?php esc_html_e('Password', 'fungames') ?>:
          <input type="password" name="pwd" id="pwd" value="" size="10" tabindex="8" />
        </label>
        <?php do_action( 'login_form' ); ?>
        <label>
          <input type="checkbox" name="rememberme" value="forever" tabindex="9" /> <?php esc_html_e("Remember me", 'fungames'); ?>
        </label>
        <input type="submit" name="submit" value="Login" tabindex="10" class="button" />
        <?php wp_register('', ''); ?>
        <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI'];?>"/>
      </form>
    <?php else : ?>
      <?php // User is logged in ... ?>
      <?php // check for buddypress ?>
      <div class="reginfo">
        <?php esc_html_e('Welcome back,', 'fungames'); ?> <?php echo $current_user->display_name; ?>!
      </div>
      <div class="reguser_details">
        <?php if ( defined('BP_VERSION') ) : ?>
          <?php
          global $bp;

          if( bp_is_active('activity') ) :
            ?>
            <a href="<?php echo $bp->loggedin_user->domain . BP_ACTIVITY_SLUG . '/'; ?>"><?php esc_html_e('Activity', 'fungames'); ?></a>
            &nbsp;|&nbsp;
          <?php endif; ?>

          <a href="<?php echo site_url( bp_get_members_root_slug() ); ?>"><?php esc_html_e('Members', 'fungames'); ?></a>
          &nbsp;|&nbsp;

          <a href="<?php echo $bp->loggedin_user->domain ?>"><?php esc_html_e('Profile', 'fungames'); ?></a>
          <?php if ( isset( $bp->loaded_components['scores'] ) ) : ?>
            &nbsp;|&nbsp;
            <a href="<?php echo $bp->loggedin_user->domain . 'scores'; ?>"><?php esc_html_e('My Scores', 'fungames'); ?></a>
          <?php endif; ?>

        <?php endif; ?>

        &nbsp;|&nbsp;

        <?php
        if ( ! is_user_logged_in() )
          $link = '<a href="' . wp_login_url() .'">' . esc_html__('Log in', 'fungames') . '</a>';
        else
          $link = "<a href='" . wp_logout_url( home_url() )  . "'>".esc_html__('Log out', 'fungames')."</a>";

        echo apply_filters('loginout', $link);
        ?>
      </div>
    <?php endif; ?>
  </div>

  <div class="clear"></div>
<?php endif; ?>