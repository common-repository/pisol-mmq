<?php

class Class_Pi_Mmq_Category{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'mmq_category';

    private $tab_name = 'Category rule (PRO)';

    private $setting_key = 'pisol_mmq_category_rule';
    
    public $default = array(
		'enabled' => 'no',
		'excluded_products'=>array(),
		'min_quantity' => '',
		'max_quantity' => '',
	);

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;

        $this->settings = array(
           
            array('field'=>'pisol_mmq_category_rules')

        );
        
        $this->active_tab = (isset($_GET['tab'])) ? sanitize_text_field($_GET['tab']) : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }

        $parent = array("",'default','mmq_category', 'message');
        if( in_array($this->active_tab, $parent)){
            add_action($this->plugin_name.'_sub_tab', array($this,'tab'),2);
        }
       
        $this->register_settings();

        if(PI_MMQ_DELETE_SETTING ){
			$this->delete_settings();
        }
    }

    
    function delete_settings(){
        foreach($this->settings as $setting){
            delete_option( $setting['field'] );
        }
    }

    function register_settings(){   

        foreach($this->settings as $setting){
            register_setting( $this->setting_key, $setting['field']);
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
        <h2>Set Min/Max Quantity limit on category level</h2>
		<div class="alert alert-primary my-3">
If you don't want to go inside each and every product to set <strong>Min and Max quantity</strong> then you can set it on the category level, that way each of the product in that category will inherit that min and max quantity<br><br>
If you want to assign different Min/Max quantity to specific product you can do that from the product page of that product
</div>
<img src="<?php echo plugin_dir_url( __FILE__ ); ?>img/cat-rule.png" class="img-fluid">
       <?php
    }

    function getCategoryTable(){
        include 'partials/mmq-category-table.php';
    }

    function getCategories($parent = 0, $level = 0){
        $categories = get_terms( 'product_cat', array(
			'hierarchical' => false,
			'hide_empty'   => false,
			'parent'       => $parent,
        ) );
        
        $settings = get_option( 'pisol_mmq_category_rules', array() );
        //print_r($settings);
        include 'partials/mmq-category-row.php';
	}
	
	function storedValue( $saved_settings, $category ){
		if( isset( $saved_settings[$category->term_id] ) ){
			return wp_parse_args( $saved_settings[$category->term_id], $this->default );
		}

		return $this->default;
	}

	function savedProducts( $product_ids, $category_id ){
		$html = "";
		foreach($product_ids as $product_id){
			$html .= $this->savedProduct( $product_id, $category_id );
		}
		return $html;
	}

	function savedProduct( $product_id, $category_id ){
		$html ="";
		if($product_id == ""){
			$html .='';
			return $html ;
		}

		if ( pisol_wc_version_check() ) {
			$product_name = str_replace('&#8211;','>',get_the_title( $product_id  ));

		} else {
			$child_wc  = wc_get_product( $product_id );
			$get_atts  = $child_wc->get_variation_attributes();
			$attr_name = array_values( $get_atts )[0];
			$product_name   = get_the_title() . ' - ' . $attr_name;
		}
		$html .= '<option value="'.$product_id.'" selected="selected">'.$product_name.'</option>';
		return $html;
	}

   
    
}




if(!function_exists('pisol_wc_version_check')){
    function pisol_wc_version_check( $version = '3.0' ) {
        if ( class_exists( 'WooCommerce' ) ) {
            global $woocommerce;
            if ( version_compare( $woocommerce->version, $version, ">=" ) ) {
                return true;
            }
        }
        return false;
    }
}