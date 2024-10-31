<?php

class Class_Pi_Mmq_Min_Amount{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'min_amount';

    private $tab_name = "Minimum Order Amount";

    private $setting_key = 'pi_sn_min_amount_setting';

    public $tab;

    public $excluded_product = array();
    

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;
        $this->excluded_product = $this->savedExcludedProducts();   
        $this->settings = array(
            
            array('field'=>'title', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>"Minimum amount needed to checkout", 'type'=>"setting_category"),

            array('field'=>'pi_mmq_min_amount_enabled', 'label'=>__('Enable minimum amount'),'type'=>'switch', 'default'=>0,   'desc'=>__('This enable minimum amount restriction on checkout')),

            array('field'=>'pi_mmq_min_amount', 'label'=>__('Minimum amount'),'type'=>'number',  'min'=>1,    'desc'=>__('Minimum amount that should be there to checkout')),

            array('field'=>'pi_mmq_min_exclude_product', 'label'=>__('Exclude this product from global level min amount restriction', 'pisol-mmq'),'type'=>'multiselect',   'desc'=>__('In Pro version you can add more then TWO excluded product', 'pisol-mmq'), 'value'=> $this->excluded_product, 'default'=> $this->excluded_product),

            array('field'=>'pi_mmq_min_amount_bar_display', 'label'=>__('Show minimum amount notification on top on every page', 'pisol-mmq'),'type'=>'switch', 'default'=>1,   'desc'=>__('You can disable the notification bar on top using this setting so that user will see the warning message of minimum amount only on the cart page', 'pisol-mmq')),

            array('field'=>'pi_mmq_enable_circular_progress', 'label'=>__('Show a circular progress of amount purchased', 'pisol-mmq'),'type'=>'switch', 'default'=>1,   'desc'=>__('This will show a circular progress for the amount purchased ', 'pisol-mmq')),

            array('field'=>'pi_mmq_min_exclude_category1', 'label'=>__('Exclude this category product from global level min amount restriction', 'pisol-mmq'),'type'=>'select',   'desc'=>__('exclude from global min amount restriction', 'pisol-mmq'), 'value'=>array('select'=>'Select categories'), 'default'=> 'select', 'pro'=>true),

            array('field'=>'pi_mmq_count_in_min_amount_total_global1', 'label'=>__('Count excluded product total in min amount restriction', 'pisol-mmq'),'type'=>'switch', 'default'=>1,   'desc'=>__('When enabled the excluded product total will be counted for min amount restriction', 'pisol-mmq'), 'pro'=>true),


            array('field'=>'pisol_mmq_force_checkout_page_redirect_amount', 'label'=>__('Redirect checkout page to cart page when min amount limit not reached'),'type'=>'switch', 'default'=>1,   'desc'=>__('When enabled this will redirect checkout page to cart page when the amount limits are not fulfilled'), 'pro'=>true),

            array('field'=>'title', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>"Minimum amount messages", 'type'=>"setting_category"),

            array('field'=>'pi_mmq_min_amount_0', 'label'=>"When user just came to the site and have not purchased the product", 'type'=>"mmq_textarea", 'default'=>"Minimum order amount is {min_amount} you need to buy {away_from_min_amount} more to checkout", 'sanitize_callback'=>[__CLASS__,'sanitize_textarea_field'], 'shortcode'=> array('{min_amount}'=>'Minimum amount needed', '{away_from_min_amount}'=>'Amount needed to reach min amount')),

            array('field'=>'pi_mmq_min_amount_more_then_0_less_then_min', 'label'=>"Message shown when cart total is more then 0, less then minimum amount", 'type'=>"mmq_textarea", 'default'=>"Minimum order amount is {min_amount}. You're  {away_from_min_amount} short.", 'pro'=>true, 'shortcode'=> array('{away_from_min_amount}'=>'How much more quantity is needed for the minimum requirement')),

            array('field'=>'pi_mmq_min_amount_more_then_min_equal_to_min', 'label'=>"Message shown when added amount is more then minimum amount or equal to minimum amount", 'type'=>"mmq_textarea", 'default'=>"You just crossed minimum purchase amount limit of {min_amount}, now you can checkout", 'sanitize_callback'=>[__CLASS__,'sanitize_textarea_field'], 'shortcode'=> array('{min_amount}'=>'Minimum amount needed')),

            array('field'=>'title', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>"Notification display setting", 'type'=>"setting_category"),

            array('field'=>'pi_mmq_min_amount_close_button', 'label'=>__('Show close option'),'type'=>'switch', 'default'=>1,   'desc'=>__('It will show the close button on the bar'), 'pro'=>true),
            array('field'=>'pi_mmq_min_amount_persistent_bar', 'label'=>__('Show the Free shipping bar continues'),'type'=>'switch', 'default'=>0,   'desc'=>__('Free shipping notification bar will be shown continues will not get hidden after some time'), 'pro'=>true),
            array('field'=>'pi_mmq_min_amount_how_long_to_show', 'label'=>__('How long to show (unit in seconds)'),'type'=>'number', 'default'=>6,   'desc'=>__('Once page ha loaded, then after this many seconds notification will be shown, This is only applicable when Bar is not set to show continues'), 'min'=>1, 'step'=>1, 'pro'=>true),
            array('field'=>'pi_mmq_progress_image', 'label'=>__('Image shown in the circular progress bar'), 'type'=>'image', 'pro'=>true),
            array('field'=>'pi_mmq_enable_linear_progress', 'label'=>__('Show a linear progress bar of amount purchased'),'type'=>'switch', 'default'=>1,   'desc'=>__('This will show a progress bar behind the amount purchased '), 'pro'=>true),
            
        );
        
        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }


        add_action($this->plugin_name.'_tab', array($this,'tab'),3);

       
        $this->register_settings();

        if(PI_MMQ_DELETE_SETTING){
            $this->delete_settings();
        }
    }

    function savedExcludedProducts(){
        $exc = get_option('pi_mmq_min_exclude_product', array());
        $exc_products = array();
        if(!is_array($exc) || empty($exc)) return array();
        foreach((array)$exc as $product){
            $exc_products[$product] = get_the_title($product);
        }
        return $exc_products;
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
        $parent = array('min_amount', 'design','control','mmq_min_amount_per_category');
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

    static function sanitize_textarea_field($input){
        return wp_kses_post($input);
    }
}

