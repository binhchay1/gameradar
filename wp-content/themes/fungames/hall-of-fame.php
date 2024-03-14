<?php
if ( (get_option('fungames_hall_of_fame') == 'enable') && defined('MYSCORE_VERSION') ) {
  // Get x best players
  $players = fungames_get_best_players(5);

  if ($players) {

    do_action('fungames_before_hall_of_fame');
    ?>
    <div id="hall-of-fame" class="fullcontent">
      <h2><?php echo get_option('fungames_hall_of_fame_box_title'); ?></h2>
      <ul>
        <?php
        foreach ($players as $player) {
          ?>
          <li>
            <div class="avatar"><?php echo get_avatar($player->user_id, 80); ?></div>
            <div class="name"><?php echo myscore_get_user_link($player->user_id); ?></div>
            <div class="plays">(<?php echo $player->plays; ?> plays)</div>
            <div class="highscores"><?php echo $player->highscores; ?> High Scores</div>
          </li>
          <?php
        }
        ?>
      </ul>
      <div class="clear"></div>
    </div>
    <?php

    do_action('fungames_after_hall_of_fame');
  }
}
?>