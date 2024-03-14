<?php

/*
 * Shows the User Login panel. When the user is logged in serveral user links
 * are shown.
 *
 */

if ( !class_exists('WP_Widget_MABP_User_Login') ) {
  class WP_Widget_MABP_User_Login extends WP_Widget {

    // Constructor
    function __construct() {
      $widget_ops   = array('description' => 'Shows the user login and the user panel.');
      parent::__construct('MABP_User_Login', 'MyArcade User Login Panel', $widget_ops);
      add_action('wp_print_scripts', array($this, 'js_load_scripts'));
    }

	function js_load_scripts () {
    if ( defined('WDFB_PLUGIN_URL')) {
		if (!is_admin()) wp_enqueue_script('wdfb_connect_widget', WDFB_PLUGIN_URL . '/js/wdfb_connect_widget.js');
		if (!is_admin()) wp_enqueue_script('wdfb_facebook_login', WDFB_PLUGIN_URL . '/js/wdfb_facebook_login.js');
    }
	}

    // Display Widget
    function widget($args, $instance) {

      $current_user = wp_get_current_user();

      extract($args);

      $title = apply_filters('widget_title', esc_attr($instance['title']));

      echo $before_widget.$before_title.$title.$after_title;

      // <-- START --> HERE COMES THE OUPUT
      if ( $current_user->ID ) {
        // user is logged in
        ?>
        <div class="gravatar">
          <?php echo get_avatar( $current_user->user_email, $size = '85'); ?>
        </div>
        <div class="userinfo">
          <div calss="welcome"><?php esc_html_e('Hello', 'fungames'); ?>, <strong><?php echo $current_user->display_name; ?></strong>! [<a href="<?php echo wp_logout_url( home_url() ); ?>"><?php esc_html_e('Logout', 'fungames'); ?></a>]</div>
        </div>
        <div class="clear"></div>
          <ul>
            <?php if ( defined('BP_VERSION') ) : ?>
            <?php global $bp; ?>
            <?php if( bp_is_active('activity') ) : ?>
            <li><a href="<?php echo $bp->loggedin_user->domain . BP_ACTIVITY_SLUG . '/'; ?>"><?php esc_html_e('Activity', 'fungames'); ?></a></li>
            <?php endif; ?>
            <li><a href="<?php echo site_url( bp_get_members_root_slug() ); ?>"><?php esc_html_e('Members', 'fungames'); ?></a></li>
            <?php if( bp_is_active('groups') ) : ?>
            <li><a href="<?php echo $bp->loggedin_user->domain . BP_GROUPS_SLUG . '/'; ?>"><?php esc_html_e('Groups', 'fungames'); ?></a></li>
            <?php endif; ?>
            <?php if ( bp_is_active( 'friends' ) ) : ?>
            <li><a href="<?php echo $bp->loggedin_user->domain . BP_FRIENDS_SLUG . '/'; ?>"><?php esc_html_e('Friends', 'fungames'); ?></a></li>
            <?php endif; ?>
            <li><a href="<?php echo $bp->loggedin_user->domain ?>"><?php esc_html_e('Profile', 'fungames'); ?></a></li>
            <?php if ( isset($bp->myscore) ) : ?>
            <li><a href="<?php echo $bp->loggedin_user->domain . $bp->myscore->slug . '/'; ?>"><?php esc_html_e('My Scores', 'fungames'); ?></a></li>
            <?php endif; ?>
            <?php else: ?>
            <li><a href="<?php echo home_url(); ?>/wp-admin/index.php"><?php esc_html_e('Go to Dashboard', 'fungames'); ?></a></li>
            <li><a href="<?php echo home_url(); ?>/wp-admin/profile.php"><?php esc_html_e('Edit My Profile', 'fungames'); ?></a></li>
            <?php endif; ?>
          </ul>
          <?php if(function_exists('wpfp_list_favorite_posts')) { ?>
            <p style="text-align:center"><?php esc_html_e('Your Favorite Games', 'fungames'); ?></p>
            <?php wpfp_list_favorite_posts() ?>
				<?php } ?>
        <?php
      } else {
        // user isn't logged in
        ?>
        <h3 style="text-align:center;"><?php esc_html_e("Members Login", 'fungames' ) ?></h3><br />
        <fieldset id="loginBox">
          <form action="<?php echo wp_login_url() ?>" method="post">
          <label><input type="text" name="log" id="log" value="<?php esc_attr_e("username", 'fungames') ?>" /></label>
          <label><input type="password" name="pwd" id="pwd" value="<?php esc_attr_e("password", 'fungames') ?>" /></label>
          <?php do_action( 'login_form' ); ?>
          <input type="hidden" name="redirect_to" value="<?php echo home_url(); ?>"/>
          <input type="submit" name="Login" value="<?php esc_attr_e('Login', 'fungames'); ?>" class="logininp" />
          </form>
          <div class="clr"></div>
          <div class="register_recover">
             <?php wp_register('', ''); ?> | <a href="<?php echo wp_lostpassword_url(); ?>"><?php esc_html_e('Lost password?', 'fungames'); ?></a>
          </div>
        </fieldset>
        <?php
      }
      // <-- END --> HERE COMES THE OUPUT

      echo $after_widget;
    }

    // Update Widget
    function update($new_instance, $old_instance) {

      $instance = $old_instance;
      $instance['title'] = strip_tags($new_instance['title']);

      return $instance;
    }

    // Display Widget Control Form
    function form($instance) {
      global $wpdb;

      $instance = wp_parse_args((array) $instance, array('title' => esc_html__('User Panel', 'fungames') ) );

      $title = esc_attr($instance['title']);

      ?>

      <p>
        <label for="<?php echo $this->get_field_id('title'); ?>">
          Title
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </label>
      </p>
      <?php
    }
  }
}
?>