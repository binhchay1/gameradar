<?php
/**
 * FunGames back compat functionality
 *
 * Prevent FunGames from running on WordPress versions prior to 4.1,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 4.1.
 *
 * @package WordPress
 * @subpackage FunGames
 */

/**
 * Prevent switching to FunGames on old versions of WordPress.
 * Switches to the default theme.
 */
function fungames_switch_theme() {
  switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
  unset( $_GET['activated'] );
  add_action( 'admin_notices', 'fungmes_upgrade_notice' );
}
add_action( 'after_switch_theme', 'fungames_switch_theme' );


function fungames_get_error_message() {
  return sprintf( esc_html__( 'FunGames requires at least WordPress version 4.1. You are running version %s. Please upgrade and try again.', 'fungames' ), $GLOBALS['wp_version'] );
}

/**
 * Add message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * FunGames on WordPress versions prior to 4.1.
 */
function fungmes_upgrade_notice() {
  printf( '<div class="error"><p>%s</p></div>', fungames_get_error_message() );
}

/**
 * Prevent the Customizer from being loaded on WordPress versions prior to 4.1.
 */
function fungames_customize() {
  wp_die( fungames_get_error_message(), '', array( 'back_link' => true) );
}
add_action( 'load-customize.php', 'fungames_customize' );

/**
 * Prevent the Theme Preview from being loaded on WordPress versions prior to 4.1.
 */
function fungames_preview() {
  if ( isset( $_GET['preview'] ) ) {
    wp_die( fungames_get_error_message() );
  }
}
add_action( 'template_redirect', 'fungames_preview' );