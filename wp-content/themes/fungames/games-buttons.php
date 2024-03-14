<?php if ( fungames_display_buttons() ) : ?>
<div id="game_buttons">
  <?php if ( get_option('fungames_button_fullscreen') == 'enable' ) : ?>
    <?php // Display Fullscreen button ?>
    <a href="<?php echo get_permalink() . 'fullscreen'; ?>" class="fullscreen" title="<?php esc_attr_e('Fullscreen', 'fungames'); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/blank.png" border="0" alt="<?php esc_attr_e('Fullscreen', 'fungames'); ?>" /></a>
  <?php endif; ?>

  <?php if ( get_option('fungames_button_lights') == 'enable' ) : ?>
    <?php // Display Lights-Switch ?>
    <div class="command">
      <a href="#" title="<?php esc_attr_e('Turn lights off / on', 'fungames'); ?>" class="lightSwitch"><img src="<?php echo get_template_directory_uri(); ?>/images/blank.png" border="0" alt="<?php esc_attr_e('Lights Toggle', 'fungames'); ?>" /></a>
    </div>
  <?php endif; ?>

  <?php if ( get_option('fungames_button_favorite') == 'enable' ) : ?>
    <?php // Display Add To Favorite Button ?>
    <?php fungames_favorite(); ?>
  <?php endif; ?>
</div>
<?php endif; ?>