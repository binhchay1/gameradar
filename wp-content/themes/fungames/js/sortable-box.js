jQuery(document).ready(function($){
  "use strict";
  $.fn.vAlign = function() {
    return this.each(function(i){
      var ah = $(this).height();
      var ph = $(this).parent().height();
      var mh = Math.ceil((ph-ah) / 2);
      $(this).css('margin-top', mh);
    });
  };

  $('#sortable-game-box-order select').change(function() {
    var sorturl = $(this).attr("value");
    var loaderheight = $('#sortable-game-box-inner-content').height();
    $('#sortable-game-box-loader').css('height', loaderheight+13);
    $('#sortable-game-box-loader-content').css('height', loaderheight+13);
    $('#sortable-game-box-loader-content img').vAlign();
    $('#sortable-game-box-list').hide();
    $('#sortable-game-box-loader').show();
    $('#sortable-game-box-inner').load(sorturl + ' #sortable-game-box-inner-content', function() {
      $('#sortable-game-box-loader').hide();
      $('#sortable-game-box-list').show();
      if( $().lazyload ) {
        $("#sortable-game-box-list img").lazyload({effect:"fadeIn"});
      }
    });
  });
});