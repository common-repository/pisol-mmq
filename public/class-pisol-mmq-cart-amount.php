<?php

class Pisol_Mmq_Cart_Amount{

    function __construct(){
        $cart_total_enabled = get_option('pi_mmq_min_amount_enabled',0);

       if($cart_total_enabled == 1){
        add_action('woocommerce_check_cart_items', array($this,'validateCartTotal'));

        add_action( 'wp_ajax_get_cart_mmq', array( $this, 'ajaxResponse' ) );
        add_action( 'wp_ajax_nopriv_get_cart_mmq', array( $this, 'ajaxResponse' ) );
        add_action( 'wp_footer', array( $this, 'loadBarOnPage' ), 90 );
        add_action( 'wp_enqueue_scripts', array( $this,  'inlineCss') );
        add_action( 'wp_enqueue_scripts', array( $this,  'inlineJs') );
       }
    }

    function validateCartTotal(){

		if(!self::cartAmountValid()){
            $message =  self::minAmountMessage();
            wc_add_notice( $message, 'error' );
        }
                
    }

    static function minAmountMessage(){
        $total = self::getCartTotalAmount();
        $minimum_cart_total = get_option('pi_mmq_min_amount',false);

        if(empty($minimum_cart_total)) return false;

        $away = $minimum_cart_total - $total;

        $key = array('{min_amount}','{away_from_min_amount}');
        $replace = array(wc_price($minimum_cart_total), wc_price($away));

        $message_array = array(
            'state_0'=> get_option('pi_mmq_min_amount_0',"Minimum order amount is {min_amount}. You're  {away_from_min_amount} short."),
            'state_1'=>get_option('pi_mmq_min_amount_0',"Minimum order amount is {min_amount}. You're  {away_from_min_amount} short."),
            'state_2'=>get_option('pi_mmq_min_amount_more_then_min_equal_to_min',"You just crossed minimum purchase amount limit of {min_amount}, now you can checkout"),
        );
        $cart_state = self::cartState();
        if(isset($message_array[$cart_state])){
            $message = str_replace($key, $replace, $message_array[$cart_state]);
            return wp_kses_post($message);
        }
        return false;

    }

    /**
     * cart total = 0 state 0
     * cart 0 < total < min state 1
     * cart min <= total state 2
     */
    static function cartState(){
        $total = self::getCartTotalAmount();
        $minimum_cart_total = get_option('pi_mmq_min_amount',false);
        if($minimum_cart_total != false && $minimum_cart_total != ""){
            if($total == 0){
                return 'state_0';
            }elseif($total > 0 && $total < $minimum_cart_total){
                return 'state_1';
            }elseif($total >= $minimum_cart_total){
                return 'state_2';
            }
        }else{
            return false;
        }

    }

    /**
     * Check if the cart amount is valid 
     * that is more then min amount
     */
    static function cartAmountValid(){
        $cart_total_enabled = get_option('pi_mmq_min_amount_enabled',0);
        if(empty($cart_total_enabled)) return true;

        if(self::cartHasOnlyExcludedProduct()) return true;

        $total = self::getCartTotalAmount();

        $minimum_cart_total = get_option('pi_mmq_min_amount',false);
        if($minimum_cart_total != false && $minimum_cart_total != ""){
            if( $total < $minimum_cart_total  ) {
                return false;
            }
        }
        return true;
    }

    static function cartHasOnlyExcludedProduct(){
        $excluded_prod = get_option('pi_mmq_min_exclude_product', array());

        if(is_array($excluded_prod) && count($excluded_prod) > 2){
            $excluded_prod = array_slice($excluded_prod, 0, 2);
        }

        if(!is_array($excluded_prod) || empty($excluded_prod)) return false;

        $cart_has_only_excluded_products = true;

        $products_in_cart = isset(WC()->cart) ? WC()->cart->cart_contents : null;
        if(is_array($products_in_cart)){
            foreach($products_in_cart as $key => $product_in_cart){
                if(!in_array($product_in_cart['product_id'], $excluded_prod)){
                    $cart_has_only_excluded_products = false;
                }
            }
        }

        return $cart_has_only_excluded_products;
    }

    /**
     * Cart Total amount
     */
    static function getCartTotalAmount(){
        if(function_exists('WC') && isset(WC()->cart)){
            $total = WC()->cart->get_displayed_subtotal();
            return $total;
        }
        return 0;
    }

    static function percent(){
        $total = self::getCartTotalAmount();
        $percent = 0;
        $min_amount = get_option('pi_mmq_min_amount',false);
        if($min_amount != false && $min_amount != ""){
            $percent = ($total / $min_amount) * 100 ;
        }
        return $percent;
    }
    /**
     * Control on which page to show message
     */
    static function whereToShowCartQuantityWarning(){
        return true;
    }
    

    function ajaxResponse(){
        $min_amount = get_option('pi_mmq_min_amount',false);
        $total = self::getCartTotalAmount();
        $json = array('message_bar'=> self::minAmountMessage(), 'min_amount'=> $min_amount, 'total'=> $total, 'percent'=>self::percent());
        echo json_encode($json);
        die;
    }

     /**
     * direct loading the message in the html on initial load
     * reduces load on server, we don't have to do ajax call until user buy some thing or update cart
     */
    public function loadBarOnPage() {
		if ( ! is_admin() ) {
            $enabled = true;
            //$message = self::minAmountMessage();
			if ( self::whereToShowCartQuantityWarning()) {
				echo self::messageTemplate('');
			}

		}
    }

    static function messageTemplate($message){
        $show_close_button = 1;
        $message_template = "<div class='pisol-mmq-bar-container'>
                <div class='pisol-mmq-bar-message'>{$message}</div>
                ".($show_close_button == 1 ? "<a href='javascript:void(0);' class='pisol-mmq-close'>&times;</a>" : "")."
              </div>
        ";

        $pi_mmq_enable_circular_progress = get_option('pi_mmq_enable_circular_progress', 1);

        if( !empty($pi_mmq_enable_circular_progress) ){
            $message_template .= "<div id='pi-mmq-progress-circle' style='background-image:url(".plugin_dir_url( __FILE__ )."img/minimum-order.svg);'></div>";
        }
        
        return $message_template;
    }

    function inlineCss(){
        $pi_mmq_minimum_amount_position = esc_html(get_option("pi_mmq_minimum_amount_position",'top'));

        $pi_mmq_minimum_amount_background_color = esc_html(get_option("pi_mmq_minimum_amount_background_color",'#ee6443'));

        $pi_mmq_minimum_amount_font_color = esc_html(get_option("pi_mmq_minimum_amount_font_color",'#ffffff'));
       
        $pi_mmq_minimum_amount_close_color = esc_html(get_option("pi_mmq_minimum_amount_close_color",'#ffffff'));

        $pi_mmq_minimum_amount_font_weight = esc_html(get_option("pi_mmq_minimum_amount_font_weight",'normal'));
        
        $pi_mmq_minimum_amount_close_weight = esc_html(get_option("pi_mmq_minimum_amount_close_weight",'bold'));

        $pi_mmq_minimum_amount_font_size = esc_html(get_option("pi_mmq_minimum_amount_font_size",'16'));
       
        $pi_mmq_minimum_amount_close_size = esc_html(get_option("pi_mmq_minimum_amount_close_size",'22'));

        $css = "
            .pisol-mmq-bar-container{
                {$pi_mmq_minimum_amount_position}: 0px !important;
                background-color:{$pi_mmq_minimum_amount_background_color};
                color:{$pi_mmq_minimum_amount_font_color};
                font-weight:{$pi_mmq_minimum_amount_font_weight};
                font-size:{$pi_mmq_minimum_amount_font_size}px;
            }

            

            .pisol-mmq-bar-container a.pisol-mmq-close{
                color:{$pi_mmq_minimum_amount_close_color};
                font-weight:{$pi_mmq_minimum_amount_close_weight};
                font-size:{$pi_mmq_minimum_amount_close_size}px;
            }
        ";
        wp_register_style( 'pi-mmq_minimum_amount-dummy', false );
        wp_enqueue_style( 'pi-mmq_minimum_amount-dummy' );
        wp_add_inline_style('pi-mmq_minimum_amount-dummy' , $css );
    }

    function inlineJs(){
        wp_enqueue_script('pisol-mmq-amount-circle-progress', plugin_dir_url( __FILE__ ) . 'js/circle-progress.min.js', array( 'jquery' ), '1.0.0', false );
		wp_enqueue_script( 'pisol-mmq-amount', plugin_dir_url( __FILE__ ) . 'js/pisol-mmq-public.js', array( 'jquery', 'pisol-mmq-amount-circle-progress' ), '1.0.0', false );

		$values = array(
            'ajax_url'=>admin_url('admin-ajax.php'),
            'showContinues' =>true,
            'howLongToShow' =>(6*1000),
            'percent'=> (float)Pisol_Mmq_Cart_Amount::percent(),
            'show_amount_bar' => (bool)(empty(get_option('pi_mmq_min_amount_bar_display',1)) ? false : true),
		);
		wp_localize_script('pisol-mmq-amount', 'pisol_mmq', $values);
    }
}

new Pisol_Mmq_Cart_Amount();

