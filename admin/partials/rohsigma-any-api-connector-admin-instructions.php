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
<div class="wrap">
    <h1><?php echo esc_html__( 'Instructions for Rohsigma Any API Connector', 'rohsigma-any-api-connector' ); ?></h1>
    <p><?php echo esc_html__( 'Save an API connection once, test it here, then use the same saved settings in your frontend JavaScript.', 'rohsigma-any-api-connector' ); ?></p>

    <h2><?php echo esc_html__( 'Basic Connection Settings', 'rohsigma-any-api-connector' ); ?></h2>
    <ol>
        <li><strong><?php echo esc_html__( 'Connection Name:', 'rohsigma-any-api-connector' ); ?></strong> <?php echo esc_html__( 'A human-friendly label for this API connection.', 'rohsigma-any-api-connector' ); ?></li>
        <li><strong><?php echo esc_html__( 'Connection Key:', 'rohsigma-any-api-connector' ); ?></strong> <?php echo esc_html__( 'A unique lowercase key used when JavaScript selects a saved connection, for example crm_api.', 'rohsigma-any-api-connector' ); ?></li>
        <li><strong><?php echo esc_html__( 'Description:', 'rohsigma-any-api-connector' ); ?></strong> <?php echo esc_html__( 'Optional notes about the API, its purpose, or the data it returns.', 'rohsigma-any-api-connector' ); ?></li>
        <li><strong><?php echo esc_html__( 'Enabled:', 'rohsigma-any-api-connector' ); ?></strong> <?php echo esc_html__( 'Turn this off to prevent the connection from being used.', 'rohsigma-any-api-connector' ); ?></li>
        <li><strong><?php echo esc_html__( 'Base URL:', 'rohsigma-any-api-connector' ); ?></strong> <?php echo esc_html__( 'The remote API address before any optional path or query parameters.', 'rohsigma-any-api-connector' ); ?></li>
    </ol>

    <h2><?php echo esc_html__( 'Advanced Options', 'rohsigma-any-api-connector' ); ?></h2>
    <ul>
        <li><strong><?php echo esc_html__( 'Base Path:', 'rohsigma-any-api-connector' ); ?></strong> <?php echo esc_html__( 'An optional path added after the Base URL, such as /v1 or /cheapest-hours.', 'rohsigma-any-api-connector' ); ?></li>
        <li><strong><?php echo esc_html__( 'Timeout:', 'rohsigma-any-api-connector' ); ?></strong> <?php echo esc_html__( 'The maximum number of seconds WordPress waits for the remote API response.', 'rohsigma-any-api-connector' ); ?></li>
        <li><strong><?php echo esc_html__( 'Authentication:', 'rohsigma-any-api-connector' ); ?></strong> <?php echo esc_html__( 'Choose None, API Key, Bearer Token, Basic Auth, or OAuth2, then complete only the fields required by that API.', 'rohsigma-any-api-connector' ); ?></li>
        <li><strong><?php echo esc_html__( 'Default Headers:', 'rohsigma-any-api-connector' ); ?></strong> <?php echo esc_html__( 'Header/value pairs attached to every request, such as Accept: application/json.', 'rohsigma-any-api-connector' ); ?></li>
        <li><strong><?php echo esc_html__( 'Default Parameters:', 'rohsigma-any-api-connector' ); ?></strong> <?php echo esc_html__( 'Values sent with every request. GET requests use them as query parameters; other methods use them as request body values.', 'rohsigma-any-api-connector' ); ?></li>
        <li><strong><?php echo esc_html__( 'Allowed Endpoints:', 'rohsigma-any-api-connector' ); ?></strong> <?php echo esc_html__( 'Optional route entries in METHOD /path format. Leave empty when the Base URL and Base Path already identify the API route to test.', 'rohsigma-any-api-connector' ); ?></li>
        <li><strong><?php echo esc_html__( 'Allowed Methods:', 'rohsigma-any-api-connector' ); ?></strong> <?php echo esc_html__( 'Select the request methods the connection may use. The first selected method is used for the default request.', 'rohsigma-any-api-connector' ); ?></li>
        <li><strong><?php echo esc_html__( 'Caching:', 'rohsigma-any-api-connector' ); ?></strong> <?php echo esc_html__( 'Controls whether API responses may be cached and how long the cache lasts.', 'rohsigma-any-api-connector' ); ?></li>
        <li><strong><?php echo esc_html__( 'Rate Limiting:', 'rohsigma-any-api-connector' ); ?></strong> <?php echo esc_html__( 'Sets the maximum number of requests allowed within the configured time window.', 'rohsigma-any-api-connector' ); ?></li>
    </ul>

    <h2><?php echo esc_html__( 'Test Before Using', 'rohsigma-any-api-connector' ); ?></h2>
    <p><?php echo esc_html__( 'Save the connection, then use Test Connection. A successful result confirms the remote API URL, path, parameters, headers, and authentication settings work together.', 'rohsigma-any-api-connector' ); ?></p>

    <h2><?php echo esc_html__( 'Use the Main Connection', 'rohsigma-any-api-connector' ); ?></h2>
    <p><?php echo esc_html__( 'This uses the saved URL, method, headers, and parameters from the main settings page.', 'rohsigma-any-api-connector' ); ?></p>
    <pre><?php echo esc_html( "window.rohsigmaAnyApiConnectorRawRequest()\n    .then(function (result) {\n        if (!result.ok) {\n            throw new Error('Request failed with status ' + result.status);\n        }\n\n        const data = JSON.parse(result.rawBody);\n        console.log(data);\n    })\n    .catch(function (error) {\n        console.error(error);\n    });" ); ?></pre>

    <h2><?php echo esc_html__( 'Use a Saved Connection Key', 'rohsigma-any-api-connector' ); ?></h2>
    <p><?php echo esc_html__( 'When a saved connection is available by key, replace connectionKey with its Connection Key. Its own saved URL, method, headers, and parameters are used automatically.', 'rohsigma-any-api-connector' ); ?></p>
    <pre><?php echo esc_html( "window.rohsigmaAnyApiConnectorRawRequest('connectionKey')\n    .then(function (result) {\n        if (!result.ok) {\n            throw new Error('Request failed with status ' + result.status);\n        }\n\n        const data = JSON.parse(result.rawBody);\n        console.log(data);\n    })\n    .catch(function (error) {\n        console.error(error);\n    });" ); ?></pre>
</div>