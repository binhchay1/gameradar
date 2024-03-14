<?php

/*
 * Shows thumbnails of the most played games
 *
 * Required: WP-PostViews Plugin
 */

if ( !class_exists('WP_Widget_MABP_Most_Played') ) {
  class WP_Widget_MABP_Most_Played extends WP_Widget {

    // Constructor
    function __construct() {
      $widget_ops   = array('description' => 'Shows images of the most played games. WP-PostViews Plugin required!');
      parent::__construct('MABP_Most_Played', 'MyArcade Most Played Games', $widget_ops);
    }

    // Display Widget
    function widget($args, $instance) {
      extract($args);

      $title = apply_filters('widget_title', esc_attr($instance['title']));
      $limit = intval($instance['limit']);

      global $post, $wpdb;

      echo $before_widget;

      if($title) {
        echo $before_title . $title . $after_title;
      }

      // <-- START --> HERE COMES THE OUTPUT
      $blogcat = get_cat_ID( get_option('fungames_blog_category'));
      if ( !empty($blogcat) ) $exclude = '&cat=-'.$blogcat; else $exclude = '';
      $mobile_tag = ( 'enable' == get_option('fungames_mobile') && wp_is_mobile() ) ? '&tag=mobile' : '';

      if(function_exists('the_views')) {
        $games = new WP_Query("showposts=".$limit."&v_sortby=views&v_orderby=desc".$exclude.$mobile_tag);
      } else {
        $games = new WP_Query("showposts=".$limit."&orderby=rand".$exclude.$mobile_tag);
      }

      echo "<ul>";

      if ( !empty($games) ) {
        while( $games->have_posts() ) : $games->the_post();
          ?>
          <li>
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
              <?php
              myarcade_thumbnail ( array(
                'width' => 85,
                'height' => 85,
                'class' => 'widgetimage',
                'lazy_load' => ( get_option('fungames_lazy_load') == 'enable' ) ? true : false
              ));
              ?>
            </a>
          </li>
          <?php
        endwhile;
      }

      // <-- END --> HERE COMES THE OUTPUT
      echo "</ul>";

      echo $after_widget;
    }

    // Update Widget
    function update($new_instance, $old_instance) {

      $instance = $old_instance;
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['limit'] = intval($new_instance['limit']);

      return $instance;
    }

    // Display Widget Control Form
    function form($instance) {
      global $wpdb;

      $instance = wp_parse_args((array) $instance, array('title' => 'Most Played Games', 'limit' => 12));

      $title = esc_attr($instance['title']);
      $limit = intval($instance['limit']);

      ?>

      <p>
        <label for="<?php echo $this->get_field_id('title'); ?>">
          Title
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </label>
      </p>

      <p>
        <label for="<?php echo $this->get_field_id('limit'); ?>">
          Limit
          <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo $limit; ?>" />
        </label>
      </p>

      <?php
    }
  }
}
?>