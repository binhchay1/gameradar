<?php
/**
 * MyArcadePlugin Pro Theme Options helps theme developers to create MyArcadePlugin Pro compatible theme backends.
 * @package MyArcadePlugin Pro Theme Options
 * @author Onedin Ibrocevic & Daniel Bakovic
 * @version 2.00
 */
/** Init Admin Menu * */
add_action('admin_menu', 'myabp_admin_menu_add');

/** Activate some theme features * */
add_action('after_setup_theme', 'fungames_setup');

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function fungames_setup() {
  global $options, $content_width;

  if ( ! isset( $content_width ) ) $content_width = 600;

  // Make theme available for translation
  // Translations can be filed in the /lang/ directory
  load_theme_textdomain( 'fungames', get_template_directory() . '/lang' );

  // Add default posts and comments RSS feed links to head.
  add_theme_support( 'automatic-feed-links' );

  // Let WordPress manage the document title.
  // By adding theme support, we declare that this theme does not use a
  // hard-coded <title> tag in the document head, and expect WordPress to
  // provide it for us.
  add_theme_support( 'title-tag' );

  // Add default posts and comments RSS feed links to head.
  add_theme_support( 'automatic-feed-links' );

  // We don't want to show our WordPress version
  remove_action('wp_head', 'wp_generator');

  // This theme uses wp_nav_menu() in two locations.
  register_nav_menus( array(
    'primary' => esc_html__('Primary Navigation', 'fungames'),
  ) );

  // This theme allows users to set a custom background
  if (version_compare(get_bloginfo('version'), "3.4", ">=")) {
    add_theme_support('custom-background');

    $custom_header_args = array(
        'default-image' => '',
        'random-default' => false,
        'width' => apply_filters('fungames_header_image_width', 1000),
        'height' => apply_filters('fungames_header_image_height', 117),
        'flex-height' => false,
        'flex-width' => false,
        'default-text-color' => '00000000',
        'header-text' => false,
        'uploads' => true,
        'wp-head-callback' => '',
        'admin-head-callback' => 'fungames_admin_header_style',
        'admin-preview-callback' => '',
    );

    add_theme_support('custom-header', $custom_header_args);
  } else {
    add_theme_support( 'custom-background');

    // Your changeable header business starts here
    define('HEADER_TEXTCOLOR', '000000');

    // The height and width of your custom header. You can hook into the theme's own filters to change these values.
    // Add a filter to fungames_header_image_width and fungames_header_image_height to change these values.
    define('HEADER_IMAGE_WIDTH', apply_filters('fungames_header_image_width', 1000));
    define('HEADER_IMAGE_HEIGHT', apply_filters('fungames_header_image_height', 118));

    // Don't support text inside the header image.
    define('NO_HEADER_TEXT', false);

    add_theme_support( 'custom-header' );
  }

  // Check the settings
  if (!get_option("fungames_color_scheme")) {
    // Set Default Settings
    foreach ($options as $value) {
      if (isset($value['id']) && isset($value['std'])) {
        add_option($value['id'], $value['std']);
      }
    }
  }

  // This theme uses post thumbnails
  add_theme_support('post-thumbnails');
  add_image_size('contest-promo', 280, 160);
}

function myabp_admin_menu_add() {
  if (function_exists('add_theme_page')) {
    /* Create the theme  menu */
    $page = add_theme_page(esc_html__('Theme Options', 'fungames'), esc_html__('Theme Options', 'fungames'), 'manage_options', basename(__FILE__), 'myabp_admin_menu_theme');
    /* Using registered $page handle to hook script load */
    add_action('admin_print_scripts-' . $page, 'myabp_admin_plugin_scripts');
  }
}

/** Enqueue needed script only when theme admin page is called * */
function myabp_admin_plugin_scripts() {
  wp_enqueue_script('jquery');
}

/** Create Admin Menu * */
function myabp_admin_menu_theme() {
  global $options;

  if (isset($_POST['action']) && ('save' == $_POST['action'])) {
    foreach ($options as $value) {
      if (isset($value['id'])) {
        if (isset($_REQUEST[$value['id']]) && is_array($_REQUEST[$value['id']])) :
          update_option($value['id'], implode(",", $_REQUEST[$value['id']]));
        else :
          if (isset($_REQUEST[$value['id']])) {
            update_option($value['id'], trim($_REQUEST[$value['id']]));
          } else {
            delete_option($value['id']);
          }
        endif;
      }
    }

    echo '<div id="message" class="updated fade"><p><strong>' . MYAPB_THEMENAME . ' settings saved.</strong></p></div>';
  } else if (isset($_REQUEST['action']) && ('reset' == $_REQUEST['action'])) {

    foreach ($options as $value) {

      if ( isset($value['id']) )
        delete_option($value['id']);

      if ( isset($value['id']) && isset($value['std']) )
        update_option($value['id'], trim($value['std']));
    }

    echo '<div id="message" class="updated fade"><p><strong>' . MYAPB_THEMENAME . ' settings reset.</strong></p></div>';
  }
  ?>

  <script type="text/javascript">
    jQuery(document).ready(function($) {

      $(".toggle_container").hide();
      $("h2.trigger").on('click', function(){
        $(this).toggleClass("active").next().slideToggle("slow");
      });

      /************* HEADER & FOOTER - Hide / Show option specific elements *************/
      // Init
  <?php
  // Header & Footer
  if (get_option('fungames_custom_favicon_status') != 'enable')
    echo "$('#fungames_custom_favicon').parent().hide();";
  if (get_option('fungames_custom_logo_status') != 'enable')
    echo "$('#fungames_custom_logo').parent().hide();";
  if (get_option('fungames_custom_headercode_status') != 'enable')
    echo "$('#fungames_custom_headercode').parent().hide();";
  if (get_option('fungames_custom_footercode_status') != 'enable')
    echo "$('#fungames_custom_footercode').parent().hide();";
  // Home Page
  if (get_option('fungames_frontpage_text_status') != 'enable')
    echo "$('#fungames_frontpage_text').parent().hide();";
  if (get_option('fungames_contest_box') != 'enable') {
    echo "$('#fungames_contest_promo_title').parent().hide();";
    echo "$('#fungames_contest_ids').parent().hide();";
  }
  if (get_option('fungames_hall_of_fame') != 'enable')
    echo "$('#fungames_hall_of_fame_box_title').parent().hide();";
  if (get_option('fungames_games_box') != 'enable') {
    echo "$('#fungames_sortable_game_box_title').parent().hide();";
    echo "$('#fungames_games_box_count').parent().hide();";
  }
  if (get_option('fungames_step_carousel') != 'enable') {
    echo "$('tr#fungames_step_carousel_category').hide();";
    echo "$('#fungames_step_carousel_auto').parent().hide();";
    echo "$('#fungames_step_carousel_order').parent().hide();";
    echo "$('#fungames_step_carousel_games').parent().hide();";
  }
  if (get_option('fungames_nivoslider') != 'enable') {
    echo "$('tr#fungames_nivoslider_category').hide();";
    echo "$('#fungames_nivoslider_auto').parent().hide();";
    echo "$('#fungames_nivoslider_order').parent().hide();";
  }
  if (get_option('fungames_pregame_page') != 'enable') {
    echo "$('#fungames_endpoint_play').parent().hide();";
  }
  if (get_option('fungames_progressbarstatus') != 'enable') {
    echo "$('#fungames_progressbardelay').parent().hide();";
    echo "$('#fungames_progressbarspeedindex').parent().hide();";
    echo "$('#fungames_progressbartextloadstatus').parent().hide();";
    echo "$('#fungames_progressbartextload').parent().hide();";
    echo "$('#fungames_progressbartextloadlimit').parent().hide();";
    echo "$('#fungames_progressbartextcolor').parent().hide();";
    echo "$('#fungames_progressbarbordercolor').parent().hide();";
    echo "$('#fungames_progressbarbackgroundcolor').parent().hide();";
    echo "$('#fungames_progressbarloadcolor').parent().hide();";
  }
  ?>

        // On Change
        // Header & Footer
        $('#fungames_custom_favicon_status').change(function() {
          if( $(this).val() == "enable" )
            $('#fungames_custom_favicon').parent().show("fast");
          else
            $('#fungames_custom_favicon').parent().hide();
        });
        $('#fungames_custom_logo_status').change(function() {
          if( $(this).val() == "enable" ) $('#fungames_custom_logo').parent().show("fast");
          else $('#fungames_custom_logo').parent().hide();
        });
        $('#fungames_custom_headercode_status').change(function() {
          if( $(this).val() == "enable" ) {
            $('#fungames_custom_headercode').parent().show("fast");
          } else {
            $('#fungames_custom_headercode').parent().hide("fast");
          }
        });
        $('#fungames_custom_footercode_status').change(function() {
          if( $(this).val() == "enable" ) {
            $('#fungames_custom_footercode').parent().show("fast");
          } else {
            $('#fungames_custom_footercode').parent().hide();
          }
        });
        // Home Page
        $('#fungames_frontpage_text_status').change(function() {
          if( $(this).val() == "enable" )
            $('#fungames_frontpage_text').parent().show("fast");
          else
            $('#fungames_frontpage_text').parent().hide();
        });
        $('#fungames_contest_box').change(function() {
          if( $(this).val() == "enable" ) {
            $('#fungames_contest_promo_title').parent().show("fast");
            $('#fungames_contest_ids').parent().show("fast");
          } else {
            $('#fungames_contest_promo_title').parent().hide();
            $('#fungames_contest_ids').parent().hide();
          }
        });
        $('#fungames_hall_of_fame').change(function() {
          if( $(this).val() == "enable" )
            $('#fungames_hall_of_fame_box_title').parent().show("fast");
          else
            $('#fungames_hall_of_fame_box_title').parent().hide();
        });
        $('#fungames_games_box').change(function() {
          if( $(this).val() == "enable" ) {
            $('#fungames_sortable_game_box_title').parent().show("fast");
            $('#fungames_games_box_count').parent().show("fast");
          } else {
            $('#fungames_sortable_game_box_title').parent().hide();
            $('#fungames_games_box_count').parent().hide();
          }
        });
        $('#fungames_step_carousel').change(function() {
          if( $(this).val() == "enable" ) {
            $('tr#fungames_step_carousel_category').show("fast");
            $('#fungames_step_carousel_auto').parent().show("fast");
            $('#fungames_step_carousel_order').parent().show("fast");
            $('#fungames_step_carousel_games').parent().show("fast");
          } else {
            $('tr#fungames_step_carousel_category').hide();
            $('#fungames_step_carousel_auto').parent().hide();
            $('#fungames_step_carousel_order').parent().hide();
            $('#fungames_step_carousel_games').parent().hide();
          }
        });
        $('#fungames_nivoslider').change(function() {
          if( $(this).val() == "enable" ) {
            $('tr#fungames_nivoslider_category').show("fast");
            $('#fungames_nivoslider_auto').parent().show("fast");
            $('#fungames_nivoslider_order').parent().show("fast");
          } else {
            $('tr#fungames_nivoslider_category').hide();
            $('#fungames_nivoslider_auto').parent().hide();
            $('#fungames_step_nivoslider_order').parent().hide();
          }
        });
        $('#fungames_pregame_page').change(function() {
          if( $(this).val() == "enable" )
            $('#fungames_endpoint_play').parent().show("fast");
          else
            $('#fungames_endpoint_play').parent().hide();
        });
        $('#fungames_progressbarstatus').change(function() {
          if( $(this).val() == "enable" ) {
            $('#fungames_progressbardelay').parent().show("fast");
            $('#fungames_progressbarspeedindex').parent().show("fast");
            $('#fungames_progressbartextloadstatus').parent().show("fast");
            $('#fungames_progressbartextload').parent().show("fast");
            $('#fungames_progressbartextloadlimit').parent().show("fast");
            $('#fungames_progressbartextcolor').parent().show("fast");
            $('#fungames_progressbarbordercolor').parent().show("fast");
            $('#fungames_progressbarbackgroundcolor').parent().show("fast");
            $('#fungames_progressbarloadcolor').parent().show("fast");
          } else  {
            $('#fungames_progressbardelay').parent().hide();
            $('#fungames_progressbarspeedindex').parent().hide();
            $('#fungames_progressbartextloadstatus').parent().hide();
            $('#fungames_progressbartextload').parent().hide();
            $('#fungames_progressbartextloadlimit').parent().hide();
            $('#fungames_progressbartextcolor').parent().hide();
            $('#fungames_progressbarbordercolor').parent().hide();
            $('#fungames_progressbarbackgroundcolor').parent().hide();
            $('#fungames_progressbarloadcolor').parent().hide();
          }
        });
      });


      function check_reset() {
        if (document.resetform.reset_chk.checked == false) {
          alert('Are you sure? Check the box to load default theme settings.');
          return false;
        }
        return true;
      }
  </script>

  <style type="text/css">
    .fungames_options 			{ width: 500px;	margin: 0px; }
    .clear 			{ clear: both; }
    h1 				{ font: 4em normal Georgia, 'Times New Roman', Times, serif; text-align:center; padding: 20px 0;	color: #aaa; }
    h1 span 		{ color: #666; }
    h1 small		{ font: 0.3em normal Verdana, Arial, Helvetica, sans-serif; text-transform:uppercase; letter-spacing: 1.5em; display: block; color: #666; }
    h2.trigger 		{ padding: 0 0 0 50px; margin: 0 0 5px 0; background: url(<?php echo get_template_directory_uri() . '/inc/'; ?>h2_trigger_a.gif) no-repeat; height: 46px; line-height: 46px; width: 450px; font-size: 2em; font-weight: normal; float: left; }
    h2.trigger a 	{ color: #fff; text-decoration: none; display: block; }
    h2.trigger a:hover { color: #ccc; }
    h2.active 		{ background-position: left bottom; }

    .toggle_container 			{ margin: 0px 0px 10px 0px; padding: 0; border-top: 1px solid #d6d6d6; background: #f0f0f0 url(<?php echo get_template_directory_uri() . '/inc/'; ?>toggle_block_stretch.gif) repeat-y left top; overflow: hidden; font-size: 1.2em; width: 500px; clear: both; }
    .toggle_container .block 	{ padding: 5px 20px; background: url(<?php echo get_template_directory_uri() . '/inc/'; ?>toggle_block_btm.gif) no-repeat left bottom; }
    .toggle_container .block p 	{ padding: 5px 0; margin: 5px 0; }
    .toggle_container h3 		{ font: 2.5em normal Georgia, "Times New Roman", Times, serif; margin: 5px 0px; padding: 0 0 0px 0; border-bottom: 1px dashed #ccc; }
    .toggle_container small		{ width: 100%; margin: 3px 0px; padding: 0px; }
    .submit input.button-primary     {margin-top: 15px;}
  </style>

  <div class="wrap">
    <h2>
      <strong><?php echo MYAPB_THEMENAME; ?> <?php echo esc_html__('Theme Options', 'fungames'); ?></strong>
    </h2>

    <form method="post" class="fungames_options">
  <?php
  foreach ($options as $value) :
    switch ($value['type']) {

      case "open": {
          echo '<h2 class="trigger"><a href="#">';
        } break;

      case "close": {
          echo '</table></div></div><div class="clear"></div>';
        } break;

      case "savebutton": {
          ?>
              <tr>
                <td style="text-align: right;">
                  <span class="submit"><input name="Save" type="submit" value="Save changes" class="button-primary" /></span>
                </td>
              </tr>
          <?php
        } break;

      case "plugincheck": {
          ?>
              <tr>
                <td>
              <?php if (function_exists($value['function']) || class_exists($value['function'])) {
                $imgsrc = 'yes.png';
              } else {
                $imgsrc = 'no.png';
              } ?>
                  <h3><img alt="no" src="<?php echo get_template_directory_uri(); ?>/inc/<?php echo $imgsrc; ?>" /> - <?php echo $value['name']; ?></h3>
                </td>
              </tr>
          <?php
        } break;

      case "title": {
          echo $value['name'];
          ?>
              </a></h2>
              <div class="toggle_container">
                <div class="block">
                  <table class="optiontable" width="100%">
          <?php if (isset($value['desc']) && !empty($value['desc'])) : ?>
                      <tr>
                        <td>
                          <i><?php echo $value['desc']; ?></i>
                        </td>
                      </tr>
          <?php endif; ?>
                    <?php
                  } break;

                case "sub-title": {
                    ?>
                    <tr>
                      <td>
                        <h3><?php echo $value['name']; ?></h3>
          <?php if (isset($value['desc'])) : ?>
                          <i><?php echo $value['desc']; ?></i>
                        <?php endif; ?>
                      </td>
                    </tr>
          <?php
        } break;

      case 'text': {
          ?>
                    <tr>
                      <td>
          <?php
          $level = isset($value['level']) ? $value['level'] : '4';
          ?>
                    <h<?php echo $level; ?> <?php if ($level == 4) echo 'style="padding-top: 3px; border-top: 1px dashed #ccc;"'; ?>><?php echo $value['name']; ?></h<?php echo $level; ?>>
                    <small><?php echo $value['desc']; ?></small>
                    <input style="width:100%;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if (get_option($value['id']) != "") {
                echo esc_attr(stripslashes(get_option($value['id'])));
              } else {
                echo esc_attr(stripslashes($value['std']) );
              } ?>" />
                    </td>
                    </tr>
                    <?php
                  } break;

                case 'textarea': {
                    ?>
                    <tr>
                      <td>
                        <?php
                        $level = isset($value['level']) ? $value['level'] : '4';
                        ?>
                    <h<?php echo $level; ?> <?php if ($level == 4) echo 'style="padding-top: 3px; border-top: 1px dashed #ccc;"'; ?>><?php echo $value['name']; ?></h<?php echo $level; ?>>
                    <small><?php echo $value['desc']; ?></small>
                    <textarea name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" style="width:100%; height:200px;" cols="" rows=""><?php if (get_option($value['id']) != "") {
                          echo stripslashes(get_option($value['id']));
                        } else {
                          echo stripslashes($value['std']);
                        } ?></textarea>
                    </td>
                    </tr>
                        <?php
                      } break;

                    case 'select': {
                        ?>
                    <tr>
                      <td>
                      <?php
                      $level = isset($value['level']) ? $value['level'] : '4';
                      ?>
                    <h<?php echo $level; ?> <?php if ($level == 4) echo 'style="padding-top: 3px; border-top: 1px dashed #ccc;"'; ?>><?php echo $value['name']; ?></h<?php echo $level; ?>>
                    <small><?php echo $value['desc']; ?></small><br />
                    <select style="width:240px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
                        <?php
                        $selection = get_option($value['id']);
                        if (empty($selection))
                          $selection = $value['std'];
                        foreach ($value['options'] as $option) {
                          ?>
                        <option<?php if ($selection == $option) {
              echo ' selected="selected"';
            } ?>>
                      <?php echo $option; ?>
                        </option><?php } ?>
                    </select>
                    </td>
                    </tr>
                        <?php
                      } break;

                    case "checkbox": {
                        ?>
                    <tr>
                      <td>
                    <?php
                    $level = isset($value['level']) ? $value['level'] : '4';
                    ?>
                    <h<?php echo $level; ?> <?php if ($level == 4) echo 'style="padding-top: 3px; border-top: 1px dashed #ccc;"'; ?>><?php echo $value['name']; ?></h<?php echo $level; ?>>
                    <small><?php echo $value['desc']; ?></small><br />
          <?php if (get_option($value['id'])) {
            $checked = "checked=\"checked\"";
          } else {
            $checked = "";
          } ?>
                    <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
                    </td>
                    </tr>
                        <?php
                      } break;

                    case "categories_checkboxes": {
                        ?>
                    <tr id="<?php echo $value['id']; ?>">
                      <td>
                        <h4><?php echo $value['name']; ?></h4>
                        <small><?php echo $value['desc']; ?></small><br />
                    <?php
                    // Get excluded categories
                    $excluded_cats = explode(',', get_option($value['id']));
                    ?>
                    <?php
                    foreach ($value['options'] as $cat_id => $option) {
                      foreach ($excluded_cats as $exclude_cat) {
                        if ($exclude_cat == $cat_id) {
                          $checked = ' checked="checked"';
                          break;
                        } else {
                          $checked = "";
                        }
                      }
                      ?>
                          <input type="checkbox" name="<?php echo $value['id'] . '[]'; ?>" value="<?php echo $cat_id; ?>"<?php echo $checked; ?> /> <?php echo $option; ?><br />
          <?php } ?>
                      </td>
                    </tr>
          <?php
        } break;
    } // End Switch
  endforeach;
  ?>
            <p class="submit">
              <input name="save" type="submit" value="Save changes" />
              <input type="hidden" name="action" value="save" />
            </p>
            </form>

            <div style="text-align: right">
              <form method="post" name="resetform" onsubmit="return check_reset(this.order)">
                <p class="submit">
                  <input name="reset_chk" type="checkbox" /> Yes, I want to load the default settings.<br />
                  <input name="reset" type="submit" value="Load default theme settings" class="button-primary" />
                  <input type="hidden" name="action" value="reset" />
                </p>
              </form>

              <strong><?php echo MYAPB_THEMENAME; ?> v<?php echo MYAPB_THEMEVERSION; ?></strong>
            </div>
        </div>
<?php } ?>