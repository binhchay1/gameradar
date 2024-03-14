jQuery(document).ready(function($){
  "use strict";
  // Lights On / Off
  $("#lightsoff").css("visibility", "visible");
  $("#lightsoff").css("height", $(document).height()).hide();
  $(".lightSwitch").on('click', function() {
    $("#lightsoff").toggle();
    if ( $("#lightsoff").is(":hidden") ) {
      $(this).removeClass("turnedOff");
    }
    else {
      $(this).addClass("turnedOff");
    }

    return false;
  });
});