jQuery(document).ready(function($){$('#catmenu > ul').before('<button class="botn-menu">Menu</button>');$('#loginbox').prepend('<button class="botn-menub">User/Login</button>');var mouse_mt_menu=!1;$(document.body).on('click','.botn-menu',function(e){e.preventDefault();$('#catmenu > ul').toggleClass('in')});$('body').on({mouseenter:function(){mouse_mt_menu=!0},mouseleave:function(){mouse_mt_menu=!1}},'#catmenu');$("body").mouseup(function(){if(!mouse_mt_menu)$('#catmenu > ul').removeClass('in')});$(document.body).on('click','#catmenu .menu-item-has-children > a',function(e){e.preventDefault();$(this).toggleClass("submdbact");$(this).next().toggleClass("submdb")});var mouse_mt_lgt=!1;$(document.body).on('click','.botn-menub',function(e){e.preventDefault();$('#fgpage #loginbox>*').toggleClass('in')});$('body').on({mouseenter:function(){mouse_mt_lgt=!0},mouseleave:function(){mouse_mt_lgt=!1}},'#fgpage');$("body").mouseup(function(){if(!mouse_mt_lgt)$('#fgpage #loginbox>*').removeClass('in')})});var $jx=jQuery.noConflict();$jx(document).ready(function(){$jx('ul.spy').simpleSpy('4','4000');$jx('ul.spy li').reverseOrder()});(function($jx){$jx.fn.reverseOrder=function(){return this.each(function(){$jx(this).prependTo($jx(this).parent())})};$jx.fn.simpleSpy=function(limit,interval){limit=limit||4;interval=interval||4000;return this.each(function(){var $jxlist=$jx(this),items=[],currentItem=limit,total=0,start=0,startdelay=4000;height=$jxlist.find('> li:first').height();$jxlist.find('> li').each(function(){items.push('<li>'+$jx(this).html()+'</li>')});total=items.length;$jxlist.wrap('<div class="spyWrapper" />').parent().css({height:height*limit});$jxlist.find('> li').filter(':gt('+(limit-1)+')').remove();function spy(){$jxlist.find('> li:last').animate({opacity:0},1000,function(){$jx(this).remove();var $jxinsert=$jx(items[currentItem]).css({height:0,opacity:0,display:'inline'}).prependTo($jxlist);$jxinsert.animate({height:height},1000).animate({opacity:1},1000)});currentItem++;if(currentItem>=total){currentItem=0}
setTimeout(spy,interval)}
if(start<1){setTimeout(spy,startdelay);start++}else{spy()}})}})(jQuery);jQuery(document).ready(function($){var offset=300,scroll_top_duration=700,$back_to_top=$('.back-to-top');$(window).scroll(function(){($(this).scrollTop()>offset)?$back_to_top.addClass('is-visible'):$back_to_top.removeClass('is-visible fade-out')});$back_to_top.on('click',function(event){event.preventDefault();$('body,html').animate({scrollTop:0,},scroll_top_duration)})})