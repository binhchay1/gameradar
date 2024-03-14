<?php
/* Single Post Template: Blog Post Template */
?>

<?php get_header(); ?>

<div id="content" class="content<?php echo get_option('fungames_sidebar_position'); ?>">
  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
      <div class="singlepage" id="post-<?php the_ID(); ?>">
        <div class="title">
          <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php esc_attr_e("Permanent Link to", 'fungames') ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
          <div class="date"><span class="author"><?php esc_html_e("Posted by", 'fungames') ?> <?php the_author(); ?></span> <span class="clock"> <?php esc_html_e("on", 'fungames') ?> <?php the_time('F - j - Y'); ?></span></div>
        </div>

        <div class="cover">
          <div class="entry">
            <?php $banner = get_option('fungames_adcontent'); ?>
              <?php if ($banner) : ?>
                <div style="float:left;margin: 0 10px 10px 0;">
                  <?php echo stripslashes($banner); ?>
                </div>
              <?php endif; ?>
            <?php the_content(esc_html__('Read more..', 'fungames')); ?>
            <div class="clear"></div>

            <?php fungames_share(); ?>
          </div>
        </div>
      </div>

      <div class="clear"></div>

      <div class="allcomments">
        <?php comments_template(); ?>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="title">
      <h2><?php esc_html_e('Oops.. Nothing Found!', 'fungames'); ?></h2>
    </div>
    <div class="cover">
      <p>
        <?php esc_html_e('I think what you are looking for is not here or it has been moved. Please try a different search..', 'fungames'); ?>
      </p>
    </div>
  <?php endif; ?>
</div> <?php // end content ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>