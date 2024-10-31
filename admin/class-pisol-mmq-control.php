<?php

class Class_Pi_Mmq_Control{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'control';

    private $tab_name = "Control Min Amount Bar (PRO)";

    private $setting_key = 'pi_mmq_control_setting';
    
    

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;

        $this->settings = array(
           

            array('field'=>'pi_mmq_show_all', 'label'=>__('Show Free shipping notification on all the pages of a website','pisol-mmq'),'type'=>'switch', 'default'=>1,   'desc'=>__('')),

            array('field'=>'title', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>"Show Minimum amount notification on the selected page", 'type'=>"setting_category"),

            array('field'=>'pi_mmq_show_front_page', 'label'=>__('Show on front page of the site (is_front_page)','pisol-mmq'),'type'=>'switch', 'default'=>0,   'desc'=>__('')),

            array('field'=>'pi_mmq_show_is_product', 'label'=>__('Show on single product page (is_product)','pisol-mmq'),'type'=>'switch', 'default'=>0,   'desc'=>__('')),

            array('field'=>'pi_mmq_show_is_cart', 'label'=>__('Show on cart page (is_cart)','pisol-mmq'),'type'=>'switch', 'default'=>0,   'desc'=>__('')),

            array('field'=>'pi_mmq_show_is_checkout', 'label'=>__('Show on checkout page (is_checkout)','pisol-mmq'),'type'=>'switch', 'default'=>0,   'desc'=>__('')),

            array('field'=>'pi_mmq_show_is_shop', 'label'=>__('Show on shop page (is_shop)','pisol-mmq'),'type'=>'switch', 'default'=>0,   'desc'=>__('')),

            array('field'=>'pi_mmq_show_is_product_category', 'label'=>__('Show on product category page (is_product_category)','pisol-mmq'),'type'=>'switch', 'default'=>0,   'desc'=>__('')),

            array('field'=>'pi_mmq_show_is_product_tag', 'label'=>__('Show on product tag page (is_product_tag)','pisol-mmq'),'type'=>'switch', 'default'=>0,   'desc'=>__('')),
            
        );
        
        $this->active_tab = (isset($_GET['tab'])) ? sanitize_text_field($_GET['tab']) : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }

        $parent = array('min_amount', 'design','control', 'mmq_min_amount_per_category');
        if( in_array($this->active_tab, $parent)){
            add_action($this->plugin_name.'_sub_tab', array($this,'tab'),2);
        }


       
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
        <form method="post" action="options.php"  class="pisol-setting-form">
        <?php settings_fields( $this->setting_key ); ?>
        <div id="pi_control">
        <div class="alert alert-primary mt-2 text-center">
        <h2>This is only available in the Pro version</h2>
        </div>
        <?php
            foreach($this->settings as $setting){
                new pisol_class_form_mmq($setting, $this->setting_key);
            }
        ?>
        <div class="alert alert-primary mt-2">
            You can use filter <strong>pi_mmq_control_filter</strong> to create your custom rule to disable the bar
            <br>e.g: add_filter("pi_mmq_control_filter", function($val) { return true; } ); <br>true will enable the amount bar
            <br>false will disable it
            
        </div>
        </div>
        <input type="submit" class="mt-3 btn btn-primary btn-sm" value="Save Option" />
        </form>
       <?php
    }

    
}

