<script type="text/javascript">
  var counter = 1;
  var progress_width;

  function loadgame(wait_time) {
    var loadtext=document.getElementById('progressbarloadtext').style;
    var percentlimit = <?php echo get_option('fungames_progressbartextloadlimit'); ?>;
    var speedindex = <?php echo get_option('fungames_progressbarspeedindex'); ?>;
    var percentlimitstatus = "<?php echo get_option('fungames_progressbartextloadstatus'); ?>";
    speedindex = speedindex*2;
    if ( counter < wait_time) {
      counter = counter + 1;
      document.getElementById("progressbarloadbg").style.width = counter + "px";
      var percentage = Math.round( counter / wait_time * 100);
      document.getElementById("progresstext").innerHTML = percentage+" %";
      window.setTimeout("loadgame('" + wait_time + "')", speedindex );
      if ( (percentage >= percentlimit) & (percentlimitstatus == "enable" ) ) {
        loadtext.display='block';
      }
    }
    else {
      counter = 1;
      window.hide();
    }
  }

  function hide() {
    var showprogressbar=document.getElementById('showprogressbar').style;
    var loadtext=document.getElementById('progressbarloadtext').style;
    var game = document.getElementById('my_game').style;

    showprogressbar.display='none';
    loadtext.display='none';
    jQuery('#showprogressbar').remove();
    game.width = '100%';
    game.height = '100%';
    counter = progress_width;
  }

  jQuery(document).ready( function() {
    progress_width = jQuery("#progressbar").width();
    setTimeout('loadgame(progress_width)', <?php echo get_option('fungames_progressbardelay'); ?>);
  });
</script>