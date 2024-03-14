<?php
/**
 * FunGames action functions
 *
 * @author Daniel Bakovic
 * @uri https://myarcadeplugin.com
 *
 * @package WordPress
 * @subpackage FunGames
 */

function fungames_init_actions() {
  /** Add WPSeoContentManager Compatibility **/
  if ( function_exists('get_WPSEOContent') ) {
    add_action('fungames_after_404_content', 'get_WPSEOContent');
    add_action('fungames_after_archive_content', 'get_WPSEOContent');
    add_action('fungames_after_index_content', 'get_WPSEOContent');
  }

  // Only if contest is activated
  if ( function_exists('myarcadecontest_init') ) {
    add_action('fungames_before_game', 'fungames_contest_alert');
  }
}
add_action('init', 'fungames_init_actions');

function fungames_action_after_404_content() {
  do_action('fungames_after_404_content');
}

function fungames_action_after_archive_content() {
  do_action('fungames_after_archive_content');
}

function fungames_action_after_index_content() {
  do_action('fungames_after_index_content');
}
?>