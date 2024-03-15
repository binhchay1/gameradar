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

<section>
  <h3 style="font-size: 20px; font-weight: bold; margin-bottom: 10px;">Related category</h3>
  <div class="top-cate-homepage">
    <div class="list-top-cate">
      <?php
      $cat_id = get_the_category()[0]->term_id;
      $exclude = array($cat_id);
      $cat_list = array();

      foreach (get_categories() as $cat) {
        if (!in_array($cat->term_id, $exclude) and $cat->name != 'TOP DOWNLOAD') {
          $cat_list[] = '<a href="' . esc_url(get_category_link($cat->term_id)) .
            '"><div class="in-list-top-cate">' . $cat->name . '</div></a>';
        }
      }

      echo implode(' ', $cat_list);
      ?>
    </div>
  </div>
</section>

<?php wp_reset_query(); ?>