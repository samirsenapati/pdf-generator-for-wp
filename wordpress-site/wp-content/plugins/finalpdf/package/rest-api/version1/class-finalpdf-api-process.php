<?php
/**
 * Fired during plugin activation
 *
 * @link       https://finaldoc.com/
 * @since      1.0.0
 *
 * @package    FinalPDF
 * @subpackage FinalPDF/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'FinalPDF_Api_Process' ) ) {

	/**
	 * The plugin API class.
	 *
	 * This is used to define the functions and data manipulation for custom endpoints.
	 *
	 * @since      1.0.0
	 * @package    FinalPDF
	 * @subpackage FinalPDF/includes
	 * @author     FinalDoc <wpswings.com>
	 */
	class FinalPDF_Api_Process {

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {

		}

		/**
		 * Define the function to process data for custom endpoint.
		 *
		 * @since    1.0.0
		 * @param   Array $finalpdf_request  data of requesting headers and other information.
		 * @return  Array $wps_finalpdf_rest_response    returns processed data and status of operations.
		 */
		public function wps_finalpdf_default_process( $finalpdf_request ) {
			$wps_finalpdf_rest_response = array();

			// Write your custom code here.

			$wps_finalpdf_rest_response['status'] = 200;
			$wps_finalpdf_rest_response['data'] = $finalpdf_request->get_headers();
			return $wps_finalpdf_rest_response;
		}
	}
}
