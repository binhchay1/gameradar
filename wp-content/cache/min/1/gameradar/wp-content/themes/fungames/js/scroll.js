jQuery.noConflict()
var stepcarousel={ajaxloadingmsg:'<div style="margin: 1em; font-weight: bold"><img src="ajaxloadr.gif" style="vertical-align: middle" /> Fetching Content. Please wait...</div>',defaultbuttonsfade:0.4,swipethreshold:[50,300],configholder:{},getCSSValue:function(val){return(val=="auto")?0:parseInt(val)},getremotepanels:function($,config){config.$belt.html(this.ajaxloadingmsg)
$.ajax({url:config.contenttype[1],async:!0,dataType:'html',error:function(ajaxrequest){config.$belt.html('Error fetching content.<br />Server Response: '+ajaxrequest.responseText)},success:function(content){config.$belt.html(content)
config.$panels=config.$gallery.find('.'+config.panelclass)
stepcarousel.alignpanels($,config)}})},getoffset:function(what,offsettype){return(what.offsetParent)?what[offsettype]+this.getoffset(what.offsetParent,offsettype):what[offsettype]},getCookie:function(Name){var re=new RegExp(Name+"=[^;]+","i");if(document.cookie.match(re))
return document.cookie.match(re)[0].split("=")[1]
return null},setCookie:function(name,value){document.cookie=name+"="+value},fadebuttons:function(config,currentpanel){config.$leftnavbutton.fadeTo('fast',currentpanel==0?this.defaultbuttonsfade:1)
config.$rightnavbutton.fadeTo('fast',currentpanel==config.lastvisiblepanel?this.defaultbuttonsfade:1)
if(currentpanel==config.lastvisiblepanel){stepcarousel.stopautostep(config)}},addnavbuttons:function($,config,currentpanel){config.$leftnavbutton=$('<img src="'+config.defaultbuttons.leftnav[0]+'" class="'+config.galleryid+'_navbutton">').css({zIndex:50,position:'absolute',left:config.offsets.left+config.defaultbuttons.leftnav[1]+'px',top:config.offsets.top+config.defaultbuttons.leftnav[2]+'px',cursor:'hand',cursor:'pointer'}).attr({title:'Back '+config.defaultbuttons.moveby+' panels'}).appendTo('body')
config.$rightnavbutton=$('<img src="'+config.defaultbuttons.rightnav[0]+'" class="'+config.galleryid+'_navbutton">').css({zIndex:50,position:'absolute',left:config.offsets.left+config.$gallery.get(0).offsetWidth+config.defaultbuttons.rightnav[1]+'px',top:config.offsets.top+config.defaultbuttons.rightnav[2]+'px',cursor:'hand',cursor:'pointer'}).attr({title:'Forward '+config.defaultbuttons.moveby+' panels'}).appendTo('body')
config.$leftnavbutton.bind('click',function(){stepcarousel.stepBy(config.galleryid,-config.defaultbuttons.moveby)})
config.$rightnavbutton.bind('click',function(){stepcarousel.stepBy(config.galleryid,config.defaultbuttons.moveby)})
if(config.panelbehavior.wraparound==!1){this.fadebuttons(config,currentpanel)}
return config.$leftnavbutton.add(config.$rightnavbutton)},alignpanels:function($,config){var paneloffset=0
config.paneloffsets=[paneloffset]
config.panelwidths=[]
config.$panels.each(function(index){var $currentpanel=$(this)
$currentpanel.css({float:'none',position:'absolute',left:paneloffset+'px'})
$currentpanel.bind('click',function(e){if(mousedist==0)
return config.onpanelclick(e.target)})
paneloffset+=stepcarousel.getCSSValue($currentpanel.css('marginRight'))+parseInt($currentpanel.get(0).offsetWidth||$currentpanel.css('width'))
config.paneloffsets.push(paneloffset)
config.panelwidths.push(paneloffset-config.paneloffsets[config.paneloffsets.length-2])})
config.paneloffsets.pop()
var addpanelwidths=0
var lastpanelindex=config.$panels.length-1
config.lastvisiblepanel=lastpanelindex
for(var i=config.$panels.length-1;i>=0;i--){addpanelwidths+=(i==lastpanelindex?config.panelwidths[lastpanelindex]:config.paneloffsets[i+1]-config.paneloffsets[i])
if(config.gallerywidth>addpanelwidths){config.lastvisiblepanel=i}}
config.$belt.css({width:paneloffset+'px'})
config.currentpanel=(config.panelbehavior.persist)?parseInt(this.getCookie(config.galleryid+"persist")):0
config.currentpanel=(typeof config.currentpanel=="number"&&config.currentpanel<config.$panels.length)?config.currentpanel:0
var endpoint=config.paneloffsets[config.currentpanel]+(config.currentpanel==0?0:config.beltoffset)
config.$belt.css({left:-endpoint+'px'})
if(config.defaultbuttons.enable==!0){var $navbuttons=this.addnavbuttons($,config,config.currentpanel)
$(window).bind("load resize",function(){config.offsets={left:stepcarousel.getoffset(config.$gallery.get(0),"offsetLeft"),top:stepcarousel.getoffset(config.$gallery.get(0),"offsetTop")}
config.$leftnavbutton.css({left:config.offsets.left+config.defaultbuttons.leftnav[1]+'px',top:config.offsets.top+config.defaultbuttons.leftnav[2]+'px'})
config.$rightnavbutton.css({left:config.offsets.left+config.$gallery.get(0).offsetWidth+config.defaultbuttons.rightnav[1]+'px',top:config.offsets.top+config.defaultbuttons.rightnav[2]+'px'})})}
if(config.autostep&&config.autostep.enable){var $carouselparts=config.$gallery.add(typeof $navbuttons!="undefined"?$navbuttons:null)
$carouselparts.bind('click',function(){config.autostep.status="stopped"
stepcarousel.stopautostep(config)})
$carouselparts.hover(function(){stepcarousel.stopautostep(config)
config.autostep.hoverstate="over"},function(){if(config.steptimer&&config.autostep.hoverstate=="over"&&config.autostep.status!="stopped"){config.steptimer=setInterval(function(){stepcarousel.autorotate(config.galleryid)},config.autostep.pause)
config.autostep.hoverstate="out"}})
clearTimeout(config.steptimer)
config.steptimer=setInterval(function(){stepcarousel.autorotate(config.galleryid)},config.autostep.pause)}
this.createpaginate($,config)
var mousemoveevtstr='mousemove.dragstart'+config.galleryid+' touchmove.dragstart'+config.galleryid
var mouseupevtstr='mouseup.dragend'+config.galleryid+' touchend.dragend'+config.galleryid
var mousedist=0,mouseduration=0
config.$gallery.bind('mousedown touchstart',function(e){var e=(e.type.indexOf('touch')!=-1)?e.originalEvent.changedTouches[0]:e
var mousex=e.pageX
mousedist=0
mouseduration=0
var clicktime=new Date().getTime()
$(document).bind(mousemoveevtstr,function(e){var e=(e.type.indexOf('touch')!='-1')?e.originalEvent.changedTouches[0]:e
mousedist=e.pageX-mousex
mouseduration=new Date().getTime()-clicktime
return!1})
$(document).bind(mouseupevtstr,function(e){var e=(e.type.indexOf('touch')!=-1)?e.originalEvent.changedTouches[0]:e
if(Math.abs(mousedist)>stepcarousel.swipethreshold[0]&&mouseduration<stepcarousel.swipethreshold[1]){var dir=(mousedist<0)?'right':'left'
stepcarousel.stepBy(config.galleryid,((dir=='right')?1:-1)*config.defaultbuttons.moveby)
if(config.autostep)
config.autostep.status="stopped"
stepcarousel.stopautostep(config)}
$(document).unbind(mousemoveevtstr)
$(document).unbind(mouseupevtstr)
e.preventDefault()})
e.preventDefault()})
config.$gallery.bind('click',function(e){if(mousedist!=0)
e.preventDefault()})
this.statusreport(config.galleryid)
if(typeof config.oninitcalled=='undefined'){config.oninit()
config.oninitcalled=!0}
config.onslideaction(this)},stepTo:function(galleryid,pindex){var config=stepcarousel.configholder[galleryid]
if(typeof config=="undefined"){return}
stepcarousel.stopautostep(config)
var pindex=Math.min(pindex-1,config.paneloffsets.length-1)
var endpoint=config.paneloffsets[pindex]+(pindex==0?0:config.beltoffset)
if(config.panelbehavior.wraparound==!1&&config.defaultbuttons.enable==!0){this.fadebuttons(config,pindex)}
config.$belt.animate({left:-endpoint+'px'},config.panelbehavior.speed,function(){config.onslideaction(this)})
config.currentpanel=pindex
this.statusreport(galleryid)},stepBy:function(galleryid,steps,isauto){var config=stepcarousel.configholder[galleryid]
if(typeof config=="undefined"){return}
if(!isauto)
stepcarousel.stopautostep(config)
var direction=(steps>0)?'forward':'back'
var pindex=config.currentpanel+steps
if(config.panelbehavior.wraparound==!1){pindex=(direction=="back"&&pindex<=0)?0:(direction=="forward")?Math.min(pindex,config.lastvisiblepanel):pindex
if(config.defaultbuttons.enable==!0){stepcarousel.fadebuttons(config,pindex)}}else{if(pindex>config.lastvisiblepanel&&direction=="forward"){pindex=(config.currentpanel<config.lastvisiblepanel)?config.lastvisiblepanel:0}else if(pindex<0&&direction=="back"){pindex=(config.currentpanel>0)?0:config.lastvisiblepanel}}
var endpoint=config.paneloffsets[pindex]+(pindex==0?0:config.beltoffset)
if(config.panelbehavior.wraparound==!0&&config.panelbehavior.wrapbehavior=="pushpull"&&(pindex==0&&direction=='forward'||config.currentpanel==0&&direction=='back')){config.$belt.animate({left:-config.paneloffsets[config.currentpanel]-(direction=='forward'?100:-30)+'px'},'normal',function(){config.$belt.animate({left:-endpoint+'px'},config.panelbehavior.speed,function(){config.onslideaction(this)})})}else config.$belt.animate({left:-endpoint+'px'},config.panelbehavior.speed,function(){config.onslideaction(this)})
config.currentpanel=pindex
this.statusreport(galleryid)},autorotate:function(galleryid){var config=stepcarousel.configholder[galleryid]
config.$belt.stop(!0,!0)
this.stepBy(galleryid,config.autostep.moveby,!0)},stopautostep:function(config){clearTimeout(config.steptimer)},statusreport:function(galleryid){var config=stepcarousel.configholder[galleryid]
if(config.statusvars.length==3){var startpoint=config.currentpanel
var visiblewidth=0
for(var endpoint=startpoint;endpoint<config.paneloffsets.length;endpoint++){visiblewidth+=config.panelwidths[endpoint]
if(visiblewidth>config.gallerywidth){break}}
startpoint+=1
endpoint=(endpoint+1==startpoint)?startpoint:endpoint
var valuearray=[startpoint,endpoint,config.panelwidths.length]
for(var i=0;i<config.statusvars.length;i++){window[config.statusvars[i]]=valuearray[i]
config.$statusobjs[i].text(valuearray[i]+" ")}}
stepcarousel.selectpaginate(jQuery,galleryid)},createpaginate:function($,config){if(config.$paginatediv.length==1){var $templatebutt=config.$paginatediv.find('*[data-moveby]:eq(0)')
var isimg=$templatebutt.is('img')
var controlpoints=[],controlsrc=[],buttonarray=[],moveby=$templatebutt.attr("data-moveby")||1
var asize=(moveby==1?0:1)+Math.floor((config.lastvisiblepanel+1)/moveby)
var buttonhtml=$('<div>').append($templatebutt.clone()).html()
var buttontag=$(buttonhtml).get(0).tagName
config.navbuttonhtml=buttonhtml
if(isimg)
var srcs=[$templatebutt.attr('src'),$templatebutt.attr('data-over'),$templatebutt.attr('data-select')]
for(var i=0;i<asize;i++){var moveto=Math.min(i*moveby,config.lastvisiblepanel)
buttonarray.push(buttonhtml.replace(/>/,' data-index="'+i+'" data-moveto="'+moveto+'" title="Move to Panel '+(moveto+1)+'">')+'\n')
controlpoints.push(moveto)}
var $controls=config.$paginatediv.html(buttonarray.join('')).find(buttontag)
$controls.css({cursor:'pointer'})
config.$paginatediv.bind('click',function(e){var $target=$(e.target)
if($target.attr('data-moveby')){stepcarousel.stepTo(config.galleryid,parseInt($target.attr('data-moveto'))+1)}})
config.$paginatediv.bind('mouseover mouseout',function(e){var $target=$(e.target)
if(isimg&&$target.attr('data-over')){if(parseInt($target.attr('data-index'))!=config.pageinfo.curselected)
$target.attr('src',srcs[(e.type=="mouseover")?1:0])}})
config.pageinfo={controlpoints:controlpoints,$controls:$controls,srcs:srcs,prevselected:null,curselected:null,isimg:isimg}}},selectpaginate:function($,galleryid){var config=stepcarousel.configholder[galleryid]
if(config.$paginatediv.length==1){var isimg=config.pageinfo.isimg
for(var i=0;i<config.pageinfo.controlpoints.length;i++){if(config.pageinfo.controlpoints[i]<=config.currentpanel)
config.pageinfo.curselected=i}
if(config.pageinfo.prevselected!=null){config.pageinfo.$controls.eq(config.pageinfo.prevselected).removeClass('selected')
if(isimg){config.pageinfo.$controls.eq(config.pageinfo.prevselected).attr('src',config.pageinfo.srcs[0])}}
config.pageinfo.$controls.eq(config.pageinfo.curselected).addClass('selected')
if(isimg){config.pageinfo.$controls.eq(config.pageinfo.curselected).attr('src',config.pageinfo.srcs[2])}
config.pageinfo.prevselected=config.pageinfo.curselected}},loadcontent:function(galleryid,url){var config=stepcarousel.configholder[galleryid]
config.contenttype=['ajax',url]
stepcarousel.stopautostep(config)
stepcarousel.resetsettings($,config,!0)
stepcarousel.init(jQuery,config)},init:function($,config,triggertype){config.gallerywidth=config.$gallery.width()
config.offsets={left:stepcarousel.getoffset(config.$gallery.get(0),"offsetLeft"),top:stepcarousel.getoffset(config.$gallery.get(0),"offsetTop")}
config.$belt=config.$gallery.find('.'+config.beltclass)
config.$panels=config.$gallery.find('.'+config.panelclass)
config.panelbehavior.wrapbehavior=config.panelbehavior.wrapbehavior||"pushpull"
config.$paginatediv=$('#'+config.galleryid+'-paginate')
if(config.autostep)
config.autostep.pause+=config.panelbehavior.speed
config.onpanelclick=(typeof config.onpanelclick=="undefined")?function(target){}:config.onpanelclick
config.onslideaction=(typeof config.onslide=="undefined")?function(){}:function(beltobj){$(beltobj).stop();config.onslide()}
config.oninit=(typeof config.oninit=="undefined")?function(){}:config.oninit
config.beltoffset=stepcarousel.getCSSValue(config.$belt.css('marginLeft'))
config.statusvars=config.statusvars||[]
config.$statusobjs=[$('#'+config.statusvars[0]),$('#'+config.statusvars[1]),$('#'+config.statusvars[2])]
config.currentpanel=0
stepcarousel.configholder[config.galleryid]=config
if(config.contenttype[0]=="ajax"&&typeof config.contenttype[1]!="undefined"&&triggertype!='windowresize')
stepcarousel.getremotepanels($,config)
else stepcarousel.alignpanels($,config)},resetsettings:function($,config,emptybelt){stepcarousel.windowisresized=!0
config.$gallery.unbind()
config.$belt.stop()
if(emptybelt){config.$panels.remove()}
config.$panels.unbind()
if(config.defaultbuttons.enable){config.$leftnavbutton.remove()
config.$rightnavbutton.remove()}
if(config.$paginatediv.length==1){config.$paginatediv.unbind()
config.$paginatediv.empty()
config.$paginatediv.html(config.navbuttonhtml)}
if(config.autostep)
config.autostep.status=null
if(config.panelbehavior.persist){stepcarousel.setCookie(config.galleryid+"persist",0)}},setup:function(config){document.write('<style type="text/css">\n#'+config.galleryid+'{overflow: hidden;}\n</style>')
jQuery(document).ready(function($){config.$gallery=$('#'+config.galleryid)
stepcarousel.init($,config)})
jQuery(window).bind('resize',function(){clearTimeout(config.windowresizetimer)
config.windowresizetimer=setTimeout(function(){stepcarousel.stopautostep(config)
stepcarousel.resetsettings(jQuery,config)
if(config.panelbehavior.persist)
stepcarousel.setCookie(config.galleryid+"persist",0)
stepcarousel.init(jQuery,config,'windowresize')},200)})
jQuery(window).bind('unload',function(){stepcarousel.resetsettings($,config,!0)
if(config.panelbehavior.persist)
stepcarousel.setCookie(config.galleryid+"persist",config.currentpanel)
jQuery.each(config,function(ai,oi){oi=null})
config=null})}}