<?php
/**
 * Template to display Full Width Content
 */
?>

<?php global $games, $category, $cat_id, $height; ?>

<div class="gamebox" <?php echo $height; ?>>
  <h2>
    <a href="<?php echo get_category_link($cat_id); ?>">
      <?php echo $category->name; ?>
    </a>
  </h2>

  <div class="spacer"></div>

  <?php foreach ($games as $post) : ?>
  <div class="game_title">

    <span class="game_thumb">
      <?php if ( function_exists('is_leaderboard_game') && is_leaderboard_game() ) : ?>
        <div class="score_ribbon"></div>
      <?php endif; ?>
      <a href="<?php the_permalink() ?>" class="thumb_link" rel="bookmark" title="<?php echo $post->post_title; ?>" ><?php myarcade_thumbnail( array( 'width' => 75, 'height' => 75, 'lazy_load' => ( get_option('fungames_lazy_load') == 'enable' ) ? true : false ) ); ?></a>
    </span>

    <span class="game_details">
      <h3>
        <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo $post->post_title; ?>" >
          <?php myarcade_title(20); ?>
        </a>
      </h3>
      <span itemprop="text"><?php myarcade_excerpt(130) ?></span>
    </span>

  </div>
  <?php endforeach; ?>

  <div class="cat_link">
    <a href="<?php echo get_category_link($cat_id); ?>">
      <?php esc_html_e("More Games", "fungames"); ?>
    </a>
  </div>

  <div class="clear"></div>
</div>