<p>
    <label for="<?= $this->get_field_id('userId'); ?>">
        <input class="widefat"
               id="<?= $this->get_field_id('userId'); ?>"
               name="<?= $this->get_field_name('userId'); ?>"
               type="text"
               placeholder="Your HackerRank username"
               value="<?= esc_attr($userId); ?>"/></label>
</p>

<p>
    <label for="<?= $this->get_field_id('title'); ?>">
        <input class="widefat"
               id="<?= $this->get_field_id('title'); ?>"
               name="<?= $this->get_field_name('title'); ?>"
               type="text"
               placeholder="Title of the widget"
               value="<?= esc_attr($title); ?>"/></label>
</p>


