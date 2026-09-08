<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://rohsigma.com
 * @since      1.0.0
 *
 * @package    Rohsigma_Any_Api_Connector
 * @subpackage Rohsigma_Any_Api_Connector/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rohsigma_Any_Api_Connector
 * @subpackage Rohsigma_Any_Api_Connector/admin
 * @author     rohsigma <rich@rohsigma.com>
 */
class Rohsigma_Any_Api_Connector_Admin {

	/**
	 * Option name used to store the saved connection.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $connection_option_name = 'rohsigma_any_api_connector_connection';

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rohsigma-any-api-connector-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rohsigma-any-api-connector-admin.js?vdate=' . gmdate( 'YmdHis' ), array( 'jquery' ), $this->version, false );

	}
	public function add_plugin_admin_menu() {
		add_menu_page(
			'Rohsigma Any API Connector',
			'Any API Connector',
			'manage_options',
			'rohsigma-any-api-connector',
			array( $this, 'display_settings_page' ),
			plugin_dir_url( __FILE__ ) . 'images/logo-32x32.png',
			100
		);

		add_submenu_page(
			'rohsigma-any-api-connector',
			__( 'Instructions', 'rohsigma-any-api-connector' ),
			__( 'Instructions', 'rohsigma-any-api-connector' ),
			'manage_options',
			'rohsigma-any-api-connector-instructions',
			array( $this, 'display_instructions_page' )
		);
		
	}
	public function display_settings_page() {
		$save_notice = $this->maybe_save_connection();
		$test_result = $this->maybe_test_connection();
		$saved_connection = $this->get_saved_connection();

		include_once plugin_dir_path( __FILE__ ) . 'partials/rohsigma-any-api-connector-admin-display.php';
	}



	public function display_instructions_page() {
		include_once plugin_dir_path( __FILE__ ) . 'partials/rohsigma-any-api-connector-admin-instructions.php';
	}

	/**
	 * Register the frontend API proxy route.
	 *
	 * @since 1.0.0
	 */
	public function register_rest_routes() {
		register_rest_route(
			'rohsigma-any-api-connector/v1',
			'/request',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'handle_rest_request' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Run the saved connection through the REST API.
	 *
	 * @since 1.0.0
	 * @return WP_REST_Response
	 */
	public function handle_rest_request() {
		$connection = $this->get_saved_connection();
		if ( empty( $connection['enabled'] ) ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => __( 'This API connection is disabled.', 'rohsigma-any-api-connector' ),
				),
				403
			);
		}

		$result = $this->test_api_connection();
		$status_code = isset( $result['response']['status_code'] ) ? absint( $result['response']['status_code'] ) : ( ! empty( $result['success'] ) ? 200 : 502 );

		return new WP_REST_Response(
			array(
				'success' => ! empty( $result['success'] ),
				'message' => isset( $result['message'] ) ? $result['message'] : '',
				'response' => array(
					'status_code' => $status_code,
					'body'        => isset( $result['response']['body'] ) ? $result['response']['body'] : '',
				),
			),
			$status_code
		);
	}
	/**
	 * Run API test when the test form is submitted.
	 *
	 * @since 1.0.0
	 * @return array<string, mixed>
	 */
	private function maybe_test_connection() {
		$rohsigma_test_submit = filter_input( INPUT_POST, 'rohsigma_connection_test', FILTER_UNSAFE_RAW );
		if ( null === $rohsigma_test_submit ) {
			return array();
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return array(
				'success' => false,
				'message' => __( 'You are not allowed to test this connection.', 'rohsigma-any-api-connector' ),
			);
		}

		$rohsigma_nonce = filter_input( INPUT_POST, 'rohsigma_any_api_connector_nonce', FILTER_UNSAFE_RAW );
		$rohsigma_nonce = is_string( $rohsigma_nonce ) ? sanitize_text_field( $this->rohsigma_unslash_deep( $rohsigma_nonce ) ) : '';

		if ( '' === $rohsigma_nonce || ! wp_verify_nonce( $rohsigma_nonce, 'rohsigma_any_api_connector_test_connection' ) ) {
			return array(
				'success' => false,
				'message' => __( 'Security check failed. Please try again.', 'rohsigma-any-api-connector' ),
			);
		}

		return $this->test_api_connection();
	}

	/**
	 * Save the connection when the form is submitted.
	 *
	 * @since 1.0.0
	 * @return array<string, string>
	 */
	private function maybe_save_connection() {
		$rohsigma_submit = filter_input( INPUT_POST, 'rohsigma_connection_submit', FILTER_UNSAFE_RAW );
		if ( null === $rohsigma_submit ) {
			return array();
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return array(
				'type'    => 'error',
				'message' => __( 'You are not allowed to save this connection.', 'rohsigma-any-api-connector' ),
			);
		}

		$rohsigma_nonce = filter_input( INPUT_POST, 'rohsigma_any_api_connector_nonce', FILTER_UNSAFE_RAW );
		$rohsigma_nonce = is_string( $rohsigma_nonce ) ? sanitize_text_field( $this->rohsigma_unslash_deep( $rohsigma_nonce ) ) : '';

		if ( '' === $rohsigma_nonce || ! wp_verify_nonce( $rohsigma_nonce, 'rohsigma_any_api_connector_save_connection' ) ) {
			return array(
				'type'    => 'error',
				'message' => __( 'Security check failed. Please try again.', 'rohsigma-any-api-connector' ),
			);
		}

		$raw_connection = array();
		$rohsigma_connection_input = filter_input( INPUT_POST, 'rohsigma_connection', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		if ( is_array( $rohsigma_connection_input ) ) {
			$raw_connection = $this->rohsigma_unslash_deep( $rohsigma_connection_input );
		}

		$normalized_connection = $this->normalize_connection( $raw_connection );
		update_option( $this->connection_option_name, $normalized_connection, false );

		return array(
			'type'    => 'success',
			'message' => __( 'Connection saved successfully.', 'rohsigma-any-api-connector' ),
		);
	}

	/**
	 * Get a saved connection merged with defaults.
	 *
	 * @since 1.0.0
	 * @return array<string, mixed>
	 */
	private function get_saved_connection() {
		$defaults = $this->get_default_connection();
		$saved = get_option( $this->connection_option_name, array() );

		if ( ! is_array( $saved ) ) {
			return $defaults;
		}

		$saved = wp_parse_args( $saved, $defaults );

		if ( ! is_array( $saved['headers'] ) ) {
			$saved['headers'] = array();
		}

		if ( ! is_array( $saved['params'] ) ) {
			$saved['params'] = array();
		}

		if ( ! isset( $saved['auth'] ) || ! is_array( $saved['auth'] ) ) {
			$saved['auth'] = $defaults['auth'];
		}

		if ( ! isset( $saved['auth']['credentials'] ) || ! is_array( $saved['auth']['credentials'] ) ) {
			$saved['auth']['credentials'] = array();
		}

		if ( ! isset( $saved['allowed_methods'] ) || ! is_array( $saved['allowed_methods'] ) ) {
			$saved['allowed_methods'] = $defaults['allowed_methods'];
		}

		if ( ! isset( $saved['allowed_endpoints'] ) || ! is_array( $saved['allowed_endpoints'] ) ) {
			$saved['allowed_endpoints'] = array();
		}

		if ( ! isset( $saved['cache'] ) || ! is_array( $saved['cache'] ) ) {
			$saved['cache'] = $defaults['cache'];
		} else {
			$saved['cache'] = wp_parse_args( $saved['cache'], $defaults['cache'] );
		}

		if ( ! isset( $saved['rate_limit'] ) || ! is_array( $saved['rate_limit'] ) ) {
			$saved['rate_limit'] = $defaults['rate_limit'];
		} else {
			$saved['rate_limit'] = wp_parse_args( $saved['rate_limit'], $defaults['rate_limit'] );
		}

		return $saved;
	}

	/**
	 * Normalize incoming connection data into a predictable structure.
	 *
	 * @since 1.0.0
	 * @param array<string, mixed> $raw_connection Raw form data.
	 * @return array<string, mixed>
	 */
	private function normalize_connection( $raw_connection ) {
		$defaults = $this->get_default_connection();

		$connection = array(
			'name'             => isset( $raw_connection['name'] ) ? sanitize_text_field( $raw_connection['name'] ) : '',
			'slug'             => isset( $raw_connection['slug'] ) ? sanitize_key( $raw_connection['slug'] ) : '',
			'description'      => isset( $raw_connection['description'] ) ? sanitize_text_field( $raw_connection['description'] ) : '',
			'enabled'          => ! empty( $raw_connection['enabled'] ),
			'base_url'         => isset( $raw_connection['base_url'] ) ? esc_url_raw( $raw_connection['base_url'] ) : '',
			'base_path'        => isset( $raw_connection['base_path'] ) ? sanitize_text_field( $raw_connection['base_path'] ) : '',
			'timeout'          => isset( $raw_connection['timeout'] ) ? max( 1, absint( $raw_connection['timeout'] ) ) : $defaults['timeout'],
			'headers'          => $this->normalize_key_value_map( isset( $raw_connection['headers'] ) ? $raw_connection['headers'] : array() ),
			'params'           => $this->normalize_key_value_map( isset( $raw_connection['params'] ) ? $raw_connection['params'] : array() ),
			'allowed_methods'  => $this->normalize_allowed_methods( isset( $raw_connection['allowed_methods'] ) ? $raw_connection['allowed_methods'] : array() ),
			'allowed_endpoints'=> $this->normalize_endpoints( isset( $raw_connection['allowed_endpoints'] ) ? $raw_connection['allowed_endpoints'] : '' ),
			'cache'            => array(
				'enabled' => ! empty( $raw_connection['cache']['enabled'] ),
				'ttl'     => isset( $raw_connection['cache']['ttl'] ) ? max( 1, absint( $raw_connection['cache']['ttl'] ) ) : $defaults['cache']['ttl'],
			),
			'rate_limit'       => array(
				'enabled' => ! empty( $raw_connection['rate_limit']['enabled'] ),
				'max'     => isset( $raw_connection['rate_limit']['max'] ) ? max( 1, absint( $raw_connection['rate_limit']['max'] ) ) : $defaults['rate_limit']['max'],
				'window'  => isset( $raw_connection['rate_limit']['window'] ) ? max( 1, absint( $raw_connection['rate_limit']['window'] ) ) : $defaults['rate_limit']['window'],
			),
		);

		$connection['auth'] = $this->normalize_auth(
			isset( $raw_connection['auth_type'] ) ? $raw_connection['auth_type'] : 'none',
			isset( $raw_connection['auth'] ) ? $raw_connection['auth'] : array()
		);

		return $connection;
	}

	/**
	 * Normalize auth payload into {type, credentials} structure.
	 *
	 * @since 1.0.0
	 * @param string              $auth_type Selected auth type.
	 * @param array<string, mixed> $auth_raw  Auth field values.
	 * @return array<string, mixed>
	 */
	private function normalize_auth( $auth_type, $auth_raw ) {
		$allowed_types = array( 'none', 'api_key', 'bearer', 'basic', 'oauth2' );
		$auth_type = sanitize_key( $auth_type );

		if ( ! in_array( $auth_type, $allowed_types, true ) ) {
			$auth_type = 'none';
		}

		$credentials = array();

		if ( 'api_key' === $auth_type ) {
			$credentials['header'] = isset( $auth_raw['api_key']['header'] ) ? sanitize_text_field( $auth_raw['api_key']['header'] ) : '';
			$credentials['value'] = isset( $auth_raw['api_key']['value'] ) ? sanitize_text_field( $auth_raw['api_key']['value'] ) : '';
		}

		if ( 'bearer' === $auth_type ) {
			$credentials['token'] = isset( $auth_raw['bearer']['token'] ) ? sanitize_text_field( $auth_raw['bearer']['token'] ) : '';
		}

		if ( 'basic' === $auth_type ) {
			$credentials['username'] = isset( $auth_raw['basic']['username'] ) ? sanitize_text_field( $auth_raw['basic']['username'] ) : '';
			$credentials['password'] = isset( $auth_raw['basic']['password'] ) ? sanitize_text_field( $auth_raw['basic']['password'] ) : '';
		}

		if ( 'oauth2' === $auth_type ) {
			$credentials['token_url'] = isset( $auth_raw['oauth2']['token_url'] ) ? esc_url_raw( $auth_raw['oauth2']['token_url'] ) : '';
			$credentials['client_id'] = isset( $auth_raw['oauth2']['client_id'] ) ? sanitize_text_field( $auth_raw['oauth2']['client_id'] ) : '';
			$credentials['client_secret'] = isset( $auth_raw['oauth2']['client_secret'] ) ? sanitize_text_field( $auth_raw['oauth2']['client_secret'] ) : '';
			$credentials['scope'] = isset( $auth_raw['oauth2']['scope'] ) ? sanitize_text_field( $auth_raw['oauth2']['scope'] ) : '';
		}

		return array(
			'type'        => $auth_type,
			'credentials' => $credentials,
		);
	}

	/**
	 * Normalize key/value table into associative array.
	 *
	 * @since 1.0.0
	 * @param array<string, mixed> $raw_pairs Raw key/value lists.
	 * @return array<string, string>
	 */
	private function normalize_key_value_map( $raw_pairs ) {
		if ( ! is_array( $raw_pairs ) ) {
			return array();
		}

		$keys = isset( $raw_pairs['key'] ) && is_array( $raw_pairs['key'] ) ? $raw_pairs['key'] : array();
		$values = isset( $raw_pairs['value'] ) && is_array( $raw_pairs['value'] ) ? $raw_pairs['value'] : array();

		$normalized = array();
		foreach ( $keys as $index => $key ) {
			$clean_key = sanitize_text_field( $key );
			if ( '' === $clean_key ) {
				continue;
			}

			$clean_value = isset( $values[ $index ] ) ? sanitize_text_field( $values[ $index ] ) : '';
			$normalized[ $clean_key ] = $clean_value;
		}

		return $normalized;
	}

	/**
	 * Normalize allowed methods.
	 *
	 * @since 1.0.0
	 * @param array<int, string> $methods Raw methods list.
	 * @return array<int, string>
	 */
	private function normalize_allowed_methods( $methods ) {
		$valid_methods = array( 'GET', 'POST', 'PUT', 'PATCH', 'DELETE' );
		$normalized = array();

		if ( is_array( $methods ) ) {
			foreach ( $methods as $method ) {
				$method = strtoupper( sanitize_text_field( $method ) );
				if ( in_array( $method, $valid_methods, true ) ) {
					$normalized[] = $method;
				}
			}
		}

		if ( empty( $normalized ) ) {
			$normalized[] = 'GET';
		}

		return array_values( array_unique( $normalized ) );
	}

	/**
	 * Normalize endpoint textarea lines.
	 *
	 * @since 1.0.0
	 * @param string $endpoints Raw textarea content.
	 * @return array<int, string>
	 */
	private function normalize_endpoints( $endpoints ) {
		if ( ! is_string( $endpoints ) ) {
			return array();
		}

		$lines = preg_split( '/\r\n|\r|\n/', $endpoints );
		$normalized = array();

		foreach ( $lines as $line ) {
			$line = trim( sanitize_text_field( $line ) );
			if ( '' !== $line ) {
				$normalized[] = $line;
			}
		}

		return array_values( array_unique( $normalized ) );
	}

	/**
	 * Unslash scalar or nested array values without relying on wp_unslash.
	 *
	 * @since 1.0.0
	 * @param mixed $value Raw value.
	 * @return mixed
	 */
	private function rohsigma_unslash_deep( $value ) {
		if ( is_array( $value ) ) {
			$unslashed = array();
			foreach ( $value as $key => $nested_value ) {
				$unslashed[ $key ] = $this->rohsigma_unslash_deep( $nested_value );
			}

			return $unslashed;
		}

		if ( is_string( $value ) ) {
			return stripslashes( $value );
		}

		return $value;
	}

	/**
	 * Default connection shape for form restore.
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

	/**
	 * test function to check if the API is working
	 *
	 * @since 1.0.0
	 * @return array<string, mixed>
	 */
	public function test_api_connection() {
		$connection = $this->get_saved_connection();
		$base_url = isset( $connection['base_url'] ) ? esc_url_raw( $connection['base_url'] ) : '';
		$base_path = isset( $connection['base_path'] ) ? trim( sanitize_text_field( $connection['base_path'] ), '/' ) : '';
		$timeout = isset( $connection['timeout'] ) ? max( 1, absint( $connection['timeout'] ) ) : 15;

		$allowed_methods = isset( $connection['allowed_methods'] ) && is_array( $connection['allowed_methods'] ) ? $connection['allowed_methods'] : array( 'GET' );
		$allowed_endpoints = isset( $connection['allowed_endpoints'] ) && is_array( $connection['allowed_endpoints'] ) ? $connection['allowed_endpoints'] : array();

		$headers = isset( $connection['headers'] ) && is_array( $connection['headers'] ) ? $connection['headers'] : array();
		$params = isset( $connection['params'] ) && is_array( $connection['params'] ) ? $connection['params'] : array();

		$auth_type = isset( $connection['auth']['type'] ) ? sanitize_key( $connection['auth']['type'] ) : 'none';
		$auth_credentials = isset( $connection['auth']['credentials'] ) && is_array( $connection['auth']['credentials'] ) ? $connection['auth']['credentials'] : array();

		$method = strtoupper( sanitize_text_field( isset( $allowed_methods[0] ) ? $allowed_methods[0] : 'GET' ) );
		$endpoint_path = '';

		if ( ! empty( $allowed_endpoints ) && isset( $allowed_endpoints[0] ) ) {
			$endpoint_definition = trim( sanitize_text_field( $allowed_endpoints[0] ) );
			$endpoint_parts = preg_split( '/\s+/', $endpoint_definition, 2 );

			if ( is_array( $endpoint_parts ) && ! empty( $endpoint_parts[0] ) ) {
				$possible_method = strtoupper( $endpoint_parts[0] );
				if ( in_array( $possible_method, array( 'GET', 'POST', 'PUT', 'PATCH', 'DELETE' ), true ) ) {
					$method = $possible_method;
					$endpoint_path = isset( $endpoint_parts[1] ) ? $endpoint_parts[1] : '';
				} else {
					$endpoint_path = $endpoint_definition;
				}
			}
		}

		if ( '' === $base_url ) {
			return array(
				'success' => false,
				'message' => __( 'Base URL is missing. Please save a valid connection first.', 'rohsigma-any-api-connector' ),
			);
		}

		$path_segments = array();
		if ( '' !== $base_path ) {
			$path_segments[] = $base_path;
		}
		if ( '' !== $endpoint_path ) {
			$path_segments[] = trim( $endpoint_path, '/' );
		}

		$request_url = rtrim( $base_url, '/' );
		if ( ! empty( $path_segments ) ) {
			$request_url .= '/' . implode( '/', $path_segments );
		}

		if ( 'api_key' === $auth_type ) {
			$header_name = isset( $auth_credentials['header'] ) ? sanitize_text_field( $auth_credentials['header'] ) : 'X-API-Key';
			$header_value = isset( $auth_credentials['value'] ) ? sanitize_text_field( $auth_credentials['value'] ) : '';
			if ( '' !== $header_name && '' !== $header_value ) {
				$headers[ $header_name ] = $header_value;
			}
		}

		if ( 'bearer' === $auth_type ) {
			$token = isset( $auth_credentials['token'] ) ? sanitize_text_field( $auth_credentials['token'] ) : '';
			if ( '' !== $token ) {
				$headers['Authorization'] = 'Bearer ' . $token;
			}
		}

		if ( 'basic' === $auth_type ) {
			$username = isset( $auth_credentials['username'] ) ? sanitize_text_field( $auth_credentials['username'] ) : '';
			$password = isset( $auth_credentials['password'] ) ? sanitize_text_field( $auth_credentials['password'] ) : '';
			if ( '' !== $username || '' !== $password ) {
				$headers['Authorization'] = 'Basic ' . base64_encode( $username . ':' . $password );
			}
		}

		$request_args = array(
			'method'  => $method,
			'timeout' => $timeout,
			'headers' => $headers,
		);

		if ( 'GET' === $method && ! empty( $params ) ) {
			$request_url = add_query_arg( $params, $request_url );
		} elseif ( ! empty( $params ) ) {
			$request_args['body'] = $params;
		}

		$response = wp_remote_request( $request_url, $request_args );

		if ( is_wp_error( $response ) ) {
			return array(
				'success' => false,
				'message' => $response->get_error_message(),
				'vars'    => array(
					'base_url'      => $base_url,
					'base_path'     => $base_path,
					'request_url'   => $request_url,
					'method'        => $method,
					'timeout'       => $timeout,
					'auth_type'     => $auth_type,
					'headers_count' => count( $headers ),
					'params_count'  => count( $params ),
				),
			);
		}

		$status_code = wp_remote_retrieve_response_code( $response );
		$content_type = wp_remote_retrieve_header( $response, 'content-type' );
		$body = wp_remote_retrieve_body( $response );
		$pretty_body = $body;

		if ( is_string( $body ) && '' !== trim( $body ) ) {
			$decoded_body = json_decode( $body, true );
			if ( JSON_ERROR_NONE === json_last_error() && null !== $decoded_body ) {
				$pretty_body = json_encode( $decoded_body, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
			}
		}

		return array(
			'success' => $status_code >= 200 && $status_code < 300,
			'message' => sprintf(
				/* translators: %d: HTTP response status code. */
				__( 'Request completed with status code %d.', 'rohsigma-any-api-connector' ),
				$status_code
			),
			'vars'    => array(
				'base_url'      => $base_url,
				'base_path'     => $base_path,
				'request_url'   => $request_url,
				'method'        => $method,
				'timeout'       => $timeout,
				'auth_type'     => $auth_type,
				'headers_count' => count( $headers ),
				'params_count'  => count( $params ),
			),
			'response' => array(
				'status_code' => $status_code,
				'content_type' => is_string( $content_type ) ? $content_type : '',
				'body'        => $body,
				'body_pretty' => $pretty_body,
			),
		);
	}
}
