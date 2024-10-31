<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              piwebsolution.com
 * @since             2.1.72
 * @package           Pisol_Mmq
 *
 * @wordpress-plugin
 * Plugin Name:       Minimum Maximum quantity & Minimum Order amount for WooCommerce
 * Plugin URI:        piwebsolution.com/minimum-maximum-quantity-woocommerce-documentation
 * Description:       It allows you to set Minimum and maximum quantity on global level, category level and on product laver, You can also set minimum amount on the global or category level
 * Version:           2.1.72
 * Author:            PI Websolution
 * Author URI:        piwebsolution.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pisol-mmq
 * Domain Path:       /languages
 * WC tested up to: 9.3.3
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/* 
    Making sure woocommerce is there 
*/
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if(!is_plugin_active( 'woocommerce/woocommerce.php')){
    function pi_mmq_wc_notice() {
        ?>
        <div class="error notice">
            <p><?php _e( 'Please Install and Activate WooCommerce plugin, without that this plugin cant work', 'pisol-mmq'); ?></p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'pi_mmq_wc_notice' );
    return;
}

if(is_plugin_active( 'pisol-mmq-pro/pisol-mmq.php')){
    function pi_mmq_pro_notice() {
        ?>
        <div class="error notice">
            <p><?php _e( 'You have the PRO version of this plugin in your site', 'pisol-mmq'); ?></p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'pi_mmq_pro_notice' );
    return;
}else{


/**
 * Declare compatible with HPOS new order table 
 */
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

/**
 * Currently plugin version.
 * Start at version 2.1.72 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PISOL_MMQ_VERSION', '2.1.72' );
define('PI_MMQ_BUY_URL', 'https://www.piwebsolution.com/product/minimum-maximum-quantity-minimum-order-amount/');
define('PI_MMQ_ADD_TO_CART_URL', 'https://www.piwebsolution.com/checkout?add-to-cart=1513&variation_id=1517&utm_campaign=mmq&utm_source=website&utm_medium=direct-buy');
define('PI_MMQ_PRICE', '$19');
define('PI_MMQ_DELETE_SETTING', false);

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pisol-mmq-activator.php
 */
function activate_pisol_mmq() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pisol-mmq-activator.php';
	Pisol_Mmq_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pisol-mmq-deactivator.php
 */
function deactivate_pisol_mmq() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pisol-mmq-deactivator.php';
	Pisol_Mmq_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pisol_mmq' );
register_deactivation_hook( __FILE__, 'deactivate_pisol_mmq' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pisol-mmq.php';

function pisol_mmq_plugin_link( $links ) {
	$links = array_merge( array(
        '<a href="' . esc_url( admin_url( '/admin.php?page=pisol-mmq-notification' ) ) . '">' . __( 'Settings','pisol-mmq' ) . '</a>',
        '<a style="color:#0a9a3e; font-weight:bold;" target="_blank" href="' . esc_url(PI_MMQ_BUY_URL) . '">' . __( 'Buy PRO Version','pisol-mmq' ) . '</a>'
	), $links );
	return $links;
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'pisol_mmq_plugin_link' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pisol_mmq() {

	$plugin = new Pisol_Mmq();
	$plugin->run();

}
run_pisol_mmq();

}