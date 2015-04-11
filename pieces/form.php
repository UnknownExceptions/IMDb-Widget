<p>
    <label for="<?php echo $this->get_field_id('username'); ?>">
        <input class="widefat"
               id="<?php echo $this->get_field_id('username'); ?>"
               name="<?php echo $this->get_field_name('username'); ?>"
               type="text"
               placeholder="Your HackerRank username"
               value="<?php echo esc_attr($username); ?>"/></label>
</p>

<p>
    <label for="<?php echo $this->get_field_id('title'); ?>">
        <input class="widefat"
               id="<?php echo $this->get_field_id('title'); ?>"
               name="<?php echo $this->get_field_name('title'); ?>"
               type="text"
               placeholder="Title of the widget"
               value="<?php echo esc_attr($title); ?>"/></label>
</p>


