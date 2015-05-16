<?php

use IMDbParser\Parser;

/**
 * Widget Class
 *
 * @package IMDb Widget
 * @author Henrique Dias <hacdias@gmail.com>
 * @author Luís Soares <lsoares@gmail.com>
 * @version 1.0.0
 */
class IMDb_Widget extends WP_Widget {

	private $options = array(
		"title",
		"userId"
	);

	public function __construct() {
		parent::__construct(
			'IMDbWidget', 'IMDb',
			array( 'description' => 'A widget to show a small version of your IMDb profile.' )
		);
	}

	public function form( $config ) {
		$config = ! empty( $config ) ? unserialize( $config ) : array();

		foreach ( $this->options as $option ) {
			${$option} = isset( $config[ $option ] ) ? $config[ $option ] : null;
		}

		ob_start( "HTMLCompressor" );
		require 'pieces/options.php';
		ob_end_flush();
	}

	public function update( $new_instance, $old_instance ) {
		return serialize( $new_instance );
	}

	public function widget( $args, $config ) {
		extract( $args, EXTR_SKIP );
		$config = ! empty( $config ) ? unserialize( $config ) : array();

		ob_start( "HTMLCompressor" );

		if ( ! isset( $config['userId'] ) ) {
			echo 'You need to first configure the plugin :)';
		} else {
            $parser = new Parser( $config['userId'] );
            $info   = $parser->getInfo();
			require 'pieces/widget.php';
		}
		ob_end_flush();
	}
}

add_action( 'widgets_init', create_function( '', 'return register_widget("IMDb_Widget");' ) );
