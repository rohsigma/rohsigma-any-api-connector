=== Rohsigma Any API Connector ===
Contributors: rohsigma
Tags: api, rest api, javascript, integration, connector
Requires at least: 5.8
Tested up to: 7.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Test an external REST API connection in WordPress, inspect its feed, and use the result to build a custom frontend interface.

== Description ==

Rohsigma Any API Connector is a practical developer tool for mid-level developers who need to check an API feed and quickly build a frontend around it. Configure the connection in the WordPress admin area, including the remote URL, parameters, headers, authentication method, allowed methods, cache settings, and rate-limit settings.

Use the built-in Test Connection tool to inspect the remote response before building the frontend. The response can be copied from the admin screen and supplied to an AI model as working context. An AI coding assistant can then help generate the HTML, CSS, and JavaScript needed to present the feed in the interface you want.

The plugin provides a small frontend helper for developers who want to connect their own interface to a public feed. The plugin supplies the connection and feed access; the site owner or developer remains responsible for the frontend interface, user authentication, permissions, validation, and other application-specific prevention measures.

Developed by Rohsigma.

Website: https://rohsigma.es/
Support: rich@rohsigma.es

== Features ==

* Configure a REST API base URL and optional base path.
* Add default request parameters and headers.
* Configure no authentication, API key, bearer token, Basic Auth, or OAuth2 credentials.
* Restrict allowed request methods and optional endpoint paths.
* Configure cache and rate-limit settings.
* Test a saved connection from the WordPress admin area.
* Inspect formatted JSON response bodies during testing.
* Copy a test response as context for AI-assisted frontend development.
* Use saved connection settings from frontend JavaScript.
* Includes an admin Instructions page for setup and frontend examples.

== Installation ==

1. Upload the `rohsigma-any-api-connector` folder to `/wp-content/plugins/`, or install the plugin ZIP through the WordPress Plugins screen.
2. Activate rohsigma any api connector through the Plugins screen in WordPress.
3. Open **Any API Connector** in the WordPress admin menu.
4. Enter the connection details and select **Save Connection**.
5. Select **Test Connection** to confirm the remote API returns the expected result.
6. Copy the test response from the admin result area when you need help building a frontend interface.

== Configuration ==

= Basic settings =

* **Connection Name**: A label used to identify the connection.
* **Connection Key**: A unique lowercase identifier for JavaScript use, for example `crm_api`.
* **Description**: Optional notes about the API connection.
* **Enabled**: Turns the connection on or off.
* **Base URL**: The remote API address.

= Advanced settings =

* **Base Path**: An optional path appended to the Base URL, for example `/v1`.
* **Timeout**: The maximum time WordPress waits for a remote response.
* **Authentication**: Select None, API Key, Bearer Token, Basic Auth, or OAuth2 and complete the required fields.
* **Default Headers**: Header/value pairs sent with every request.
* **Default Parameters**: Values sent with every request. GET requests use query parameters; other request methods use body values.
* **Allowed Endpoints**: Optional `METHOD /path` entries used to define routes.
* **Allowed Methods**: Select the request methods the connection may use.
* **Caching**: Enable response caching and set its duration.
* **Rate Limiting**: Set the maximum number of requests allowed within a time window.

== Frontend JavaScript ==

This plugin is intended to help developers create the frontend experience around an API feed. You can use the test response as a precise example of the data shape, then ask an AI coding model to help create the HTML, CSS, and JavaScript for your page or component.

After saving an enabled main connection, call the public helper from a frontend script:

`window.rohsigmaAnyApiConnectorRawRequest().then(function (result) { console.log(result.rawBody); });`

The helper uses the saved URL, base path, first allowed method, headers, and parameters.

You can override saved query parameters for a request by passing an object as the second argument:

`window.rohsigmaAnyApiConnectorRawRequest('connectionKey', { zone: 'ES' }).then(function (result) { console.log(result.rawBody); });`

Do not put API keys, bearer tokens, passwords, or OAuth client secrets into browser JavaScript. Use server-side requests or a protected WordPress REST route for authenticated APIs.

The helper is intentionally a starting point for a custom frontend. Add the authentication, authorization, input validation, rate limiting, output escaping, and abuse prevention required by your application. Do not treat a public feed as a substitute for application-level access control.

= AI-assisted workflow =

1. Configure and save the connection in the admin area.
2. Run **Test Connection**.
3. Copy the formatted response from **Test Result**.
4. Paste the response into your AI coding model along with the interface you want to build.
5. Ask the model to generate or adapt the frontend HTML, CSS, and JavaScript to the response fields.
6. Review, test, and secure the generated code before publishing it.

== Frequently Asked Questions ==

= Why does Test Connection return Not Found? =

Check how Base URL, Base Path, and Allowed Endpoints combine. The test request uses the saved URL and path configuration, then applies saved parameters.

= Can I use URL parameters from the current page? =

Yes. Read a page parameter with `URLSearchParams` and pass it as the second argument to the JavaScript helper.

`const zone = new URLSearchParams(window.location.search).get('zone') || 'ES';`

`window.rohsigmaAnyApiConnectorRawRequest('connectionKey', { zone: zone });`

= Does this expose credentials in JavaScript? =

The plugin omits bearer tokens, passwords, OAuth client secrets, and API-key values from its public connection data. Do not use browser-side requests for APIs that require secrets.

== Changelog ==

= 1.0.0 =
* Initial release.

== Support ==

For support or product information, visit https://rohsigma.es/ or email rich@rohsigma.es.