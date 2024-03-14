<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>

<?php wp_head(); ?>
<style type="text/css">
#fullgame {
  width:95%;
  margin:5px auto 0 auto;
  display:block;
  position: relative;
}

#fullgame h2 {
  background:#C9CCD3;
  line-height:40px;
  margin-bottom: 5px;
  -moz-border-radius: 4px;
  -webkit-border-radius: 4px;
  border-radius:4px;
}
</style>
</head>

<body>
  <center>
    <div id="fullgame">
      <h2><a href="javascript:history.go(-1)">&laquo; <?php esc_html_e('Go Back To', 'fungames'); ?>: <?php bloginfo('name'); ?> </a></h2>
    </div>
    <?php
    if ( function_exists( "get_game" ) ) {
      global $mypostid, $post; $mypostid = $post->ID;
      echo myarcade_get_leaderboard_code();
      echo get_game($mypostid, $fullsize = false, $preview = false, $fullscreen = true);
    }
    else {
      echo '<div class="error"><p>' . esc_html__("Please install MyArcadePlugin in order to display games!", 'fungames' ) . '</p></div>';
    }
    ?>
  </center>

  <?php wp_footer(); ?>
</body>
</html>