  stepcarousel.setup( {
    galleryid: 'mygallery',     // id of carousel DIV
    beltclass: 'belt',          // class of inner "belt" DIV containing all the panel DIVs
    panelclass: 'panel',        // class of panel DIVs each holding content
    <?php if ( get_option('fungames_topslider_auto') == 'enable') : ?>
    autostep: {enable:true, moveby:1, pause:3000},
    <?php endif; ?>
    panelbehavior: {speed:500, wraparound:true, persist:true},
    defaultbuttons: {enable: true, moveby: 2, leftnav: ['<?php bloginfo('template_directory'); ?>/images/arrleft.jpg', -10, 68], rightnav: ['<?php bloginfo('template_directory'); ?>/images/arrright.jpg', 14, 68]},
    statusvars: ['statusA', 'statusB', 'statusC'], //register 3 variables that contain current panel (start), current panel (last), and total panels
    contenttype: ['external'] //content setting ['inline'] or ['external', 'path_to_external_file']
  })