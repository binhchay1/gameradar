<?php if ( ( is_home() || is_front_page() ) && get_option('fungames_step_carousel') == 'enable' )  : ?>
<script type="text/javascript">
  stepcarousel.setup({
    galleryid: 'mygallery',
    beltclass: 'belt',
    panelclass: 'panel',
    <?php if ( get_option('fungames_step_carousel_auto') == 'enable') : ?>
    autostep: {enable:true, moveby:1, pause:3000},
    <?php endif; ?>
    panelbehavior: {speed:500, wraparound:true, persist:true},
    defaultbuttons: {enable: true, moveby: 2, leftnav: ['<?php echo get_template_directory_uri(); ?>/images/arrleft.jpg', -10, 68], rightnav: ['<?php echo get_template_directory_uri(); ?>/images/arrright.jpg', 14, 68]},
    statusvars: ['statusA', 'statusB', 'statusC'],
    contenttype: ['external']
  })
</script>
<div id="myslides">
  <div id="mygallery" class="stepcarousel">
    <div class="belt">
      <?php
      if ( get_option('fungames_step_carousel_order') == 'Random') { $order = 'rand'; } else { $order = 'date'; }
      $catID = get_option('fungames_step_carousel_category');
      $games = get_option('fungames_step_carousel_games');
      if (!$games) $games = '20';
      $mobile_tag = ( 'enable' == get_option('fungames_mobile') && wp_is_mobile() ) ? '&tag=mobile' : '';
      $query = new WP_Query( 'cat='.$catID.'&showposts='.$games.'&orderby='.$order . $mobile_tag );
      while ($query->have_posts()) :
        $query->the_post();
        ?>
        <div class="panel">
          <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php esc_attr_e("Play", "fungames")?> <?php the_title_attribute(); ?>">
            <?php myarcade_thumbnail( array( 'lazy_load' => false ) ); ?>
          </a>
          <h2>
            <a href="<?php the_permalink() ?>" rel="bookmark" title="Play <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
          </h2>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</div>
<div class="clear"></div>
<?php endif; ?>