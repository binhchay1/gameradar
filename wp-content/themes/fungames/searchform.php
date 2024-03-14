<form method="get" class="searchform" action="<?php echo home_url('/'); ?>">
  <div>
    <input type="text" class="search" name="s" onblur="if(this.value=='')this.value='<?php esc_attr_e('To search type and hit enter','fungames'); ?>';" onfocus="if(this.value=='<?php esc_html_e('To search type and hit enter','fungames'); ?>')this.value='';" value="<?php esc_html_e('To search type and hit enter','fungames'); ?>" />
  </div>
</form>