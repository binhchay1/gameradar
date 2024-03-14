<div id="content" class="content<?php echo get_option('fungames_sidebar_position'); ?>">
  <div class="single_game" id="post-<?php the_ID(); ?>">

    <div class="title">
      <h1>
        <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
      </h1>

      <div class="ratings_box">
        <?php if(function_exists('the_ratings')) { the_ratings(); } ?>
      </div>
    </div> <?php // end title ?>

    <div class="cover">
      <div class="entry">
        <span class="game_thumb floatleft">
          <a href="<?php the_permalink() ?>" class="thumb_link" rel="bookmark" title="<?php echo $post->post_title; ?>" ><?php myarcade_thumbnail( array( 'width' => 75, 'height' => 75, 'lazy_load' => ( get_option('fungames_lazy_load') == 'enable' ) ? true : false ) ); ?></a>
        </span>
        <?php
        // Show content banner if available
        $content_banner = get_option('fungames_adcontent');
        if ( !empty($content_banner) ) {
          ?>
          <div class="adright">
            <?php echo stripslashes($content_banner); ?>
          </div>
          <?php
        }

        // Display post content
        the_content();

        ?><div class="clear"></div><?php

        if (function_exists('the_views')) {
          ?>
          <h3>
            <?php esc_html_e("Game Stats", "fungames"); ?>
          </h3>
          <?php the_views(); ?>
          <br /><br />
          <?php
        }

        // Display game screen shots if available and enabled
        if ( (get_option('fungames_dispay_screens') == 'enable') && myarcade_count_screenshots() ) {
          ?>
          <div class="clear"></div>
          <h3><?php the_title(''); ?> <?php esc_html_e('Screen Shots', 'fungames'); ?></h3>
          <div class="screencenter">
            <?php myarcade_all_screenshots(130, 130, 'screen_thumb'); ?>
          </div>
          <?php
        }

        // Display the game Video
        if ( get_option('fungames_dispay_video') == 'enable') {
          $video = myarcade_video();
          if ( $video ) {
            ?>
            <div class="game_video">
              <?php echo "<h3>" . esc_html__("Game Video", 'fungames') . "</h3>"; ?>
              <div class="embed_video">
                <?php echo $video; ?>
              </div>
            </div>
            <?php
          }
        }

        // Display game tags
        the_tags( '<h3>'.esc_html__("Game Tags", "fungames").'</h3><p>', ', ', '</p>');
        ?>

        <h3><?php esc_html_e("Game Categories", "fungames"); ?></h3>
        <div class="category">
          <?php the_category(', '); ?>
        </div>

        <?php
        // Display some manage links if logged in user is an admin
        fungames_admin_links();
        ?>

        <?php
        // Display Google Rich Snippets
        fungames_rich_snippet();
        ?>

        <?php
        // Display sexy bookmarks if plugin installed
        fungames_share();
        ?>

        <div class="clear"></div>
      </div> <?php // end entry ?>
    </div> <?php // end cover ?>
  </div> <?php // single_game ?>

  <?php
  // Show the game share box
  if ( function_exists('get_game_code') && (get_option('fungames_share_box') == 'enable') && (get_post_meta($post->ID, "mabp_game_type", true) != 'embed') ) {
    ?>
    <div class="single_game">
      <h2><?php esc_html_e("Do You Like This Game?", "fungames"); ?></h2>
      <p><?php esc_html_e("Embed this game:", "fungames"); ?></p>
      <form name="select_all"><textarea name="text_area" onClick="javascript:this.form.text_area.focus();this.form.text_area.select();"><a href="<?php echo home_url();?>"><?php bloginfo('name'); ?></a><br /><?php echo get_game_code(); ?></textarea>
      </form>
    </div>
    <?php
  }
  ?>

  <div class="clear"></div>

  <div class="allcomments">
    <?php comments_template(); ?>
  </div>

  <?php
  // Display related (YARPP required) or random games
  fungames_related();
  ?>

</div> <?php // end content ?>
<div id="lightsoff"></div>

<?php get_sidebar(); ?>
<div class="clear"></div>
