<?php
// Include Widgets
$fungames_widgets = array(
  /* Sidebar Widgets */
  'widget_most_played',
  'widget_most_rated',
  'widget_user_panel',
  'widget_youtube_video',
  'widget_ads125',
  'widget_recent_games',
  'widget_random_games',
  'widget_advertisement',
);

foreach( $fungames_widgets as $widget ) {
  locate_template ('inc/widgets/' . $widget . '.php', true, true);
}
  // Register's All Widgets
  if ( !function_exists('myabp_register_widgets') ) {
    function myabp_register_widgets() {
      register_widget('WP_Widget_MABP_Youtube_Video');
      register_widget('WP_Widget_MABP_User_Login');
      register_widget('WP_Widget_MABP_Recent_Games');
      register_widget('WP_Widget_MABP_Random_Games');
      register_widget('WP_Widget_MABP_Most_Rated');
      register_widget('WP_Widget_MABP_Most_Played');
      register_widget('WP_Widget_MABP_Banner_125');
      register_widget('WP_Widget_MABP_Advertisement');
    }
  }

  add_action('widgets_init', 'myabp_register_widgets');

/**
 * Creates sidebars
 *
 * @version 5.6.0
 * @since   1.0.0
 * @access  public
 * @return  void
 */
function fungames_widgets_init() {
  register_sidebar(
    array('name'          =>'Home Sidebar',
          'id'            =>'home-sidebar',
          'description'   => 'This is the sidebar that gets shown on the home page.',
          'before_widget' => '<div class="widget %2$s">',
          'after_widget'  => '</div>',
          'before_title'  => '<h2>',
          'after_title'   => '</h2>',
    )
  );

  register_sidebar(
    array('name'          =>'Single Sidebar',
          'id'            =>'single-sidebar',
          'description'   => 'This is your sidebar that gets shown on the game or blog pages.',
          'before_widget' => '<div class="widget %2$s">',
          'after_widget'  => '</div>',
          'before_title'  => '<h2>',
          'after_title'   => '</h2>',
    )
  );

  register_sidebar(
    array('name'          =>'Page Sidebar',
          'id'            =>'page-sidebar',
          'description'   => 'This is your sidebar that gets shown on most of your pages.',
          'before_widget' => '<div class="widget %2$s">',
          'after_widget'  => '</div>',
          'before_title'  => '<h2>',
          'after_title'   => '</h2>',
    )
  );

  register_sidebar(
    array('name'          =>'Category Sidebar',
          'id'            =>'category-sidebar',
          'description'   => 'This is your sidebar that gets shown on the category pages.',
          'before_widget' => '<div class="widget %2$s">',
          'after_widget'  => '</div>',
          'before_title'  => '<h2>',
          'after_title'   => '</h2>',
    )
  );

  register_sidebar( array(
    'name'        => esc_html__( 'Horizontal Footer Widgets', 'fungames' ),
    'id'          => 'horizontal-footer-widgets',
    'description' => esc_html__( 'Widgets displayed in full width and cetered in footer', 'fungames' ),
    'before_widget' => '<div class="horizontal-footer-widget %2$s">',
    'after_widget'  => '</div>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ));

  // Area 1, located in the footer. Empty by default.
  register_sidebar( array(
    'name' => esc_html__( 'First Footer Widget Area', 'fungames' ),
    'id' => 'first-footer-widget-area',
    'description' => esc_html__( 'The first footer widget area', 'fungames' ),
    'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ) );

  // Area 2, located in the footer. Empty by default.
  register_sidebar( array(
    'name' => esc_html__( 'Second Footer Widget Area', 'fungames' ),
    'id' => 'second-footer-widget-area',
    'description' => esc_html__( 'The second footer widget area', 'fungames' ),
    'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ) );

  // Area 3, located in the footer. Empty by default.
  register_sidebar( array(
    'name' => esc_html__( 'Third Footer Widget Area', 'fungames' ),
    'id' => 'third-footer-widget-area',
    'description' => esc_html__( 'The third footer widget area', 'fungames' ),
    'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ) );

  // Area 4, located in the footer. Empty by default.
  register_sidebar( array(
    'name' => esc_html__( 'Fourth Footer Widget Area', 'fungames' ),
    'id' => 'fourth-footer-widget-area',
    'description' => esc_html__( 'The fourth footer widget area', 'fungames' ),
    'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ) );
}