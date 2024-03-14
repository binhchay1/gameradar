<?php if ( get_option('fungames_games_box') == 'enable' ) : ?>

  <?php do_action('fungames_before_sortable_game_box'); ?>

  <div id="sortable-game-box" class="fullcontent">
    <h2>
      <?php echo get_option("fungames_sortable_game_box_title"); ?>
    </h2>

    <div id="sortable-game-box-inner">
      <div id="sortable-game-box-inner-content">

        <div id="sortable-game-box-loader">
          <div id="sortable-game-box-loader-content">
              <img src="<?php echo get_template_directory_uri(); ?>/images/loading.gif" alt="loading.." width="220" height="19" /><br />
              <?php esc_html_e('Loading...', 'fungames'); ?>
          </div>
        </div>

        <div id="sortable-game-box-list">
          <?php
          global $query_string;

          $blog = get_cat_ID( get_option('fungames_blog_category') );
          if ( $blog ) {
            $exclude_blog = '&cat=-'.$blog;
          }
          else {
            $exclude_blog = '';
          }

          $gamelimit = get_option('fungames_games_box_count');
          $mobile_tag = ( 'enable' == get_option('fungames_mobile') && wp_is_mobile() ) ? '&tag=mobile' : '';
          query_posts( $query_string . '&posts_per_page=' . $gamelimit . $exclude_blog . $mobile_tag );

          if ( have_posts() ) {
            ?>
            <ul>
              <?php while ( have_posts() ) : the_post(); ?>
              <li>
                <?php if ( function_exists('is_leaderboard_game') && is_leaderboard_game() ) : ?>
                <div class="score_ribbon"></div>
                <?php endif; ?>
                <a href="<?php the_permalink() ?>" class="thumb_link" rel="bookmark" title="<?php echo the_title_attribute(); ?>"><?php myarcade_thumbnail( array( 'lazy_load' => ( get_option('fungames_lazy_load') == 'enable' ) ? true : false ) ); ?></a>
              </li>
              <?php
            endwhile;
            wp_reset_query();
          } else {
            esc_html_e("No games found", 'fungames');
          }
          ?>
          </ul>
          <div class="clear"></div>
        </div><?php // end sortable-game-box-list ?>
      </div><?php // end sortable-game-box-inner-content ?>
    </div><?php // end sortable-game-box-inner ?>

    <div class="clear"></div>

    <div id="sortable-game-box-order">
      <?php esc_html_e('Sort by', 'fungames'); ?>
      <select>
        <option value="?orderby=date&order=desc" selected="selected"><?php esc_html_e('Newest First', 'fungames'); ?></option>
        <option value="?orderby=date&order=asc"><?php esc_html_e('Oldest First', 'fungames'); ?></option>
        <?php if( function_exists('the_ratings') ) : ?>
        <option value="?r_sortby=highest_rated&r_orderby=desc"><?php esc_html_e('Highest Rated', 'fungames'); ?></option>
        <?php endif; ?>
        <?php if ( function_exists('the_views') ) : ?>
        <option value="?v_sortby=views&v_orderby=desc"><?php esc_html_e('Most Played', 'fungames'); ?></option>
        <?php endif; ?>
        <option value="?orderby=comment_count"><?php esc_html_e('Most Discussed', 'fungames'); ?></option>
        <option value="?orderby=title&order=asc"><?php esc_html_e('Alphabetically (A-Z)', 'fungames'); ?></option>
        <option value="?orderby=title&order=desc"><?php esc_html_e('Alphabetically (Z-A)', 'fungames'); ?></option>
      </select>
    </div>
  </div><?php // end sortable-game-box ?>
  <div class="clear"></div>

  <?php do_action('fungames_after_sortable_game_box'); ?>

<?php endif; ?>