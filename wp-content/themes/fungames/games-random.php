<div class="related">
  <h3><?php esc_html_e("Related Game", "fungames"); ?></h3>
  <ul>
    <?php
    $blog = get_cat_ID(get_option('fungames_blog_category'));
    if ($blog) {
      $exclude = '&exclude=' . $blog . ',';
    } else {
      $exclude = '';
    }
    $mobile_tag = ('enable' == get_option('fungames_mobile') && wp_is_mobile()) ? '&tag=mobile' : '';

    $relatedgames = new WP_Query('showposts=10&orderby=rand' . $exclude . $mobile_tag);
    if ($relatedgames->have_posts())
      while ($relatedgames->have_posts()) :
        $relatedgames->the_post();
    ?>
      <li>
        <div class="moregames">
          <div class="img-pst">
            <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
              <?php
              myarcade_thumbnail(array(
                'width' => 80,
                'height' => 80,
                'class' => 'alignleft',
                'lazy_load' => (get_option('fungames_lazy_load') == 'enable') ? true : false
              ));
              ?>
            </a>
          </div>
          <br />

          <h4>
            <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
              <?php the_title(); ?>
            </a>
          </h4>

          <?php myarcade_excerpt(280); ?>
        </div> <?php // end moregames 
                ?>
      </li>
    <?php endwhile; ?>
  </ul>
</div>

<style>
  .list-category-ingame {
    background: #fff;
    border: 1px solid #DEDCDD;
    padding: 15px;
    border-radius: 10px;
    display: flex;
    justify-content: space-between;
  }

  .list-category-ingame a {
    margin-left: 20px;
  }
</style>
<div class="list-category-ingame">
  <?php wp_list_categories(['show_count' => 0, 'style' => '<div>']); ?>
</div>

<?php wp_reset_query(); ?>