<?php
/**
 * FunGames Setup
 *
 * @package WordPress
 * @subpackage FunGames
 */


if ( is_admin() ) {
  // Include TGM Plugin Activation
  get_template_part( 'inc/admin/plugins' );
}

/**
 * Enables MCE Editor For bbPress
 *
 * @version 5.6.0
 * @since   5.6.0
 * @param   array $args
 * @return  array
 */
function fungames_bbp_enable_visual_editor( $args = array() ) {
  $args['tinymce'] = true;
  $args['quicktags'] = false;
  return $args;
}
add_filter( 'bbp_after_get_the_content_parse_args', 'fungames_bbp_enable_visual_editor' );