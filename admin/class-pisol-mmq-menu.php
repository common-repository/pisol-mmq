<?php

class Pi_Mmq_Menu{

    public $plugin_name;
    public $menu;
    public $version;
    
    function __construct($plugin_name , $version){
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_action( 'admin_menu', array($this,'plugin_menu') );
        add_action($this->plugin_name.'_promotion', array($this,'promotion'));
        add_action( 'admin_enqueue_scripts', array($this,'removeConflictCausingScripts'), 1000 );
    }

    function plugin_menu(){
        
        $this->menu = add_menu_page(
            __( 'Min/Max Quantity'),
            __( 'Min/Max Quantity'),
            'manage_options',
            'pisol-mmq-notification',
            array($this, 'menu_option_page'),
            plugin_dir_url( __FILE__ ).'img/pi.svg',
            6
        );

        add_action("load-".$this->menu, array($this,"bootstrap_style"));
 
    }

    public function bootstrap_style() {

        wp_enqueue_script( $this->plugin_name."_quick_save", plugin_dir_url( __FILE__ ) . 'js/pisol-quick-save.js', array('jquery'), $this->version, 'all' );
        
        wp_enqueue_style( $this->plugin_name."_bootstrap", plugin_dir_url( __FILE__ ) . 'css/bootstrap.css', array(), $this->version, 'all' );
        
        wp_register_script( 'selectWoo', WC()->plugin_url() . '/assets/js/selectWoo/selectWoo.full.min.js', array( 'jquery' ) );
        wp_enqueue_script( 'selectWoo' );
        wp_enqueue_style( 'select2', WC()->plugin_url() . '/assets/css/select2.css');

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pisol-mmq-admin.js', array( 'jquery' ), $this->version, false );

		$min_quantity_enabled = (int)get_option('pi_mmq_min_quantity_enabled',0);
		$max_quantity_enabled = (int)get_option('pi_mmq_max_quantity_enabled',0);

		$data = array('min_quantity_enabled'=>$min_quantity_enabled, 'max_quantity_enabled'=>$max_quantity_enabled);
		
		wp_localize_script( $this->plugin_name, 'pi_mmq_settings', $data );
		
	}

    function menu_option_page(){
        if(function_exists('settings_errors')){
            settings_errors();
        }
        ?>
        <div class="bootstrap-wrapper">
        <div class="pisol-container mt-2">
            <div class="pisol-row">
                    <div class="col-12">
                        <div class='bg-dark'>
                        <div class="pisol-row">
                            <div class="col-12 col-sm-2 py-2">
                                    <a href="https://www.piwebsolution.com/" target="_blank"><img class="img-fluid ml-2" src="<?php echo plugin_dir_url( __FILE__ ); ?>img/pi-web-solution.svg"></a>
                            </div>
                            <div class="col-12 col-sm-10 d-flex text-center small">
                                <?php do_action($this->plugin_name.'_tab'); ?>
                                <!--<a class=" px-3 text-light d-flex align-items-center  border-left border-right  bg-info " href="https://www.piwebsolution.com/documentation-for-live-sales-notifications-for-woocommerce-plugin/">
                                    Documentation
                                </a>-->
                                <a class="btn btn-primary btn-sm" href="<?php echo PI_MMQ_ADD_TO_CART_URL; ?>" target="_blank" id="pi-buy-now-menu">BUY PRO VERSION <?php echo PI_MMQ_PRICE; ?></a>
                            </div>
                            <div class="col-12 d-flex text-center small">
                                <?php do_action($this->plugin_name.'_sub_tab'); ?>
                            </div>
                        </div>
                        </div>
                    </div>
            </div>
            <div class="pisol-row">
                <div class="col-12">
                <div class="bg-light border pl-3 pr-3 pb-3 pt-0">
                    <div class="pisol-row">
                        <div class="col">
                        <?php do_action($this->plugin_name.'_tab_content'); ?>
                        </div>
                        <?php do_action($this->plugin_name.'_promotion'); ?>
                    </div>
                </div>
                </div>
            </div>
        </div>
        </div>
        <?php
    }

    function promotion(){
        ?>
         <div class="col-12 col-sm-12 col-md-4 pt-3">

         

                <div class="bg-dark text-light text-center mb-3">
                    <a href="<?php echo PI_MMQ_ADD_TO_CART_URL; ?>&utm_ref=discount_banner" target="_blank">
                       <?php new pisol_promotion('pisol_mmq_installation_date'); ?>
                    </a>
                </div>

                <div class="pi-shadow">
                <div class="pisol-row justify-content-center">
                    <div class="col-md-8 col-sm-12 ">
                        <div class="p-2">
                            <img class="img-fluid mr-2" src="<?php echo plugin_dir_url( __FILE__ ); ?>img/bg.svg">
                        </div>
                    </div>
                </div>
                <div class="text-center py-2">
                    <a class="btn btn-success btn-sm text-uppercase mb-2 " href="<?php echo PI_MMQ_ADD_TO_CART_URL; ?>&utm_ref=top_link" target="_blank">Buy Now !!</a>
                    <a class="btn btn-sm mb-2 btn-secondary text-uppercase" href="http://websitemaintenanceservice.in/mmq_demo/" target="_blank">Try Demo</a>
                </div>
                <h2 id="pi-banner-tagline" class="text-light">BUY PRO VERSION <?php echo PI_MMQ_PRICE; ?></h2>
                <div class="inside">
                    <ul class="pisol-pro-feature-list">
                        <li class="border-top py-1 font-weight-light h6">Set <strong class="text-primary text-bold">minimum order amount</strong> for complete cart</li>
                        <li class="border-top py-1 font-weight-light h6"><strong class="text-primary text-bold">Exclude unlimited products</strong> from min amount restriction rule</li>
                        <li class="border-top py-1 font-weight-light h6">Place minimum order amount bar using short code <strong class="text-primary text-bold">[pisol_mmq_notification]</strong></li>
                        <li class="border-top py-1 font-weight-light h6">Set Minimum/Maximum quantity on <strong class="text-primary text-bold">per product basis</strong></li>
                        <li class="border-top py-1 font-weight-light h6">Set Minimum/Maximum quantity on <strong class="text-primary text-bold">per variation basis</strong></li>
                        <li class="border-top py-1 font-weight-light h6"><strong class="text-primary text-bold">Disable global Minimum / Maximum Quantity</strong> on particular product</li>
                        <li class="border-top py-1 font-weight-light h6">Custom messages using <strong class="text-primary text-bold">short codes</strong> as per minimum quantity progress</li>
                        <li class="border-top py-1 font-weight-light h6"><strong class="text-primary text-bold">Linear progress bar</strong> showing the progress of minimum quantity for each product</li>
                        <li class="border-top py-1 font-weight-light h6">
                        Control the <strong class="text-primary text-bold">background color</strong> of the linear progress bar
                        </li>
                        <li class="border-top py-1 font-weight-light h6">
                        Change the background color of the message as per the quantity added in the cart with respect to minimum quantity restriction of the product
                        </li>
                        <li class="border-top py-1 font-weight-light h6">Control minimum message <strong class="text-primary text-bold">notification bar position, timing</strong></li>
                        <li class="border-top py-1 font-weight-light h6"><strong class="text-primary text-bold">Change the image</strong> shown in the circular progress of minimum order amount</li>
                        <li class="border-top py-1 font-weight-light h6">Show minimum <strong class="text-primary text-bold">quantity progress</strong> for each product as linear</li>
                        <li class="border-top py-1 font-weight-light h6">Message is updated using ajax on the Archive pages</li>
                        <li class="border-top py-1 font-weight-light h6">Set <strong class="text-primary text-bold">different messages</strong> as per the amount added in the cart with relation to minimum order amount</li>
                        <li class="border-top py-1 font-weight-light h6"><strong class="text-primary text-bold">Hide minimum amount progress</strong> after some time interval</li>
                        <li class="border-top py-1 font-weight-light h6"><strong class="text-primary text-bold">Change the icon</strong> shown inside the circular progress bar showing the progress of minimum cart amount</li>
                        <li class="border-top py-1 font-weight-light h6">Control the pages <strong class="text-primary text-bold">where to show the Minimum amount restriction</strong> notification bar</li>
                        <li class="border-top py-1 font-weight-light h6"><strong class="text-primary text-bold">Show/Hide Min Max quantity message</strong> on product archive page, product page, cart page or checkout page</li>
                        <li class="border-top py-1 font-weight-light h6"><strong class="text-primary text-bold">Set different position for Min Max quantity message</strong> on product archie page and single product page</li>
                        <li class="border-top py-1 font-weight-light h6"><strong class="text-primary text-bold">Don't go inside each product to set Min/Max quantity</strong> set it from the category, so all product will inherit this min/max quantity limit</li>
                        <li class="border-top py-1 font-weight-light h6"><strong class="text-primary text-bold">Set minimum quantity</strong> restriction on the category, so user has to buy that many unit from that category</li>
                        <li class="border-top py-1 font-weight-light h6">Checkout page will redirect to cart page when the Minimum quantity/Min Amount restriction is not fulfilled </li>
                        <li class="border-top py-1 font-weight-light h6">Put Minimum amount restriction on the <strong class="text-primary text-bold">Category level</strong></li>
                        <li class="border-top py-1 font-weight-light h6">Option to apply Minimum amount restriction even on the <strong class="text-primary text-bold">Sub Category products</strong></li>
                        <li class="border-top py-1 font-weight-light h6"><strong class="text-primary text-bold">Exclude product</strong> from Minimum amount restriction set on the Category level</li>
                        <li class="border-top py-1 font-weight-light h6">Force produce to be ordered in a <strong class="text-primary text-bold">multiple of X units</strong></li>
                        <li class="border-top py-1 font-weight-light h6">Force category to be ordered in a <strong class="text-primary text-bold">multiple of X units</strong></li>
                    </ul>
                    <div class="text-center pt-2 pb-3">
                        <a class="btn btn-primary" href="<?php echo PI_MMQ_ADD_TO_CART_URL; ?>" target="_blank">BUY PRO VERSION</a>
                    </div>
                </div>
               </div>

            </div>
        <?php
    }

    function isWeekend() {
        return (date('N', strtotime(date('Y/m/d'))) >= 6);
    }

    function removeConflictCausingScripts(){
        if(isset($_GET['page']) && $_GET['page'] == 'pisol-mmq-notification'){
            /* fixes css conflict with Nasa Core */
            wp_dequeue_style( 'nasa_back_end-css' );
        }
    }

}