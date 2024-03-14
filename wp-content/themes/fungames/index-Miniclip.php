<?php
/**
 * Template to display Vertical Category Boxes
 */
?>
<?php global $games, $category, $cat_id, $height;?>

<div class="gamebox" <?php echo $height;?>>
  <h2>
    <a href="<?php echo get_category_link($cat_id); ?>">
      <?php echo $category->name; ?>
    </a>
  </h2>

  <?php $gamecounter = 0; ?>
  <?php foreach ($games as $post) : ?>
    <?php $gamecounter++; ?>
    <?php if ($gamecounter <= 2) : ?>
    <div class="game_title">
      <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo $post->post_title; ?>" >
        <?php myarcade_title(10); ?>
      </a>
      <br />
      <?php if ( function_exists('is_leaderboard_game') && is_leaderboard_game() ) : ?>
        <div class="score_ribbon"></div>
      <?php endif; ?>
      <a href="<?php the_permalink() ?>" class="thumb_link" rel="bookmark" title="<?php echo $post->post_title; ?>" >
        <?php myarcade_thumbnail( array( 'width' => 85, 'height' => 85, 'lazy_load' => ( get_option('fungames_lazy_load') == 'enable' ) ? true : false ) ); ?>
      </a>
    </div>
  <?php else: ?>
    <?php if ($gamecounter == 3) : ?>
    <div class="clear"></div>
    <ul class="games-ul">
    <?php endif; ?>
      <li>
        <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"> <?php myarcade_title(20); ?></a>
      </li>
  <?php endif; ?>
  <?php endforeach; ?>
    </ul>

  <div class="cat_link">
    <a href="<?php echo get_category_link($cat_id); ?>">
      <?php esc_html_e("More Games", "fungames"); ?>
    </a>
  </div>

  <div class="clear"></div>
</div>