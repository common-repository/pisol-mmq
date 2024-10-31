<?php

class Pisol_Mmq_Product{

    public $global_min_quantity_enabled;
    public $global_max_quantity_enabled;
    public $global_min_quantity;
    public $global_max_quantity;
    

    function __construct(){
        $this->global_min_quantity_enabled = self::globalMinQuantityEnabled();
        $this->global_max_quantity_enabled = self::globalMaxQuantityEnabled();

        $this->global_min_quantity = self::getGlobalMinQuantity();
        $this->global_max_quantity = self::getGlobalMaxQuantity();

        //This controls max quantity in quantity box, and goes on reducing it as they add to cart
        add_filter('woocommerce_quantity_input_args', array($this, 'setMaxQuantityPerProduct'), 10 , 2);

        //This removes add to cart button from product that has reached its limit
        add_filter('woocommerce_is_purchasable', array($this, 'removeAddToCart'), 10, 2);

        /**
         * Use this filters to get min, max quantity for a product or variation
         */
        add_filter('pisol_mmq_min_qty_of_product', array(__CLASS__, 'minQuantityPerProductForFilter'), 10, 3);
        add_filter('pisol_mmq_max_qty_of_product', array(__CLASS__, 'maxQuantityPerProductForFilter'), 10, 3);

    }

    static function globalMinQuantityEnabled(){
        return empty(get_option('pi_mmq_min_quantity_enabled',0)) ? false : true;
    }

    static function globalMaxQuantityEnabled(){
        return empty(get_option('pi_mmq_max_quantity_enabled',0)) ? false : true;
    }

    static function getGlobalMinQuantity(){
        $global_min_quantity_enabled = self::globalMinQuantityEnabled();
        if( $global_min_quantity_enabled){
            $min = get_option('pi_mmq_min_quantity',1);

            return (int)($min != "" ? $min : 1);
        }
        return false;
    }

    static function getGlobalMaxQuantity(){
        $global_max_quantity_enabled = self::globalMaxQuantityEnabled();
        if( $global_max_quantity_enabled ){
            $max = get_option('pi_mmq_max_quantity',false);
            $max = (($max != "" && $max != false) ? $max : false);
            $min = self::getGlobalMinQuantity();
            if($max != false ){
                if($min != false){
                    if($min < $max){
                        return (int)$max;
                    }else{
                        return false;
                    }
                }else{
                    return (int)$max;
                }
            }
        }
        return false;
    }

    static function maxQuantityPerProduct($product_id){
        if(self::globalMinMaxDisabled($product_id)){
            return false;
        }else{
            return self::getGlobalMaxQuantity();
        }
    }

    static function maxQuantityPerProductForFilter($val, $product_id, $variation_id = 0){
        return self::maxQuantityPerProduct($product_id);
    }

    static function minQuantityPerProduct($product_id){
        if(self::globalMinMaxDisabled($product_id)){
            return false;
        }else{
            return self::getGlobalMinQuantity();
        }
    }

    static function minQuantityPerProductForFilter($val, $product_id, $variation_id = 0){
        return self::minQuantityPerProduct($product_id);
    }

    static function getProductQuantityInCart( $product_id ) {
        global $woocommerce;
        $running_qty = 0; // iniializing quantity to 0
        // search the cart for the product in and calculate quantity.
        if(isset(WC()->cart)){
        foreach(WC()->cart->get_cart() as $other_cart_item_keys => $values ) {
            if ( $product_id == $values['product_id'] ) {				
                $running_qty += (int) $values['quantity'];
            }
        }
        }
        return $running_qty;
    }

    static function globalMinMaxDisabled($product_id){
        $global_disabled = get_post_meta($product_id, 'pisol_mmq_disable_global_min_max', true);
        return $global_disabled == 'no' || $global_disabled == "" ? false : true;
    }

    static function isProductValidForCheckout($product_id){
        $cart_quantity = self::getProductQuantityInCart( $product_id );
    }

    /**
     * Dynamically change the max quantity of a product as 
     * buyer adds the product in cart
     */
    function setMaxQuantityPerProduct($args, $product){
        $product_id = $product->get_id();

        $quantity_in_cart = self::getProductQuantityInCart($product_id);
        $max_quantity = self::maxQuantityPerProduct($product_id);
        $min_quantity = self::minQuantityPerProduct($product_id);

        /**
         * we set min and max on product page based on what they have added 
         */
        if(!is_cart()){
            if($max_quantity){
                $max = $max_quantity - $quantity_in_cart;
                $args['max_value'] = $max;
            }

            if($min_quantity){
                $min = $min_quantity - $quantity_in_cart;
                if($min > 0){
                    $args['min_value'] = $min;
                }
            }
        }else{
            /**
             * On cart page we set min to 0 and max to max quantity of that product
             */
            if($max_quantity){
                $args['max_value'] = $max_quantity;
            }

        }
        

        return $args;

    }

    /**
     * This hides add to cart option per product basis
     */
    function removeAddToCart($purchasable, $product){
        $product_id = $product->get_id();

        $quantity_in_cart = self::getProductQuantityInCart($product_id);
        $max_quantity = self::maxQuantityPerProduct($product_id);

        if($max_quantity){
            $max = $max_quantity - $quantity_in_cart;
            if($max <= 0){
                return false;
            }
        }
        return true;
    }
    
}

new Pisol_Mmq_Product();

