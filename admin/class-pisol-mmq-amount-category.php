<?php

class Class_Pi_Mmq_Min_Amount_Per_Cat{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'mmq_min_amount_per_category';

    private $tab_name = "Min Amount per Category (PRO)";

    private $setting_key = 'pisol_mmq_min_amount_per_category_rule';
    
    public $default = array(
		"enabled" => "no",
		"min_amount_per_category"=>"",
	);

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;

        $this->settings = array(
           
			array('field'=>'pisol_mmq_min_amount_per_category_rules'),
			array('field'=>'min_max_title', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>'Min amount needed from a specific category', 'type'=>"setting_category"),
            array('field'=>'pi_cat_min_amount_warning_message', 'label'=>'Warning shown on cart page when user has not full filled the min amount requirement of a category ', 'desc'=> '<strong>{min_amount}</strong> = Min amount required<br> <strong>{category}</strong> = Name of the category with link to category page<br>{cart_amount} => amount of product present in cart from this category<br>{amount_needed} => How much amount needs to be added more to allow checkout','type'=>"textarea", 'default'=>'You need to purchase {amount_needed} amount more from category {category} to checkout')

        );
        
        $this->active_tab = (isset($_GET['tab'])) ? sanitize_text_field($_GET['tab']) : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }


		$parent = array('min_amount', 'design','control', 'mmq_min_amount_per_category');
        if( in_array($this->active_tab, $parent)){
            add_action($this->plugin_name.'_sub_tab', array($this,'tab'),2);
        }
       
        
    }

    
    function delete_settings(){
        foreach($this->settings as $setting){
            delete_option( $setting['field'] );
        }
    }

    

    function tab(){
        ?>
        <a class="col px-3 py-2 text-light d-flex align-items-center  border-left border-right  border-top justify-content-center <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo admin_url( 'admin.php?page='.sanitize_text_field($_GET['page']).'&tab='.$this->this_tab ); ?>">
            <?php _e( $this->tab_name); ?> 
        </a>
        <?php
    }

    function tab_content(){
        ?>
        <img src="<?php echo plugin_dir_url( __FILE__ ); ?>img/min-amount-category.png"" class="img-fluid">
        <?php
      
    }

    
    
}


