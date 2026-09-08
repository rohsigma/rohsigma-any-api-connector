<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://rohsigma.com
 * @since      1.0.0
 *
 * @package    Rohsigma_Any_Api_Connector
 * @subpackage Rohsigma_Any_Api_Connector/admin/partials
 */
?>
<?php
$rohsigma_any_api_connector_connection = isset( $saved_connection ) && is_array( $saved_connection ) ? $saved_connection : array();
$rohsigma_any_api_connector_auth_type = isset( $rohsigma_any_api_connector_connection['auth']['type'] ) ? $rohsigma_any_api_connector_connection['auth']['type'] : 'none';
$rohsigma_any_api_connector_auth_credentials = isset( $rohsigma_any_api_connector_connection['auth']['credentials'] ) && is_array( $rohsigma_any_api_connector_connection['auth']['credentials'] ) ? $rohsigma_any_api_connector_connection['auth']['credentials'] : array();

$rohsigma_any_api_connector_header_rows = array();
if ( isset( $rohsigma_any_api_connector_connection['headers'] ) && is_array( $rohsigma_any_api_connector_connection['headers'] ) ) {
    foreach ( $rohsigma_any_api_connector_connection['headers'] as $rohsigma_any_api_connector_key => $rohsigma_any_api_connector_value ) {
        $rohsigma_any_api_connector_header_rows[] = array(
            'key'   => $rohsigma_any_api_connector_key,
            'value' => $rohsigma_any_api_connector_value,
        );
    }
}
if ( empty( $rohsigma_any_api_connector_header_rows ) ) {
    $rohsigma_any_api_connector_header_rows[] = array(
        'key'   => '',
        'value' => '',
    );
}

$rohsigma_any_api_connector_param_rows = array();
if ( isset( $rohsigma_any_api_connector_connection['params'] ) && is_array( $rohsigma_any_api_connector_connection['params'] ) ) {
    foreach ( $rohsigma_any_api_connector_connection['params'] as $rohsigma_any_api_connector_key => $rohsigma_any_api_connector_value ) {
        $rohsigma_any_api_connector_param_rows[] = array(
            'key'   => $rohsigma_any_api_connector_key,
            'value' => $rohsigma_any_api_connector_value,
        );
    }
}
if ( empty( $rohsigma_any_api_connector_param_rows ) ) {
    $rohsigma_any_api_connector_param_rows[] = array(
        'key'   => '',
        'value' => '',
    );
}

$rohsigma_any_api_connector_allowed_methods = isset( $rohsigma_any_api_connector_connection['allowed_methods'] ) && is_array( $rohsigma_any_api_connector_connection['allowed_methods'] ) ? $rohsigma_any_api_connector_connection['allowed_methods'] : array( 'GET' );
$rohsigma_any_api_connector_allowed_endpoints = isset( $rohsigma_any_api_connector_connection['allowed_endpoints'] ) && is_array( $rohsigma_any_api_connector_connection['allowed_endpoints'] ) ? $rohsigma_any_api_connector_connection['allowed_endpoints'] : array();
$rohsigma_any_api_connector_test_result = isset( $test_result ) && is_array( $test_result ) ? $test_result : array();
$rohsigma_any_api_connector_has_test_result = ! empty( $rohsigma_any_api_connector_test_result );
$rohsigma_any_api_connector_test_result_output = '';
if ( $rohsigma_any_api_connector_has_test_result ) {
    if ( isset( $rohsigma_any_api_connector_test_result['response']['body_pretty'] ) && '' !== $rohsigma_any_api_connector_test_result['response']['body_pretty'] ) {
        $rohsigma_any_api_connector_test_result_output = $rohsigma_any_api_connector_test_result['response']['body_pretty'];
    } else {
        $rohsigma_any_api_connector_test_result_output = json_encode( $rohsigma_any_api_connector_test_result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
    }
}
?>
<div class="wrap">
    <?php if ( ! empty( $save_notice['message'] ) ) : ?>
        <div class="notice notice-<?php echo 'error' === $save_notice['type'] ? 'error' : 'success'; ?> is-dismissible">
            <p><?php echo esc_html( $save_notice['message'] ); ?></p>
        </div>
    <?php endif; ?>

    <h1><?php echo esc_html__( 'Welcome to Rohsigma Any API Connector', 'rohsigma-any-api-connector' ); ?></h1>
    <p><?php echo esc_html__( 'Configure an API connection once, then use it from your frontend scripts.', 'rohsigma-any-api-connector' ); ?></p>
<div class="rohsigma-hide-this">
    <h2><?php echo esc_html__( 'Create a New Connection', 'rohsigma-any-api-connector' ); ?></h2>
    <form method="post" action="" class="rohsigma-connection-form">
        <?php wp_nonce_field( 'rohsigma_any_api_connector_save_connection', 'rohsigma_any_api_connector_nonce' ); ?>

        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row"><label for="rohsigma_connection_name"><?php echo esc_html__( 'Connection Name', 'rohsigma-any-api-connector' ); ?></label></th>
                    <td><input name="rohsigma_connection[name]" type="text" id="rohsigma_connection_name" class="regular-text" value="<?php echo esc_attr( $rohsigma_any_api_connector_connection['name'] ); ?>" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="rohsigma_connection_slug"><?php echo esc_html__( 'Connection Key', 'rohsigma-any-api-connector' ); ?></label></th>
                    <td>
                        <input name="rohsigma_connection[slug]" type="text" id="rohsigma_connection_slug" class="regular-text" placeholder="crm_api" value="<?php echo esc_attr( $rohsigma_any_api_connector_connection['slug'] ); ?>" required>
                        <p class="description"><?php echo esc_html__( 'Unique key used by JavaScript requests, for example: crm_api', 'rohsigma-any-api-connector' ); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="rohsigma_connection_description"><?php echo esc_html__( 'Description', 'rohsigma-any-api-connector' ); ?></label></th>
                    <td><textarea name="rohsigma_connection[description]" id="rohsigma_connection_description" class="large-text" rows="3"><?php echo esc_html( $rohsigma_any_api_connector_connection['description'] ); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__( 'Enabled', 'rohsigma-any-api-connector' ); ?></th>
                    <td>
                        <label>
                            <input name="rohsigma_connection[enabled]" type="checkbox" value="1" <?php checked( ! empty( $rohsigma_any_api_connector_connection['enabled'] ) ); ?>>
                            <?php echo esc_html__( 'Enable this connection', 'rohsigma-any-api-connector' ); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="rohsigma_connection_base_url"><?php echo esc_html__( 'Base URL', 'rohsigma-any-api-connector' ); ?></label></th>
                    <td><input name="rohsigma_connection[base_url]" type="url" id="rohsigma_connection_base_url" class="regular-text code" placeholder="https://api.example.com" value="<?php echo esc_attr( $rohsigma_any_api_connector_connection['base_url'] ); ?>" required></td>
                </tr>
            </tbody>
        </table>

        <p class="rohsigma-advanced-toggle-wrap">
            <button
                type="button"
                class="button-link rohsigma-toggle-advanced"
                aria-expanded="false"
                aria-controls="rohsigma-advanced-fields"
                data-show-label="<?php echo esc_attr__( 'Show advanced options', 'rohsigma-any-api-connector' ); ?>"
                data-hide-label="<?php echo esc_attr__( 'Hide advanced options', 'rohsigma-any-api-connector' ); ?>"
            >
                <?php echo esc_html__( 'Show advanced options', 'rohsigma-any-api-connector' ); ?>
            </button>
        </p>

        <div id="rohsigma-advanced-fields" hidden>
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row"><label for="rohsigma_connection_base_path"><?php echo esc_html__( 'Base Path (Optional)', 'rohsigma-any-api-connector' ); ?></label></th>
                    <td><input name="rohsigma_connection[base_path]" type="text" id="rohsigma_connection_base_path" class="regular-text code" placeholder="/v1" value="<?php echo esc_attr( $rohsigma_any_api_connector_connection['base_path'] ); ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="rohsigma_connection_timeout"><?php echo esc_html__( 'Timeout (seconds)', 'rohsigma-any-api-connector' ); ?></label></th>
                    <td><input name="rohsigma_connection[timeout]" type="number" id="rohsigma_connection_timeout" class="small-text" min="1" value="<?php echo esc_attr( $rohsigma_any_api_connector_connection['timeout'] ); ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="rohsigma_connection_auth_type"><?php echo esc_html__( 'Authentication', 'rohsigma-any-api-connector' ); ?></label></th>
                    <td>
                        <select name="rohsigma_connection[auth_type]" id="rohsigma_connection_auth_type">
                            <option value="none" <?php selected( $rohsigma_any_api_connector_auth_type, 'none' ); ?>><?php echo esc_html__( 'None', 'rohsigma-any-api-connector' ); ?></option>
                            <option value="api_key" <?php selected( $rohsigma_any_api_connector_auth_type, 'api_key' ); ?>><?php echo esc_html__( 'API Key', 'rohsigma-any-api-connector' ); ?></option>
                            <option value="bearer" <?php selected( $rohsigma_any_api_connector_auth_type, 'bearer' ); ?>><?php echo esc_html__( 'Bearer Token', 'rohsigma-any-api-connector' ); ?></option>
                            <option value="basic" <?php selected( $rohsigma_any_api_connector_auth_type, 'basic' ); ?>><?php echo esc_html__( 'Basic Auth', 'rohsigma-any-api-connector' ); ?></option>
                            <option value="oauth2" <?php selected( $rohsigma_any_api_connector_auth_type, 'oauth2' ); ?>><?php echo esc_html__( 'OAuth2', 'rohsigma-any-api-connector' ); ?></option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="rohsigma-auth-field rohsigma-auth-api_key" hidden>
            <h3><?php echo esc_html__( 'API Key Settings', 'rohsigma-any-api-connector' ); ?></h3>
            <p>
                <label for="rohsigma_auth_api_key_header"><?php echo esc_html__( 'Header Name', 'rohsigma-any-api-connector' ); ?></label><br>
                <input name="rohsigma_connection[auth][api_key][header]" type="text" id="rohsigma_auth_api_key_header" class="regular-text" placeholder="X-API-Key" value="<?php echo esc_attr( isset( $rohsigma_any_api_connector_auth_credentials['header'] ) ? $rohsigma_any_api_connector_auth_credentials['header'] : '' ); ?>">
            </p>
            <p>
                <label for="rohsigma_auth_api_key_value"><?php echo esc_html__( 'API Key Value', 'rohsigma-any-api-connector' ); ?></label><br>
                <input name="rohsigma_connection[auth][api_key][value]" type="password" id="rohsigma_auth_api_key_value" class="regular-text" autocomplete="off" value="<?php echo esc_attr( isset( $rohsigma_any_api_connector_auth_credentials['value'] ) ? $rohsigma_any_api_connector_auth_credentials['value'] : '' ); ?>">
            </p>
        </div>

        <div class="rohsigma-auth-field rohsigma-auth-bearer" hidden>
            <h3><?php echo esc_html__( 'Bearer Token Settings', 'rohsigma-any-api-connector' ); ?></h3>
            <p>
                <label for="rohsigma_auth_bearer_token"><?php echo esc_html__( 'Token', 'rohsigma-any-api-connector' ); ?></label><br>
                <input name="rohsigma_connection[auth][bearer][token]" type="password" id="rohsigma_auth_bearer_token" class="regular-text" autocomplete="off" value="<?php echo esc_attr( isset( $rohsigma_any_api_connector_auth_credentials['token'] ) ? $rohsigma_any_api_connector_auth_credentials['token'] : '' ); ?>">
            </p>
        </div>

        <div class="rohsigma-auth-field rohsigma-auth-basic" hidden>
            <h3><?php echo esc_html__( 'Basic Auth Settings', 'rohsigma-any-api-connector' ); ?></h3>
            <p>
                <label for="rohsigma_auth_basic_username"><?php echo esc_html__( 'Username', 'rohsigma-any-api-connector' ); ?></label><br>
                <input name="rohsigma_connection[auth][basic][username]" type="text" id="rohsigma_auth_basic_username" class="regular-text" value="<?php echo esc_attr( isset( $rohsigma_any_api_connector_auth_credentials['username'] ) ? $rohsigma_any_api_connector_auth_credentials['username'] : '' ); ?>">
            </p>
            <p>
                <label for="rohsigma_auth_basic_password"><?php echo esc_html__( 'Password', 'rohsigma-any-api-connector' ); ?></label><br>
                <input name="rohsigma_connection[auth][basic][password]" type="password" id="rohsigma_auth_basic_password" class="regular-text" autocomplete="off" value="<?php echo esc_attr( isset( $rohsigma_any_api_connector_auth_credentials['password'] ) ? $rohsigma_any_api_connector_auth_credentials['password'] : '' ); ?>">
            </p>
        </div>

        <div class="rohsigma-auth-field rohsigma-auth-oauth2" hidden>
            <h3><?php echo esc_html__( 'OAuth2 Settings', 'rohsigma-any-api-connector' ); ?></h3>
            <p>
                <label for="rohsigma_auth_oauth2_token_url"><?php echo esc_html__( 'Token URL', 'rohsigma-any-api-connector' ); ?></label><br>
                <input name="rohsigma_connection[auth][oauth2][token_url]" type="url" id="rohsigma_auth_oauth2_token_url" class="regular-text code" placeholder="https://api.example.com/oauth/token" value="<?php echo esc_attr( isset( $rohsigma_any_api_connector_auth_credentials['token_url'] ) ? $rohsigma_any_api_connector_auth_credentials['token_url'] : '' ); ?>">
            </p>
            <p>
                <label for="rohsigma_auth_oauth2_client_id"><?php echo esc_html__( 'Client ID', 'rohsigma-any-api-connector' ); ?></label><br>
                <input name="rohsigma_connection[auth][oauth2][client_id]" type="text" id="rohsigma_auth_oauth2_client_id" class="regular-text" value="<?php echo esc_attr( isset( $rohsigma_any_api_connector_auth_credentials['client_id'] ) ? $rohsigma_any_api_connector_auth_credentials['client_id'] : '' ); ?>">
            </p>
            <p>
                <label for="rohsigma_auth_oauth2_client_secret"><?php echo esc_html__( 'Client Secret', 'rohsigma-any-api-connector' ); ?></label><br>
                <input name="rohsigma_connection[auth][oauth2][client_secret]" type="password" id="rohsigma_auth_oauth2_client_secret" class="regular-text" autocomplete="off" value="<?php echo esc_attr( isset( $rohsigma_any_api_connector_auth_credentials['client_secret'] ) ? $rohsigma_any_api_connector_auth_credentials['client_secret'] : '' ); ?>">
            </p>
            <p>
                <label for="rohsigma_auth_oauth2_scope"><?php echo esc_html__( 'Scope (Optional)', 'rohsigma-any-api-connector' ); ?></label><br>
                <input name="rohsigma_connection[auth][oauth2][scope]" type="text" id="rohsigma_auth_oauth2_scope" class="regular-text" placeholder="read write" value="<?php echo esc_attr( isset( $rohsigma_any_api_connector_auth_credentials['scope'] ) ? $rohsigma_any_api_connector_auth_credentials['scope'] : '' ); ?>">
            </p>
        </div>

        <h3><?php echo esc_html__( 'Default Headers', 'rohsigma-any-api-connector' ); ?></h3>
        <p class="description"><?php echo esc_html__( 'Headers are attached to every request for this connection.', 'rohsigma-any-api-connector' ); ?></p>
        <table class="widefat striped rohsigma-kv-table" id="rohsigma-headers-table">
            <thead>
                <tr>
                    <th><?php echo esc_html__( 'Header', 'rohsigma-any-api-connector' ); ?></th>
                    <th><?php echo esc_html__( 'Value', 'rohsigma-any-api-connector' ); ?></th>
                    <th><?php echo esc_html__( 'Action', 'rohsigma-any-api-connector' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $rohsigma_any_api_connector_header_rows as $rohsigma_any_api_connector_row ) : ?>
					<tr>
                    <td><input type="text" name="rohsigma_connection[headers][key][]" class="regular-text" placeholder="Accept" value="<?php echo esc_attr( $rohsigma_any_api_connector_row['key'] ); ?>"></td>
                    <td><input type="text" name="rohsigma_connection[headers][value][]" class="regular-text" placeholder="application/json" value="<?php echo esc_attr( $rohsigma_any_api_connector_row['value'] ); ?>"></td>
						<td><button type="button" class="button-link-delete rohsigma-remove-row"><?php echo esc_html__( 'Remove', 'rohsigma-any-api-connector' ); ?></button></td>
					</tr>
				<?php endforeach; ?>
            </tbody>
        </table>
        <p><button type="button" class="button rohsigma-add-row" data-target="rohsigma-headers-table"><?php echo esc_html__( 'Add Header', 'rohsigma-any-api-connector' ); ?></button></p>

        <h3><?php echo esc_html__( 'Default Parameters', 'rohsigma-any-api-connector' ); ?></h3>
        <p class="description"><?php echo esc_html__( 'Parameters are added as query/body values based on request type.', 'rohsigma-any-api-connector' ); ?></p>
        <table class="widefat striped rohsigma-kv-table" id="rohsigma-params-table">
            <thead>
                <tr>
                    <th><?php echo esc_html__( 'Parameter', 'rohsigma-any-api-connector' ); ?></th>
                    <th><?php echo esc_html__( 'Value', 'rohsigma-any-api-connector' ); ?></th>
                    <th><?php echo esc_html__( 'Action', 'rohsigma-any-api-connector' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $rohsigma_any_api_connector_param_rows as $rohsigma_any_api_connector_row ) : ?>
					<tr>
                    <td><input type="text" name="rohsigma_connection[params][key][]" class="regular-text" placeholder="page" value="<?php echo esc_attr( $rohsigma_any_api_connector_row['key'] ); ?>"></td>
                    <td><input type="text" name="rohsigma_connection[params][value][]" class="regular-text" placeholder="1" value="<?php echo esc_attr( $rohsigma_any_api_connector_row['value'] ); ?>"></td>
						<td><button type="button" class="button-link-delete rohsigma-remove-row"><?php echo esc_html__( 'Remove', 'rohsigma-any-api-connector' ); ?></button></td>
					</tr>
				<?php endforeach; ?>
            </tbody>
        </table>
        <p><button type="button" class="button rohsigma-add-row" data-target="rohsigma-params-table"><?php echo esc_html__( 'Add Parameter', 'rohsigma-any-api-connector' ); ?></button></p>

        <h3><?php echo esc_html__( 'Request Controls', 'rohsigma-any-api-connector' ); ?></h3>
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row"><label for="rohsigma_allowed_endpoints"><?php echo esc_html__( 'Allowed Endpoints', 'rohsigma-any-api-connector' ); ?></label></th>
                    <td>
                        <textarea name="rohsigma_connection[allowed_endpoints]" id="rohsigma_allowed_endpoints" class="large-text code" rows="5" placeholder="GET /users&#10;POST /orders"><?php echo esc_html( implode( "\n", $rohsigma_any_api_connector_allowed_endpoints ) ); ?></textarea>
                        <p class="description"><?php echo esc_html__( 'One endpoint per line in METHOD /path format, or leave empty to allow all configured routes.', 'rohsigma-any-api-connector' ); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__( 'Allowed Methods', 'rohsigma-any-api-connector' ); ?></th>
                    <td class="rohsigma-checkbox-group">
                        <label><input type="checkbox" name="rohsigma_connection[allowed_methods][]" value="GET" <?php checked( in_array( 'GET', $rohsigma_any_api_connector_allowed_methods, true ) ); ?>> GET</label>
                        <label><input type="checkbox" name="rohsigma_connection[allowed_methods][]" value="POST" <?php checked( in_array( 'POST', $rohsigma_any_api_connector_allowed_methods, true ) ); ?>> POST</label>
                        <label><input type="checkbox" name="rohsigma_connection[allowed_methods][]" value="PUT" <?php checked( in_array( 'PUT', $rohsigma_any_api_connector_allowed_methods, true ) ); ?>> PUT</label>
                        <label><input type="checkbox" name="rohsigma_connection[allowed_methods][]" value="PATCH" <?php checked( in_array( 'PATCH', $rohsigma_any_api_connector_allowed_methods, true ) ); ?>> PATCH</label>
                        <label><input type="checkbox" name="rohsigma_connection[allowed_methods][]" value="DELETE" <?php checked( in_array( 'DELETE', $rohsigma_any_api_connector_allowed_methods, true ) ); ?>> DELETE</label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__( 'Caching', 'rohsigma-any-api-connector' ); ?></th>
                    <td>
                        <label>
                            <input name="rohsigma_connection[cache][enabled]" type="checkbox" value="1" <?php checked( ! empty( $rohsigma_any_api_connector_connection['cache']['enabled'] ) ); ?>>
                            <?php echo esc_html__( 'Enable response caching', 'rohsigma-any-api-connector' ); ?>
                        </label>
                        <p>
                            <label for="rohsigma_cache_ttl"><?php echo esc_html__( 'Cache Duration (seconds)', 'rohsigma-any-api-connector' ); ?></label><br>
                            <input name="rohsigma_connection[cache][ttl]" type="number" id="rohsigma_cache_ttl" class="small-text" min="1" value="<?php echo esc_attr( $rohsigma_any_api_connector_connection['cache']['ttl'] ); ?>">
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__( 'Rate Limiting', 'rohsigma-any-api-connector' ); ?></th>
                    <td>
                        <label>
                            <input name="rohsigma_connection[rate_limit][enabled]" type="checkbox" value="1" <?php checked( ! empty( $rohsigma_any_api_connector_connection['rate_limit']['enabled'] ) ); ?>>
                            <?php echo esc_html__( 'Enable rate limiting', 'rohsigma-any-api-connector' ); ?>
                        </label>
                        <p>
                            <label for="rohsigma_rate_limit_max"><?php echo esc_html__( 'Max Requests', 'rohsigma-any-api-connector' ); ?></label><br>
                            <input name="rohsigma_connection[rate_limit][max]" type="number" id="rohsigma_rate_limit_max" class="small-text" min="1" value="<?php echo esc_attr( $rohsigma_any_api_connector_connection['rate_limit']['max'] ); ?>">
                        </p>
                        <p>
                            <label for="rohsigma_rate_limit_window"><?php echo esc_html__( 'Window (seconds)', 'rohsigma-any-api-connector' ); ?></label><br>
                            <input name="rohsigma_connection[rate_limit][window]" type="number" id="rohsigma_rate_limit_window" class="small-text" min="1" value="<?php echo esc_attr( $rohsigma_any_api_connector_connection['rate_limit']['window'] ); ?>">
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
		</div>

        <p class="submit">
            <button type="submit" name="rohsigma_connection_submit" value="1" class="button button-primary">
                <?php echo esc_html__( 'Save Connection', 'rohsigma-any-api-connector' ); ?>
            </button>
        </p>
    </form>
    <?php
    //if connection has been saved, show test connection button
    if ( isset( $saved_connection ) && is_array( $saved_connection ) ) :
    ?>
        <hr>
    <h1><?php echo esc_html__( 'Test connection', 'rohsigma-any-api-connector' ); ?></h1>
        <form method="post" action="" class="rohsigma-testing-form">
        <?php wp_nonce_field( 'rohsigma_any_api_connector_test_connection', 'rohsigma_any_api_connector_nonce' ); ?>

        <p class="submit">
            <button type="submit" name="rohsigma_connection_test" value="1" class="button button-success">
                <?php echo esc_html__( 'Test Connection', 'rohsigma-any-api-connector' ); ?>
            </button>
        </p>
        </form>
        <div id="rohsigma-test-connection-result" class="rohsigma-test-connection-result" <?php echo $rohsigma_any_api_connector_has_test_result ? '' : 'hidden'; ?>>
            <h2><?php echo esc_html__( 'Test Result', 'rohsigma-any-api-connector' ); ?></h2>
            <pre id="rohsigma-test-connection-output"><?php echo esc_html( $rohsigma_any_api_connector_test_result_output ); ?></pre>
       <?php endif; ?> 
    </div>
</div>