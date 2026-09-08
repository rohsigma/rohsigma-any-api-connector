<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://rohsigma.com
 * @since      1.0.0
 *
 * @package    Rohsigma_Any_Api_Connector
 * @subpackage Rohsigma_Any_Api_Connector/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Rohsigma_Any_Api_Connector
 * @subpackage Rohsigma_Any_Api_Connector/public
 * @author     rohsigma <rich@rohsigma.com>
 */
class Rohsigma_Any_Api_Connector_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rohsigma_Any_Api_Connector_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rohsigma_Any_Api_Connector_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rohsigma-any-api-connector-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rohsigma_Any_Api_Connector_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rohsigma_Any_Api_Connector_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rohsigma-any-api-connector-public.js?vdate=' . gmdate( 'YmdHis' ), array( 'jquery' ), $this->version, false );
		wp_add_inline_script(
			$this->plugin_name,
			'window.rohsigmaAnyApiConnector = ' . wp_json_encode( $this->get_public_connection_data() ) . ';',
			'before'
		);

	}

	/**
	 * Get a safe frontend-ready copy of the saved connection.
	 *
	 * Secrets are intentionally omitted from the browser payload.
	 *
	 * @since 1.0.0
	 * @return array<string, mixed>
	 */
	private function get_public_connection_data() {
		$defaults = $this->get_default_connection();
		$saved_connection = get_option( 'rohsigma_any_api_connector_connection', array() );

		if ( ! is_array( $saved_connection ) ) {
			$saved_connection = array();
		}

		$saved_connection = wp_parse_args( $saved_connection, $defaults );

		$public_auth = array(
			'type'        => isset( $saved_connection['auth']['type'] ) ? sanitize_key( $saved_connection['auth']['type'] ) : 'none',
			'credentials' => array(),
		);

		if ( isset( $saved_connection['auth']['type'] ) && 'api_key' === $saved_connection['auth']['type'] ) {
			$public_auth['credentials']['header'] = isset( $saved_connection['auth']['credentials']['header'] ) ? sanitize_text_field( $saved_connection['auth']['credentials']['header'] ) : '';
		}

		return array(
			'name'              => isset( $saved_connection['name'] ) ? sanitize_text_field( $saved_connection['name'] ) : '',
			'slug'              => isset( $saved_connection['slug'] ) ? sanitize_key( $saved_connection['slug'] ) : '',
			'description'       => isset( $saved_connection['description'] ) ? sanitize_text_field( $saved_connection['description'] ) : '',
			'enabled'           => ! empty( $saved_connection['enabled'] ),
			'base_url'          => isset( $saved_connection['base_url'] ) ? esc_url_raw( $saved_connection['base_url'] ) : '',
			'base_path'         => isset( $saved_connection['base_path'] ) ? sanitize_text_field( $saved_connection['base_path'] ) : '',
			'timeout'           => isset( $saved_connection['timeout'] ) ? max( 1, absint( $saved_connection['timeout'] ) ) : $defaults['timeout'],
			'auth'              => $public_auth,
			'headers'           => isset( $saved_connection['headers'] ) && is_array( $saved_connection['headers'] ) ? $saved_connection['headers'] : array(),
			'params'            => isset( $saved_connection['params'] ) && is_array( $saved_connection['params'] ) ? $saved_connection['params'] : array(),
			'allowed_methods'   => isset( $saved_connection['allowed_methods'] ) && is_array( $saved_connection['allowed_methods'] ) ? $saved_connection['allowed_methods'] : $defaults['allowed_methods'],
			'allowed_endpoints' => isset( $saved_connection['allowed_endpoints'] ) && is_array( $saved_connection['allowed_endpoints'] ) ? $saved_connection['allowed_endpoints'] : array(),
			'cache'             => isset( $saved_connection['cache'] ) && is_array( $saved_connection['cache'] ) ? $saved_connection['cache'] : $defaults['cache'],
			'rate_limit'        => isset( $saved_connection['rate_limit'] ) && is_array( $saved_connection['rate_limit'] ) ? $saved_connection['rate_limit'] : $defaults['rate_limit'],
		);
	}

	/**
	 * Default connection shape used for frontend exposure.
	 *
	 * @since 1.0.0
	 * @return array<string, mixed>
	 */
	private function get_default_connection() {
		return array(
			'name'              => '',
			'slug'              => '',
			'description'       => '',
			'enabled'           => true,
			'base_url'          => '',
			'base_path'         => '',
			'timeout'           => 15,
			'auth'              => array(
				'type'        => 'none',
				'credentials' => array(),
			),
			'headers'           => array(),
			'params'            => array(),
			'allowed_methods'   => array( 'GET' ),
			'allowed_endpoints' => array(),
			'cache'             => array(
				'enabled' => true,
				'ttl'     => 300,
			),
			'rate_limit'        => array(
				'enabled' => false,
				'max'     => 100,
				'window'  => 60,
			),
		);
	}

}
