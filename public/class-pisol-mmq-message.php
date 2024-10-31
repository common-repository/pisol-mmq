<?php

class Pisol_Mmq_Message{

    function __construct(){
        
        add_action('wp', array($this,'messageOnProductPage'));
        
        $product_loop_position = get_option('pi_mmq_product_loop_position','woocommerce_after_shop_loop_item_title');
        add_action($product_loop_position, array($this,'messageOnProductLoopPage'));
        
        add_action('woocommerce_after_cart_item_name', array($this,'messageOnCartPage'),10,2);
        add_filter('woocommerce_checkout_cart_item_quantity', array($this,'messageOnCheckoutPage'),10,3);
        
    }

    function messageOnProductPage(){
        if(is_product()):
        $message = $this->productState();
        wc_add_notice($message);
        endif;
    }

    function messageOnProductLoopPage(){
        $message = self::productState();
        echo ($message);
    }

    function messageOnCartPage($cart_item, $cart_item_key){
        $message = self::productState($cart_item['product_id']);
        echo '<br>'.($message);
    }

    function messageOnCheckoutPage($link_text, $cart_item, $cart_item_key){
        $message = self::productState($cart_item['product_id']);
        return $link_text.'<br>'.($message);
    }

    /**
     * This select kind of message to show, 
     * based on the quantity added in cart
     */
    static function productState($product_id = false, $return_array = false){
        if($product_id == false){
            $product_id = get_the_ID();
        }

        $max_quantity = Pisol_Mmq_Product::maxQuantityPerProduct($product_id);
        $min_quantity = Pisol_Mmq_Product::minQuantityPerProduct($product_id);
        $quantity_in_cart = Pisol_Mmq_Product::getProductQuantityInCart($product_id);

        if($max_quantity){
            $away_from_max_limit = $max_quantity - $quantity_in_cart;
        }else{
            $away_from_max_limit = false;
        }

        if($min_quantity){
            $away_from_min_requirement = $min_quantity - $quantity_in_cart;
        }else{
            $away_from_min_requirement = false;
        }

        $key = array('{min_quantity}', '{max_quantity}', '{away_from_max}', '{away_from_min}');
        $replace = array($min_quantity, $max_quantity, $away_from_max_limit, $away_from_min_requirement);

        $message  = self::messageGenerator($max_quantity, $min_quantity, $quantity_in_cart, $return_array);

        if($return_array){
            $message['message'] = wp_kses_post(str_replace($key, $replace, $message['message']));
            return $message;
        }
        
        return wp_kses_post(str_replace($key, $replace, $message));
    }
    
    /**
     * Input: min, max and quantity in cart
     * return : message
     */
    static function messageGenerator($max_quantity, $min_quantity, $quantity_in_cart, $return_array = false){
        
        $away_from_max_limit = $max_quantity - $quantity_in_cart;
        $away_from_min_requirement = $min_quantity - $quantity_in_cart;

        $message = "";
        $type= "info";
        if($max_quantity != false && $min_quantity == false){

            if($quantity_in_cart == 0){
                $message = get_option('pi_mmq_max_0',"You can purchase maximum {max_quantity} unit of this product");
                
            }elseif($quantity_in_cart > 0 && $quantity_in_cart < $max_quantity){
                $message = get_option('pi_mmq_max_more_then_0_less_then_max',"You can purchase {away_from_max} unit more");
                
            }elseif($quantity_in_cart >= $max_quantity){
                $message = get_option('pi_mmq_max_equal_to_max',"You have purchased maximum quantity allowed for this product, that is {max_quantity} units");

                if($quantity_in_cart > $max_quantity){
                    $type= "error";
                }
            }

        }elseif($min_quantity != false && $max_quantity == false){

            if($quantity_in_cart == 0){
                $message = get_option('pi_mmq_min_0',"You have to purchase minimum {min_quantity} units to buy this product");
                
            }elseif($quantity_in_cart > 0 && $quantity_in_cart < $min_quantity){
                $message = get_option('pi_mmq_min_more_then_0_less_then_min',"To buy this product, You have to purchase {away_from_min} unit more");
                $type= "error";
            }elseif($quantity_in_cart >= $min_quantity){
                $message = get_option('pi_mmq_min_more_then_min_equal_to_min',"You have purchased {min_quantity} units, required to buy this product");
                
            }

        }elseif($min_quantity != false && $max_quantity != false){

            if($quantity_in_cart == 0){
                $message = get_option('pi_mmq_min_max_0',"You have to purchase minimum {min_quantity} units and maximum {max_quantity} units");
            }elseif($quantity_in_cart > 0 && $quantity_in_cart < $min_quantity){
                $message = get_option('pi_mmq_min_max_more_then_0_less_then_min',"To buy this product, You have to purchase {away_from_min} unit more");
                $type= "error";
            }elseif($quantity_in_cart >= $min_quantity && $quantity_in_cart < $max_quantity){
                $message = get_option('pi_mmq_min_max_more_then_min_less_then_max',"You have purchased {min_quantity} units, required for this product, maximum you can buy {max_quantity} unit");
            }elseif($quantity_in_cart >= $max_quantity){
                $message = get_option('pi_mmq_min_max_equal_to_max',"You have purchased maximum quantity allowed for this product, that is {max_quantity} units");

                if($quantity_in_cart > $max_quantity){
                    $type= "error";
                }
            }

        }elseif($min_quantity == false && $max_quantity == false){
            $message = "";
        }

        if($return_array){
            return array("message"=>$message, "type"=>$type);
        }

        return self::messageTemplate($message, $type);
    }

    static function messageTemplate($message, $type = ""){
        if($message == "") return $message;
        $message = "<div class='pisol-mmq-container {$type}'>".$message."</div>";
        return $message;
    }

    
    
}

new Pisol_Mmq_Message();

