<?php /*
List template
This template returns the related posts as a comma-separated list.
Author: Daniel Bakovic
*/
?>
<?php if ( $related_query->have_posts() ): ?>
  <div class="related">
    <h3><?php esc_html_e("More Games", "fungames"); ?></h3>
    <ul>
    <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
      <li>
        <div class="moregames">
          <div class="img-pst">
            <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
              <?php myarcade_thumbnail( array( 'width' => 80, 'height' => 80, 'class' => 'alignleft', 'lazy_load' => ( get_option('fungames_lazy_load') == 'enable' ) ? true : false ) ); ?>
            </a>
          </div>

          <br />

          <h4>

            <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
              <?php the_title(); ?>
            </a>
          </h4>

          <span><?php myarcade_excerpt(280); ?></span>
        </div> <?php // end moregames ?>
      </li>
    <?php endwhile; ?>
    </ul>
  </div>
<?php endif; ?>