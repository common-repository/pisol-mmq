<?php
class Pisol_Mmq_Public {


	private $plugin_name;


	private $version;


	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}


	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pisol-mmq-public.css', array(), $this->version, 'all' );

	}


	public function enqueue_scripts() {

	}

}
