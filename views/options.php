<?php
/*
 * IMDb Widget for WordPress
 *
 *     Copyright (C) 2015 Henrique Dias <hacdias@gmail.com>
 *     Copyright (C) 2015 Luís Soares <lsoares@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
?>
<p>
	<label for="<?= $this->get_field_id( 'title' ); ?>"/>
	<input class="widefat"
		   id="<?= $this->get_field_id( 'title' ); ?>"
		   name="<?= $this->get_field_name( 'title' ); ?>"
		   type="text"
		   placeholder="Title"
		   value="<?= $title ?>" />
</p>

<p>
	<label for="<?= $this->get_field_id( 'userId' ); ?>"/>
	<input class="widefat"
		   id="<?= $this->get_field_id( 'userId' ); ?>"
		   name="<?= $this->get_field_name( 'userId' ); ?>"
		   type="text"
		   placeholder="User id. (e.g. ur0840624)"
		   value="<?= $userId ?>" />
</p>

<div>Show:</div>
<div style="margin-left: 8px; margin-bottom: 8px;">
	<?php foreach ( $this->optionsShow as $option ): ?>
		<input class="checkbox" type="checkbox" 
			   <?php checked( ${$option}, 'on' ); ?>
			   id="<?= $this->get_field_id( $option ) ?>"
			   name="<?= $this->get_field_name( $option ) ?>" />
		<label for="<?= $this->get_field_id( $option ) ?>">
			<?= ucfirst( $option ) ?>
		</label>
		<br />
	<?php endforeach; ?>
</div>