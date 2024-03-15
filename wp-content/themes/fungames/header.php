<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="profile" href="https://gmpg.org/xfn/11" />
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <?php wp_head(); ?>
</head>
<style>
  .fa {
    padding: 15px;
    text-align: center;
    text-decoration: none;
    margin: 5px 2px;
  }

  .fa:hover {
    opacity: 0.7;
  }

  .fa-facebook {
    background: #3B5998;
    color: white;
  }

  .fa-twitter {
    background: #55ACEE;
    color: white;
  }

  .fa-youtube {
    background: #bb0000;
    color: white;
  }

  html {
    margin-top: 0;
  }
</style>

<body <?php body_class(); ?>>
  <?php wp_body_open();  ?>
  <?php
  // Do some actions before we open the page wrapper
  do_action('fungames_before_wrapper');
  ?>
  <div id="wrapper">
    <?php
    // Custom Header Image
    $header_img = get_header_image();
    if (!empty($header_img)) {
      $header_bg_style = 'style="background-image:url(' . $header_img . ');"';
    } else {
      $header_bg_style = '';
    }

    // Do some actions before the header output
    do_action('fungames_before_header');

    ?>
    <div id="top" <?php echo $header_bg_style; ?> style="padding: 10px">

      <div class="blogname" style="display: flex;">

        <a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>">
          <?php fungames_logo(); ?>
        </a>
        <?php if (is_front_page() && is_home()) { ?>
          <?php
          // Generate dynamic heading for SEO
          $h1Homepage = get_option('h1_homepage');
          ?>

          <h1><?php echo $h1Homepage ?></h1>
        <?php } ?>

        <?php if (is_category() and !is_category(array(1))) { ?>
          <?php
          // Generate dynamic heading for SEO
          $category = get_category(get_query_var('cat'));
          $cat_id = $category->cat_ID;
          $result = $wpdb->get_results("SELECT * FROM wp_category_custom WHERE category_id = $cat_id");
          if (!empty($result)) {
          ?>

            <h1><?php echo $result[0]->title ?></h1>
        <?php }
        } ?>
      </div>
      <div style="text-align: right;">
        <a href="https://twitter.com/gameradar24h" class="fa fa-twitter"></a>
        <a href="https://www.youtube.com/@Gameradar24h" class="fa fa-youtube"></a>
        <a href=" https://www.facebook.com/profile.php?id=61556511675047" class="fa fa-facebook"></a>
      </div>

      <?php // include header banner 
      ?>
      <div id="headbanner"> <?php echo stripslashes(get_option('fungames_headerad')); ?></div>

      <div class="clear"></div>
    </div> <?php // end top 
            ?>

    <?php
    // Do some actions before the main menu
    do_action('fungames_before_menu');
    ?>

    <?php // Header menu 
    ?>
    <div id="catcontainer">
      <div id="catmenu">
        <?php
        // show header menu
        wp_nav_menu(
          array(
            'theme_location'  => 'primary',
            'menu_id' => '',
            'container' => '',
            'fallback_cb' => 'fungames_default_header_menu'
          )
        );
        ?>
      </div>
      <div class="clear"></div>
    </div>

    <div class="clear"></div>

    <?php
    // Do some actions after the main menu
    do_action('fungames_after_menu');
    ?>

    <div id="fgpage">
      <?php
      // Include the step carousel slider if activated (Only on the front page)
      get_template_part('step-carousel');

      // Display Breadcrub Navigation on all pages except on the front page
      fungames_breadcumb();

      // Include the login box
      get_template_part('loginform');

      // Do some actions before the content wrap
      do_action('fungames_before_content');
