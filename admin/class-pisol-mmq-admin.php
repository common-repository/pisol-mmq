<?php

class Pisol_Mmq_Admin {

	
	private $plugin_name;

	
	private $version;

	
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		if(is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX )){
			new Pi_Mmq_Menu($this->plugin_name, $this->version);
			new Class_Pi_Mmq_Option($this->plugin_name);
			new Class_Pi_Mmq_Min_Amount($this->plugin_name);
			new Pisol_mmq_Design($this->plugin_name);
			new Class_Pi_Mmq_Min_Amount_Per_Cat($this->plugin_name);
			new Class_Pi_Mmq_Control($this->plugin_name);
			new Class_Pi_Mmq_Message($this->plugin_name);
			new Class_Pi_Mmq_Category($this->plugin_name);
			new Class_Pi_Mmq_Metabox();
		}
		add_action('admin_init', array($this,'plugin_redirect'));
	}

	function plugin_redirect(){
		if (get_option('pi_mmq_do_activation_redirect', false)) {
			delete_option('pi_mmq_do_activation_redirect');
			if(!isset($_GET['activate-multi']))
			{
				wp_redirect("admin.php?page=pisol-mmq-notification");
			}
		}
	}

	
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pisol-mmq-admin.css', array(), $this->version, 'all' );

	}

	
	public function enqueue_scripts() {

	}

}
