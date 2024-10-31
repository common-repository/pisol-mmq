<?php

class Pisol_mmq_Design{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'design';

    private $tab_name = "Design";

    private $setting_key = 'pi_mmq_design_setting';

    public $tab;
    
    

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;

        

        add_action('woocommerce_init', array($this, 'initialize'));
        
        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }

        $parent = array('min_amount', 'design','control', 'mmq_min_amount_per_category');
        if( in_array($this->active_tab, $parent)){
            add_action($this->plugin_name.'_sub_tab', array($this,'tab'),2);
        }


       
        

        if(PI_MMQ_DELETE_SETTING){
            $this->delete_settings();
        }
    }

    function initialize(){
        
        
     $this->settings = array(
        array('field'=>'title1', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h6', 'label'=>__('Positions of the notification bar','pisol-mmq'), 'type'=>'setting_category'),
        array('field'=>'pi_mmq_minimum_amount_position', 'label'=>__('Bar position',"pisol-mmq"),'type'=>'select', 'default'=> 'top', 'value'=>array('top'=>__('Top','pisol-mmq'), 'bottom'=>__('Bottom','pisol-mmq')),  'desc'=>'' ),

        array('field'=>'title2', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h6', 'label'=>__('Background color of the notification bar','pisol-mmq'), 'type'=>"setting_category"),

        array('field'=>'pi_mmq_minimum_amount_background_color', 'label'=>__('Background color',"pisol-mmq"),'type'=>'color', 'default'=>"#ee6443",   'desc'=>__('Background color of the popup','pisol-mmq') ),

        array('field'=>'title3', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h6', 'label'=>__('Text color','pisol-mmq'), 'type'=>"setting_category"),

        array('field'=>'pi_mmq_minimum_amount_font_color', 'label'=>__('Font color','pisol-mmq'),'type'=>'color', 'default'=>'#ffffff',   'desc'=>__('This font color will be used as general color of text inside message bar','pisol-mmq') ),
        

        array('field'=>'title4', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h6', 'label'=>__('Font Weight','pisol-mmq'), 'type'=>"setting_category"),

        array('field'=>'pi_mmq_minimum_amount_font_weight', 'label'=>__('Normal text font weight',"pisol-mmq"),'type'=>'select','value'=>array('normal'=>__('Normal',"pisol-mmq"),'bold'=>__('Bold',"pisol-mmq"),'lighter'=>__('Lighter',"pisol-mmq")), 'default'=>'normal',   'desc'=>__('This is the font weight used for text in the popup',"pisol-mmq") ),
        

        array('field'=>'title5', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h6', 'label'=>__('Font Size','pisol-mmq'), 'type'=>"setting_category"),
        
        array('field'=>'pi_mmq_minimum_amount_font_size', 'label'=>__('Normal text font size',"pisol-mmq"),'type'=>'number','min'=>0, 'step'=>1, 'default'=>"16",   'desc'=>__('Font size in PX (pixels)',"pisol-mmq")),
        
            
    );
        

        $this->register_settings();
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
       <a class="col px-3 py-2 text-light d-flex align-items-center  border-left border-right  border-top justify-content-center <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo admin_url( 'admin.php?page='.sanitize_text_field($_GET['page']).'&tab='.$this->this_tab ); ?>">
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

    function getShippingZones(){
        $shipping_zones = WC_Shipping_Zones::get_zones( );
        $values = array();
        foreach($shipping_zones as $shipping_zone){
            $shipping_zone_id = $shipping_zone['zone_id'];

            if($this->checkFreeShippingAvailable($shipping_zone_id)){
                $shipping_zone_obj = WC_Shipping_Zones::get_zone($shipping_zone_id);
                $values[$shipping_zone_id] = $shipping_zone_obj->get_zone_name();
            }
        }
        if(count($values) == 0){
            return false;
        }
        return $values;
    }

    function checkFreeShippingAvailable($shipping_zone_id){
        global $wpdb;
        $wfspb_query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}woocommerce_shipping_zone_methods WHERE method_id = %s AND is_enabled = %d AND zone_id = %d", 'free_shipping', 1, $shipping_zone_id );
        $zone_data   = $wpdb->get_results( $wfspb_query, OBJECT );

        if ( empty( $zone_data ) ) {
            return false;
        } else {
            return true;
        }

    }

    
}

