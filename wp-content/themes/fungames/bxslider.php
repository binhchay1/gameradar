<?php
if ( get_option('fungames_nivoslider') == 'enable' ) {
  // Get the post order
  if ( get_option('fungames_nivoslider_order') == 'Random') {
    $order = 'rand';
  } else { $order = 'date'; }

  $catID = get_option('fungames_nivoslider_category');
  $mobile_tag = ( 'enable' == get_option('fungames_mobile') && wp_is_mobile() ) ? 'mobile' : '';

  $game_query = array (
    'showposts' => 10,
    'cat' => $catID,
    'orderby' => $order,
    'tag' => $mobile_tag,
    'meta_query' => array (
      array(
        'key' => 'mabp_screen1_url',
        'value' => 'http',
        'compare' => 'LIKE'
      )
    )
  );

  $games = new WP_Query($game_query);

  if ( $games->have_posts() ) {
    ?>
    <div class="contentbox customslider">
      <div class="bxslider-loading"></div>
      <ul class="bxslider" style="visibility:hidden">
      <?php
      while ($games->have_posts()) {
        $games->the_post();
        ?>
        <li>
          <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
            <img src="<?php myarcade_get_screenshot_url(); ?>" alt="<?php the_title_attribute(); ?>" title="<?php the_title(); ?>"/>
            <span class="caption"><?php the_title(); ?></span>
          </a>
        </li>
        <?php
      }
      wp_reset_query();
      ?>
      </ul>
    </div>
    <script type="text/javascript">
      jQuery(document).ready(function($) {
        $('.bxslider').bxSlider({
          captions: false,
          adaptiveHeight:false,
          pager:true,
          pagerType: 'full',
          controls:false,
          slideWidth:630,
          mode:'fade',
          auto:true,
          responsive: true,
          onSliderLoad: function(currentIndex){
            $('.bxslider').css('visibility', 'visible');
            $('.bxslider-loading').remove();
          }
        });
      });
    </script>
    <?php
  }
}