<p>
	<label for="<?= $this->get_field_id( 'title' ); ?>"/>
	<input class="widefat"
	       id="<?= $this->get_field_id( 'title' ); ?>"
	       name="<?= $this->get_field_name( 'title' ); ?>"
	       type="text"
	       placeholder="Title"
	       value="<?= esc_attr( $title ); ?>"/>
</p>

<p>
	<label for="<?= $this->get_field_id( 'userId' ); ?>"/>
	<input class="widefat"
	       id="<?= $this->get_field_id( 'userId' ); ?>"
	       name="<?= $this->get_field_name( 'userId' ); ?>"
	       type="text"
	       placeholder="User id. (e.g. ur0840624)"
	       value="<?= esc_attr( $userId ); ?>"/>
</p>
