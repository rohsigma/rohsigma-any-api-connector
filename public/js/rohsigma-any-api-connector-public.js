(function( $ ) {
	'use strict';

	window.rohsigmaAnyApiConnectorRequest = function( connectionKey, extraParams ) {
		var config = window.rohsigmaAnyApiConnector || {};
		var connectionConfig;
		var method = Array.isArray( config.allowed_methods ) && config.allowed_methods[ 0 ] ? String( config.allowed_methods[ 0 ] ).toUpperCase() : 'GET';
		var baseUrl = String( config.base_url || '' );
		var basePath = ( config.base_path || '' ).replace( /^\/+|\/+$/g, '' );
		var queryParams = $.extend( true, {}, config.params || {} );
		var headers = $.extend( true, {}, config.headers || {} );
		var urlObject;

		if ( connectionKey && window.rohsigmaAnyApiConnections && window.rohsigmaAnyApiConnections[ connectionKey ] ) {
			connectionConfig = window.rohsigmaAnyApiConnections[ connectionKey ];
			method = Array.isArray( connectionConfig.allowed_methods ) && connectionConfig.allowed_methods[ 0 ] ? String( connectionConfig.allowed_methods[ 0 ] ).toUpperCase() : method;
			baseUrl = String( connectionConfig.base_url || baseUrl );
			basePath = ( connectionConfig.base_path || basePath ).replace( /^\/+|\/+$/g, '' );
			queryParams = $.extend( true, {}, connectionConfig.params || {} );
			headers = $.extend( true, {}, connectionConfig.headers || {} );
		}

		if ( extraParams && typeof extraParams === 'object' ) {
			queryParams = $.extend( true, {}, queryParams, extraParams );
		}

		if ( ! baseUrl ) {
			throw new Error( 'rohsigmaAnyApiConnector.base_url is missing.' );
		}

		urlObject = new URL( baseUrl, window.location.origin );

		if ( basePath ) {
			var cleanPathSegments = [];
			var existingPath = ( urlObject.pathname || '' ).replace( /^\/+|\/+$/g, '' );

			if ( existingPath ) {
				cleanPathSegments.push( existingPath );
			}

			if ( basePath ) {
				cleanPathSegments.push( basePath );
			}

			urlObject.pathname = '/' + cleanPathSegments.join( '/' );
		}

		Object.keys( queryParams ).forEach( function( key ) {
			if ( queryParams[ key ] !== undefined && queryParams[ key ] !== null && queryParams[ key ] !== '' ) {
				urlObject.searchParams.set( key, queryParams[ key ] );
			}
		} );
		return {
			url: urlObject.toString(),
			params: queryParams,
			options: {
				method: method,
				headers: headers
			}
		};
	};

	window.rohsigmaAnyApiConnectorRawRequest = function( connectionKey, extraParams ) {
		var request = window.rohsigmaAnyApiConnectorRequest( connectionKey, extraParams );

		return fetch( request.url, {
			method: request.options.method,
			headers: request.options.headers,
			body: request.options.body
		} ).then( function( response ) {
			return response.text().then( function( rawBody ) {
			//	console.log( rawBody );

				return {
					ok: response.ok,
					status: response.status,
					headers: response.headers,
					rawBody: rawBody
				};
			} );
		} );
	};

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );
