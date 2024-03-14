<?php
// Functions that are needed for the buddypress integration

// Do it only if buddypress is installed
if ( defined('BP_VERSION') ) :

/**
 *
 * Removes WordPress SEO Title Filter On BuddyPress Pages
 *
 * @version 5.6.0
 * @since   5.6.0
 * @return  void
 */
function fungames_remove_bp_wpseo_title() {
  // WP SEO Plugin needs to be active
  if (  class_exists( 'WPSEO_Frontend' ) && function_exists( 'bp_is_blog_page' ) && ! bp_is_blog_page() ) {
    $fungames_front_end = WPSEO_Frontend::get_instance();
    remove_filter( 'pre_get_document_title', array( $fungames_front_end, 'title' ), 15 );
  }
}
add_action( 'init', 'fungames_remove_bp_wpseo_title' );

endif;