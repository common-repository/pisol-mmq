<?php

/**
 * Fired during plugin activation
 *
 * @link       piwebsolution.com
 * @since      1.0.0
 *
 * @package    Pisol_Mmq
 * @subpackage Pisol_Mmq/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Pisol_Mmq
 * @subpackage Pisol_Mmq/includes
 * @author     PI Websolution <sales@piwebsolution.com>
 */
class Pisol_Mmq_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		add_option('pi_mmq_do_activation_redirect', true);
	}

}
