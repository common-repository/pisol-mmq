<?php

class Class_Pi_Mmq_Metabox{

    function __construct(){
        add_action( 'woocommerce_product_data_tabs', array($this,'productTab') );
        add_action( 'woocommerce_product_data_panels', array($this,'productTabContent'));
        add_action( 'woocommerce_process_product_meta', array($this,'productTabContentSave') );
    }

    function productTab($tabs){
        $tabs['pisol_mmq'] = array(
            'label'    => 'Min/Max Quantity',
            'target'   => 'pisol_min_max_quantity',
            'priority' => 21,
        );
        return $tabs;
    }

    function productTabContent(){
        echo '<div id="pisol_min_max_quantity" class="panel woocommerce_options_panel hidden">';
        woocommerce_wp_checkbox( array(
            'label' => __("Disable Global Min/Max Quantity"), 
            'id' => 'pisol_mmq_disable_global_min_max', 
            'name' => 'pisol_mmq_disable_global_min_max', 
            'description' => __("Using this you can disable global min max quantity to apply on this product")
          ) );
        echo '<div id="pisol-product-overwrite" class="pisol-pro-message">';
        woocommerce_wp_checkbox( array(
            'label' => __("Enable Minimum Quantity"), 
            'id' => 'pisol_mmq_enable_product_min', 
            'name' => 'pisol_mmq_enable_product_min', 
            'description' => __("Enable minimum quantity for this product")
          ) );
        woocommerce_wp_text_input( array(
            'label' => __("Minimum Quantity"), 
            'id' => 'pisol_mmq_product_min', 
            'name' => 'pisol_mmq_product_min', 
            'description' => __("Set minimum quantity for this product")
          ) );

        woocommerce_wp_checkbox( array(
            'label' => __("Enable Maximum Quantity"), 
            'id' => 'pisol_mmq_enable_product_max', 
            'name' => 'pisol_mmq_enable_product_max', 
            'description' => __("Enable Maximum quantity for this product")
          ) );
        woocommerce_wp_text_input( array(
            'label' => __("Maximum Quantity"), 
            'id' => 'pisol_mmq_product_max', 
            'name' => 'pisol_mmq_product_max', 
            'description' => __("Set Maximum quantity for this product")
          ) );
        echo '<div class="pisol-pro-message-content">';
        echo '<span>Buy PRO Version</span>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    function productTabContentSave($post_id){
        // Checkbox
	    $global_min_max_disabled = isset( $_POST['pisol_mmq_disable_global_min_max'] ) ? 'yes' : 'no';
	    update_post_meta( $post_id, 'pisol_mmq_disable_global_min_max', $global_min_max_disabled );
		
    }
}

