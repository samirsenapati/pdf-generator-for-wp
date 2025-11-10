<?php
/**
 * The file that defines the core plugin api class
 *
 * A class definition that includes api's endpoints and functions used across the plugin
 *
 * @link       https://finaldoc.com/
 * @since      1.0.0
 *
 * @package    FinalPDF
 * @subpackage FinalPDF/package/rest-api/version1
 */

/**
 * The core plugin  api class.
 *
 * This is used to define internationalization, api-specific hooks, and
 * endpoints for plugin.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    FinalPDF
 * @subpackage FinalPDF/package/rest-api/version1
 * @author     FinalDoc <support@finaldoc.com>
 */
class FinalPDF_Rest_Api {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin api.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the merthods, and set the hooks for the api and
	 *
	 * @since    1.0.0
	 * @param   string $plugin_name    Name of the plugin.
	 * @param   string $version        Version of the plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}


	/**
	 * Define endpoints for the plugin.
	 *
	 * Uses the FinalPDF_Rest_Api class in order to create the endpoint
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function wps_finalpdf_add_endpoint() {
		register_rest_route(
			'pgfw-route/v1',
			'/pgfw-dummy-data/',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'wps_finalpdf_default_callback' ),
				'permission_callback' => array( $this, 'wps_finalpdf_default_permission_check' ),
			)
		);
	}


	/**
	 * Begins validation process of api endpoint.
	 *
	 * @param   Array $request    All information related with the api request containing in this array.
	 * @return  Array   $result   return rest response to server from where the endpoint hits.
	 * @since    1.0.0
	 */
	public function wps_finalpdf_default_permission_check( $request ) {

		// Add rest api validation for each request.
		$result = true;
		return $result;
	}


	/**
	 * Begins execution of api endpoint.
	 *
	 * @param   Array $request    All information related with the api request containing in this array.
	 * @return  Array   $wps_finalpdf_response   return rest response to server from where the endpoint hits.
	 * @since    1.0.0
	 */
	public function wps_finalpdf_default_callback( $request ) {

		require_once FINALPDF_DIR_PATH . 'package/rest-api/version1/class-finalpdf-api-process.php';
		$wps_finalpdf_api_obj     = new FinalPDF_Api_Process();
		$wps_finalpdf_resultsdata = $wps_finalpdf_api_obj->wps_finalpdf_default_process( $request );
		if ( is_array( $wps_finalpdf_resultsdata ) && isset( $wps_finalpdf_resultsdata['status'] ) && 200 == $wps_finalpdf_resultsdata['status'] ) {
			unset( $wps_finalpdf_resultsdata['status'] );
			$wps_finalpdf_response = new WP_REST_Response( $wps_finalpdf_resultsdata, 200 );
		} else {
			$wps_finalpdf_response = new WP_Error( $wps_finalpdf_resultsdata );
		}
		return $wps_finalpdf_response;
	}
}
