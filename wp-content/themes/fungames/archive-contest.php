<?php
/*
Template Name: Blog Template
*/
?>

<?php get_header(); ?>

<?php // content start ?>
<div id="content" class="content<?php echo get_option('fungames_sidebar_position'); ?>">

  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>

    <div class="singlepage" id="post-<?php the_ID(); ?>">
      <div class="title">
        <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php esc__e('Permanent Link to', 'fungames'); ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
      </div>

      <?php if (has_post_thumbnail()): ?>
      <div class="post-thumbnail">
        <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(esc_attr__('Permanent Link to %s', 'fungames'), the_title_attribute('echo=0')) ?>">
          <?php the_post_thumbnail() ?>
        </a>
      </div>
      <?php endif ?>

      <div class="cover">
        <div class="entry">
          <?php the_excerpt(); ?>
          <a class="readmore" href="<?php the_permalink(); ?>" title="<?php esc_attr_e('Read more about', 'fungames'); the_title_attribute(); ?>"><?php esc_html_e('Read more', 'fungames'); ?>&raquo;</a>
					<div class="clear"></div>
        </div>
      </div>
    </div>
		<?php endwhile; ?>

    <div id="navigation">
      <?php if(function_exists('wp_pagenavi')) : ?>
        <?php wp_pagenavi(); ?>
      <?php else: ?>
        <div class="post-nav clearfix">
          <p id="previous"><?php next_posts_link(esc_html__('Older contests &laquo;', 'fungames')) ?></p>
          <p id="next-post"><?php previous_posts_link(esc_html__('&raquo; Newer contests', 'fungames')) ?></p>
        </div>
      <?php endif; ?>
    </div>

	<?php else : ?>
    <div class="singlepage">
      <h2 class="title"><?php esc_html_e('Not Found' , 'fungames'); ?></h2>
      <div class="cover">
        <div class="entry">
          <p><?php esc_html_e("Sorry, but you are looking for something that isn't here.", 'fungames'); ?></p>
        </div>
      </div>
    </div>

	<?php endif; ?>

</div><?php // end content ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>