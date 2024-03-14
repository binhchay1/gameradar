<?php
/**
 * Template to display Horizontal Category Boxes
 */
?>

<?php global $category, $games, $cat_id; ?>

<div class="gamebox">
  <h2>
    <a href="<?php echo get_category_link($cat_id); ?>">
      <?php echo $category->name; ?>
    </a>
  </h2>

  <?php // print out all games from this category ?>
  <?php foreach ($games as $post) : ?>
  <div class="game_title">
    <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo $post->post_title; ?>" >
      <span><?php myarcade_title(10); ?></span>
    </a>
    <br />
    <?php if ( function_exists('is_leaderboard_game') && is_leaderboard_game() ) : ?>
      <div class="score_ribbon"></div>
    <?php endif; ?>
    <a href="<?php the_permalink() ?>" class="thumb_link" rel="bookmark" title="<?php echo $post->post_title; ?>" >
      <?php myarcade_thumbnail( array( 'width' => 85, 'height' => 85, 'lazy_load' => ( get_option('fungames_lazy_load') == 'enable' ) ? true : false )); ?>
    </a>
  </div>
  <?php endforeach; ?>

  <div class="cat_link">
    <a href="<?php echo get_category_link($cat_id); ?>">
      <?php esc_html_e("More Games", "fungames"); ?>
    </a>
  </div>

 <div class="clear"></div>
</div>