<?php // Display contest promo box if enabled
if ( (get_option('fungames_contest_box') == 'enable') && function_exists('myarcadecontest_init') ) {

  $ids = get_option('fungames_contest_ids');

  if ( empty($ids) ) {
    // Get two latest opened contests
    $query_arg =  array ( 'post_type'       => 'contest',
                        'post_status'     => 'publish',
                        'orderby'         => 'date',
                        'order'           => 'DESC',
                        'posts_per_page'  => '2',
                        'meta_query'      => array(
                                          'relation' => 'AND',
                                          array(
                                          'key'     => 'startdate',
                                          'value'   => date( "Y-m-d" ),
                                          'compare' => '<='
                                          ),
                                          array(
                                          'key'     => 'enddate',
                                          'value'   => date( "Y-m-d" ),
                                          'compare' => '>='
                                          ),
                                          array(
                                          'key'     => 'complete',
                                          'value'   => 'yes',
                                          'compare' => '!='
                                          )
                                        )
                        );
  }
  else {
    // Get specific contests
    $query_arg = array( 'post_type' => 'contest', 'post__in'  => array ($ids), 'posts_per_page'  => '2' );
  }

  $contests = new WP_Query( $query_arg );

  if ( $contests->have_posts() ) {
    do_action('fungames_before_contest_promo');
    ?>
    <div id="promo-contests" class="fullcontent">
      <h2><?php echo get_option('fungames_contest_promo_title'); ?></h2>
      <ul>
      <?php
      while ($contests->have_posts()): $contests->the_post();
        ?>
        <li>
          <?php if (has_post_thumbnail()): ?>
          <div class="thumb">
            <a href="<?php the_permalink(); ?>" title="<?php echo the_title_attribute(); ?>">
              <?php the_post_thumbnail('contest-promo') ?>
            </a>
          </div>
          <?php endif; ?>
          <div class="name">
            <a href="<?php the_permalink(); ?>" title="<?php echo the_title_attribute(); ?>">
              <?php the_title('<h3>','</h3>'); ?>
            </a>
          </div>
        </li>
        <?php
      endwhile;
      ?>
      </ul>
    </div>
    <?php
    do_action('fungames_after_contest_promo');
  }

  wp_reset_query();
}
?>