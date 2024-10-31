<?php

class Class_Pi_Mmq_Option{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'default';

    private $tab_name = "Min/Max Quantity";

    private $setting_key = 'pi_sn_basic_setting';

    public $tab;
    

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;

        $this->settings = array(
            
            array('field'=>'title', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Minimum / Maximum Quantity (Per product)','pisol-mmq'), 'type'=>'setting_category'),

            array('field'=>'pi_mmq_min_quantity_enabled', 'label'=>__('Enable global minimum quantity per product',"pisol-mmq"),'type'=>'switch', 'default'=>0,   'desc'=>__('Enable this if you like to set a global level minimum quantity',"pisol-mmq")),

            array('field'=>'pi_mmq_min_quantity', 'label'=>__('Minimum product quantity',"pisol-mmq"),'type'=>'number',  'min'=>1,    'desc'=>__('Set a global minimum quantity for product, this can be changed on product level by setting a minimum quantity over there',"pisol-mmq")),

            array('field'=>'pi_mmq_max_quantity_enabled', 'label'=>__('Enable global maximum quantity per product',"pisol-mmq"),'type'=>'switch', 'default'=>0,   'desc'=>__('Enable this if you like to set a global level minimum quantity',"pisol-mmq")),

            array('field'=>'pi_mmq_max_quantity', 'label'=>__('Maximum product quantity',"pisol-mmq"),'type'=>'number', 'min'=>1,'desc'=>__('Set a global maximum quantity for product,  this can be changed on product level by setting a maximum quantity over there',"pisol-mmq")),

            array('field'=>'pisol_mmq_force_checkout_page_redirect_qty', 'label'=>__('Redirect checkout page to cart page when quantity limits not reached'),'type'=>'switch', 'default'=>1,   'desc'=>__('When enabled this will redirect checkout page to cart page when the quantity limits are not fulfilled'), 'pro'=>true),

            array('field'=>'title', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Minimum / Maximum quantity message box color','pisol-mmq'), 'type'=>'setting_category'),

            array('field'=>'pi_mmq_normal_bg_color', 'label'=>__('Background color of message box',"pisol-mmq"),'type'=>'color', 'default'=>"#CCCCCC",   'desc'=>__('This color will be used for the message box to show minimum requirement','pisol-mmq') , 'pro'=>true),

            array('field'=>'pi_mmq_normal_text_color', 'label'=>__('Text color of message box','pisol-mmq'),'type'=>'color', 'default'=>'#ffffff',   'desc'=>__('This color will be used for the message box to show minimum requirement','pisol-mmq') , 'pro'=>true),

            array('field'=>'title', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Minimum / Maximum quantity message box color when the quantity not satisfied','pisol-mmq'), 'type'=>'setting_category'),

            array('field'=>'pi_mmq_error_bg_color', 'label'=>__('Error background color of message box',"pisol-mmq"),'type'=>'color', 'default'=>"#FF0000",   'desc'=>__('This color will be used when user has not purchased the required amount of product',"pisol-mmq") , 'pro'=>true),

            array('field'=>'pi_mmq_error_text_color', 'label'=>__('Error text color of message box',"pisol-mmq"),'type'=>'color', 'default'=>"#ffffff",   'desc'=>__('This color will be used when user has not purchased the required amount of product',"pisol-mmq") , 'pro'=>true),

            array('field'=>'title', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Minimum quantity progress bar','pisol-mmq'), 'type'=>'setting_category'),

            array('field'=>'pi_mmq_min_quantity_liner_progress_enabled', 'label'=>__('Show linear progress as color shade'),'type'=>'switch', 'default'=>1,   'desc'=>__('Show linear progress in color for minimum quantity, for each product',"pisol-mmq"), 'pro'=>true),

            array('field'=>'pi_mmq_min_quantity_liner_progress_color', 'label'=>__('Progress line background color',"pisol-mmq"),'type'=>'color', 'default'=>"#ff0000",   'desc'=>__('Progress line background color',"pisol-mmq"), 'pro'=>true ),

            array('field'=>'title', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Minimum / Maximum quantity message position and visibility','pisol-mmq'), 'type'=>'setting_category'),

            array('field'=>'pi_mmq_min_quantity_msg_on_archive_page', 'label'=>__('Show Min/Max message on shop / category page',"pisol-mmq"),'type'=>'switch', 'default'=>1, 'desc'=>__('Using this you can disable or enable the min max quantity message of product archive pages',"pisol-mmq"), 'pro'=>true ),
            
            array('field'=>'pi_mmq_product_loop_position', 'label'=>__('Min/Max message position on product archive page',"pisol-mmq"),'type'=>'select', 'default'=> 'woocommerce_after_shop_loop_item_title', 'value'=>array('woocommerce_after_shop_loop_item_title'=>__('After product title',"pisol-mmq"), 'woocommerce_after_shop_loop_item'=>__('After price',"pisol-mmq"), 'woocommerce_shop_loop_item_title'=>__('Before product title',"pisol-mmq")),  'desc'=>__('This position are based on the WooCommerce default position, your template may implement this position differently as you have to try out this position to see which position is mapped to which location',"pisol-mmq"), 'pro'=>true  ),

            array('field'=>'pi_mmq_min_quantity_msg_on_product_page', 'label'=>__('Show Min/Max message on single product page',"pisol-mmq"),'type'=>'switch', 'default'=>1, 'desc'=>__('Using this you can disable or enable the min max quantity message on single product page',"pisol-mmq"), 'pro'=>true ),

            array('field'=>'pi_mmq_product_page_position', 'label'=>__('Min/Max message position on single product page',"pisol-mmq"),'type'=>'select', 'default'=> 'wp', 'value'=>array('wp'=>__('Woocommerce Alert box',"pisol-mmq"), 'woocommerce_after_add_to_cart_button'=>__('After add to cart button',"pisol-mmq"), 'woocommerce_before_add_to_cart_button'=>__('Before add to cart button',"pisol-mmq"), 'woocommerce_before_add_to_cart_form' =>__('Just below short description',"pisol-mmq")),  'desc'=>__('This position are based on the WooCommerce default position, your template may implement this position differently as you have to try out this position to see which position is mapped to which location',"pisol-mmq"), 'pro'=>true  ),

            array('field'=>'pi_mmq_min_quantity_msg_on_cart_page', 'label'=>__('Show Min/Max message on cart page',"pisol-mmq"),'type'=>'switch', 'default'=>1, 'desc'=>__('Using this you can disable or enable the min max quantity message of cart pages',"pisol-mmq"), 'pro'=>true ),

            array('field'=>'pi_mmq_min_quantity_msg_on_checkout_page', 'label'=>__('Show Min/Max message on checkout page',"pisol-mmq"),'type'=>'switch', 'default'=>1, 'desc'=>__('Using this you can disable or enable the min max quantity message of checkout pages',"pisol-mmq"), 'pro'=>true ),
        );
        
        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }


        add_action($this->plugin_name.'_tab', array($this,'tab'),1);
        

       
        $this->register_settings();

        if(PI_MMQ_DELETE_SETTING){
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
            pisol_class_form_mmq::register_setting( $this->setting_key, $setting);
        }
    
    }

    function tab(){
        $parent = array("",'default','mmq_category', 'message');
        ?>
        <a class=" px-3 text-light d-flex align-items-center  border-left border-right  <?php echo (in_array($this->active_tab, $parent) ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo admin_url( 'admin.php?page='.sanitize_text_field($_GET['page']).'&tab='.$this->this_tab ); ?>">
            <?php _e( $this->tab_name); ?> 
        </a>
        <?php
    }

    function tab_content(){
        
       ?>
        <form method="post" action="options.php"  class="pisol-setting-form">
        <?php settings_fields( $this->setting_key ); ?>
        <?php
            foreach($this->settings as $setting){
                new pisol_class_form_mmq($setting, $this->setting_key);
            }
        ?>
        <input type="submit" class="mt-3 btn btn-primary btn-sm" value="Save Option" />
        </form>
       <?php
    }

    
}

