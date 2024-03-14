/* Responsive Menu */
jQuery(document).ready(function($){
  $('#catmenu > ul').before('<button class="botn-menu">Menu</button>');
  $('#loginbox').prepend('<button class="botn-menub">User/Login</button>');

  // menu navbar
  var mouse_mt_menu = false;

  // open
  $(document.body).on('click', '.botn-menu' ,function(e){
    e.preventDefault();
    $('#catmenu > ul').toggleClass('in');
  });

  $('body').on({
    mouseenter: function () {
      mouse_mt_menu=true;
    },
    mouseleave: function () {
      mouse_mt_menu=false;
    }
  }, '#catmenu');

  $("body").mouseup(function(){
    if(! mouse_mt_menu) $('#catmenu > ul').removeClass('in');
  });

  // submenu
  $(document.body).on('click', '#catmenu .menu-item-has-children > a' ,function(e){
    e.preventDefault();
    $( this ).toggleClass( "submdbact" );
    $( this ).next().toggleClass( "submdb" );
  });

  // menu lgt
  var mouse_mt_lgt = false;

  // open
  $(document.body).on('click', '.botn-menub' ,function(e){
    e.preventDefault();
    $('#fgpage #loginbox>*').toggleClass('in');
  });

  $('body').on({
    mouseenter: function () {
      mouse_mt_lgt=true;
    },
    mouseleave: function () {
      mouse_mt_lgt=false;
    }
  }, '#fgpage');

  $("body").mouseup(function(){
    if(! mouse_mt_lgt) $('#fgpage #loginbox>*').removeClass('in');
  });
});

/* featured scoller widget */
var $jx = jQuery.noConflict();
$jx(document).ready(function(){
  $jx('ul.spy').simpleSpy('4','4000');
  $jx('ul.spy li').reverseOrder();
});

(function ($jx) {
  $jx.fn.reverseOrder = function() {
    return this.each(function() {
      $jx(this).prependTo( $jx(this).parent() );
    });
  };


  $jx.fn.simpleSpy = function (limit, interval) {
    limit = limit || 4;
    interval = interval || 4000;

    return this.each(function () {
        // 1. setup
            // capture a cache of all the Interesting title s
            // chomp the list down to limit li elements
        var $jxlist = $jx(this),
            items = [], // uninitialised
            currentItem = limit,
            total = 0, // initialise later on
            start = 0,//when the effect first starts
            startdelay = 4000;
            height = $jxlist.find('> li:first').height();

        // capture the cache
        $jxlist.find('> li').each(function () {
            items.push('<li>' + $jx(this).html() + '</li>');
        });

        total = items.length;

        $jxlist.wrap('<div class="spyWrapper" />').parent().css({ height : height * limit });

        $jxlist.find('> li').filter(':gt(' + (limit - 1) + ')').remove();

        // 2. effect
        function spy() {
            // fade the LAST item out
            $jxlist.find('> li:last').animate({ opacity : 0}, 1000, function () {
                $jx(this).remove();

            // insert a new item with opacity and height of zero
            var $jxinsert = $jx(items[currentItem]).css({
                height : 0,
                opacity : 0,
                display : 'inline'
            }).prependTo($jxlist);

                // increase the height of the NEW first item
                 $jxinsert.animate({ height : height }, 1000).animate({ opacity : 1 }, 1000);

                // AND at the same time - decrease the height of the LAST item
                // $jx(this).animate({ height : 0 }, 1000, function () {
                    // finally fade the first item in (and we can remove the last)
                // });
            });

            currentItem++;
            if (currentItem >= total) {
              currentItem = 0;
            }

          setTimeout(spy, interval)
        }

        if (start < 1) {
          setTimeout(spy,startdelay);
          start++;
        } else {
          spy();
        }
    });
  };
})(jQuery);

/* Back to Top */
jQuery(document).ready(function($){
  // browser window scroll (in pixels) after which the "back to top" link is shown
  var offset = 300,
  //duration of the top scrolling animation (in ms)
  scroll_top_duration = 700,
  //grab the "back to top" link
  $back_to_top = $('.back-to-top');

  //hide or show the "back to top" link
  $(window).scroll(function(){
    ( $(this).scrollTop() > offset ) ? $back_to_top.addClass('is-visible') : $back_to_top.removeClass('is-visible fade-out');
  });

  //smooth scroll to top
  $back_to_top.on('click', function(event){
    event.preventDefault();
    $('body,html').animate({
      scrollTop: 0 ,
      }, scroll_top_duration
    );
  });
});