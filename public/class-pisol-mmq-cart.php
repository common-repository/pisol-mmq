<?php

class Pisol_Mmq_Cart{

    function __construct(){
      
       add_filter( 'woocommerce_add_to_cart_validation', array($this, 'add_to_cart_validation'), 1, 5 );

       add_filter( 'woocommerce_update_cart_validation', array($this,'update_cart_validation'), 1, 4 );

       add_action('woocommerce_check_cart_items', array($this,'validateCheckout'));
    }

    
    /**
     * This is extra layer of security
     * This is run when add to cart is done
     * This make sure that you don't add more then Max allowed quantity
     */
    function add_to_cart_validation( $passed, $product_id, $quantity, $variation_id = '', $variations = '' ) {
        $product_max = Pisol_Mmq_Product::maxQuantityPerProduct($product_id);
        
        if ( ! empty( $product_max ) ) {
            // min is empty
            if ( false !== $product_max ) {
                $new_max = $product_max;
            } else {
                // neither max is set, so get out
                return $passed;
            }
        }

        $already_in_cart 	= Pisol_Mmq_Product::getProductQuantityInCart( $product_id );
        $product 			= wc_get_product( $product_id );
        $product_title 		= $product->get_title();
        
        if (isset($new_max) && !is_null( $new_max ) && !empty( $already_in_cart ) ) {
            
            if ( ( $already_in_cart + $quantity ) > $new_max ) {
                // oops. too much.
                $passed = false;			
                wc_add_notice( apply_filters( 'isa_wc_max_qty_error_message_already_had', sprintf( __( 'You can add a maximum of %1$s %2$s\'s to %3$s. You already have %4$s.' ), 
                            $new_max,
                            $product_title,
                            '<a href="' . esc_url( wc_get_cart_url() ) . '">' . __( 'your cart' ) . '</a>',
                            $already_in_cart ),
                        $new_max,
                        $already_in_cart ),
                'error' );
            }
        }
        return $passed;
    }   

    function update_cart_validation( $passed, $cart_item_key, $values, $quantity ) {
		$product_id = $values['product_id'];
		$product_max = Pisol_Mmq_Product::maxQuantityPerProduct($product_id);
        
        if ( ! empty( $product_max ) ) {
            // min is empty
            if ( false !== $product_max ) {
                $new_max = $product_max;
            } else {
                // neither max is set, so get out
                return $passed;
            }
        }

        $already_in_cart 	= Pisol_Mmq_Product::getProductQuantityInCart( $product_id );
        $updates_quantity 	= $quantity;
        $product 			= wc_get_product( $product_id );
        $product_title 		= $product->get_title();
        
        if (isset($new_max) && !is_null( $new_max ) && !empty( $updates_quantity ) ) {
            
            if ( ( $updates_quantity ) > $new_max ) {
                // oops. too much.
                $passed = false;			
                wc_add_notice( apply_filters( 'pisol_mmq_max_qty_error_message_already_had', sprintf( __( 'You can add a maximum of %1$s %2$s\'s to %3$s. You already have %4$s.','pisol-mmq' ), 
                            $new_max,
                            $product_title,
                            '<a href="' . esc_url( wc_get_cart_url() ) . '">' . __( 'your cart' ) . '</a>',
                            $already_in_cart ),
                        $new_max,
                        $already_in_cart ),
                'error' );
            }
        }
        return $passed;

	}

    function validateCheckout(){
        $error = "";
        $product_with_qty_error = array();
        foreach ( WC()->cart->get_cart() as $other_cart_item_keys => $values ) {
            $message_array = Pisol_Mmq_Message::productState($values['product_id'], true);
            if($message_array['type']=='error'){
                $product_with_qty_error[] = get_the_title( $values['product_id'] );
                
            }
        }

        if(!empty($product_with_qty_error)){
            wc_add_notice( sprintf(__("Please fix the quantity error in %s products",'pisol-mmq'), implode(', ',$product_with_qty_error)), 'error');
        }
        
        return true;
        
    }

    
    
}

new Pisol_Mmq_Cart();

