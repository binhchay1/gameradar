    <?php do_action('fungames_before_footer'); ?>

    <div id="footbar">
      <div id="footer" itemscope="itemscope" itemtype="https://schema.org/WPFooter">
        <?php get_sidebar('footer'); ?>
      </div>

      <?php fungames_copyright(); ?>

      <?php do_action('fungames_after_footer'); ?>
    </div>

    <a href="#0" class="back-to-top"><?php esc_html_e("Top", 'fungames'); ?></a>
  </div>

  <?php wp_footer(); ?>
</div>
</body>
</html>