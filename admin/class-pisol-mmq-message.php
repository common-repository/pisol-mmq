<?php

class Class_Pi_Mmq_Message{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'message';

    private $tab_name = "Quantity Message";

    private $setting_key = 'pi_sn_message_setting';

    public $tab;
    

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;

        $this->settings = array(
            
            array('field'=>'min_max_title', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Minimum & Maximum Quantity Restriction is set', 'pisol-mmq'), 'type'=>"setting_category"),

            array('field'=>'pi_mmq_min_max_0', 'label'=>__('When user just came to the site and have not purchased the product', 'pisol-mmq'), 'type'=>"mmq_textarea", 'default'=>"You have to purchase minimum {min_quantity} units and maximum {max_quantity} units", 'sanitize_callback'=>[__CLASS__,'sanitize_textarea_field'], 'shortcode' => array('{min_quantity}'=>'Minimum Quantity restriction', '{max_quantity}'=>'Maximum Quantity restriction')),

            array('field'=>'pi_mmq_min_max_more_then_0_less_then_min', 'label'=>__('Message shown when added quantity is less then minimum quantity', 'pisol-mmq'), 'type'=>"mmq_textarea", 'default'=>"To buy this product, You have to purchase {away_from_min} unit more", 'sanitize_callback'=>[__CLASS__,'sanitize_textarea_field'], 'shortcode' => array('{away_from_min}'=>'How much more quantity is needed for the minimum requirement')),

            array('field'=>'pi_mmq_min_max_more_then_min_less_then_max', 'label'=>__('Message shown when added quantity is more then minimum quantity but less then maximum', 'pisol-mmq'), 'type'=>"mmq_textarea", 'default'=>"You have purchased {min_quantity} units, required for this product, maximum you can buy {max_quantity} unit", 'sanitize_callback'=>[__CLASS__,'sanitize_textarea_field'], 'shortcode'=> array('{min_quantity}'=>'Minimum Quantity restriction', '{max_quantity}'=>'Maximum Quantity restriction')),

            array('field'=>'pi_mmq_min_max_equal_to_max', 'label'=>__('Message shown when added quantity has reached maximum quantity', 'pisol-mmq'), 'type'=>"mmq_textarea", 'default'=>"You have purchased maximum quantity allowed for this product, that is {max_quantity} units", 'sanitize_callback'=>[__CLASS__,'sanitize_textarea_field'], 'shortcode'=> array('{max_quantity}'=>'Maximum Quantity restriction')),

            array('field'=>'max_title', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__("Only Maximum Quantity Restriction is set", 'pisol-mmq'), 'type'=>"setting_category"),

            array('field'=>'pi_mmq_max_0', 'label'=>__("When user just came to the site and have not purchased the product", 'pisol-mmq'), 'type'=>"mmq_textarea", 'default'=>"You can purchase maximum {max_quantity} unit of this product", 'sanitize_callback'=>[__CLASS__,'sanitize_textarea_field'], 'shortcode'=> array('{max_quantity}'=>'Maximum Quantity restriction')),

            array('field'=>'pi_mmq_max_more_then_0_less_then_max', 'label'=>__("Message shown when added quantity is more then 0 but less then maximum", 'pisol-mmq'), 'type'=>"mmq_textarea", 'default'=>"You can purchase {away_from_max} unit more", 'sanitize_callback'=>[__CLASS__,'sanitize_textarea_field'], 'shortcode'=> array('{away_from_max}'=>'How much more quantity you can buy')),

            array('field'=>'pi_mmq_max_equal_to_max', 'label'=>__("Message shown when added quantity has reached maximum quantity",'pisol-mmq'), 'type'=>"mmq_textarea", 'default'=>"You have purchased maximum quantity allowed for this product, that is {max_quantity} units", 'sanitize_callback'=>[__CLASS__,'sanitize_textarea_field'], 'shortcode'=> array('{max_quantity}'=>'Maximum Quantity restriction')),

            array('field'=>'min_title', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__("Only Minimum Quantity Restriction is set", 'pisol-mmq'), 'type'=>"setting_category"),

            array('field'=>'pi_mmq_min_0', 'label'=>__("When user just came to the site and have not purchased the product", 'pisol-mmq'), 'type'=>"mmq_textarea", 'default'=>"You have to purchase minimum {min_quantity} units to buy this product", 'sanitize_callback'=>[__CLASS__,'sanitize_textarea_field'], 'shortcode'=> array('{min_quantity}'=>'Minimum Quantity restriction')),

            array('field'=>'pi_mmq_min_more_then_0_less_then_min', 'label'=>__("Message shown when added quantity is less then minimum quantity", 'pisol-mmq'), 'type'=>"mmq_textarea", 'default'=>"To buy this product, You have to purchase {away_from_min} unit more", 'sanitize_callback'=>[__CLASS__,'sanitize_textarea_field'], 'shortcode'=> array('{away_from_min}'=>'How much more quantity is needed for the minimum requirement')),

            array('field'=>'pi_mmq_min_more_then_min_equal_to_min', 'label'=>__("Message shown when added quantity is more then minimum quantity or equal to minimum quantity", 'pisol-mmq'), 'type'=>"mmq_textarea", 'default'=>"You have purchased {min_quantity} units, required to buy this product", 'sanitize_callback'=>[__CLASS__,'sanitize_textarea_field'], 'shortcode'=> array('{min_quantity}'=>'Minimum Quantity restriction')),
        );
        
        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }

        $parent = array("",'default','mmq_category', 'message');
        if( in_array($this->active_tab, $parent)){
            add_action($this->plugin_name.'_sub_tab', array($this,'tab'),1);
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
            pisol_class_form_mmq::register_setting( $this->setting_key, $setting);
        }
    
    }

    function tab(){
        ?>
        <a class="col px-3 py-2 text-light d-flex align-items-center  border-left border-right border-top justify-content-center  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo admin_url( 'admin.php?page='.sanitize_text_field($_GET['page']).'&tab='.$this->this_tab ); ?>">
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

