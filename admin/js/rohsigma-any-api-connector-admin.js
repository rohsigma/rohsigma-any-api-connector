(function( $ ) {
	'use strict';

	$( function() {
		var authTypeField = $( '#rohsigma_connection_auth_type' );
		var advancedToggleButton = $( '.rohsigma-toggle-advanced' );
		var advancedFields = $( '#rohsigma-advanced-fields' );

		function toggleAuthFields() {
			var selected = authTypeField.val();
			$( '.rohsigma-auth-field' ).prop( 'hidden', true );
			if ( selected && selected !== 'none' ) {
				$( '.rohsigma-auth-' + selected ).prop( 'hidden', false );
			}
		}

		function addRepeaterRow( targetId ) {
			var table = $( '#' + targetId );
			var lastRow = table.find( 'tbody tr:last' );
			var newRow = lastRow.clone();
			newRow.find( 'input' ).val( '' );
			table.find( 'tbody' ).append( newRow );
		}

		function setAdvancedToggleState( expanded ) {
			var label = expanded ? advancedToggleButton.data( 'hide-label' ) : advancedToggleButton.data( 'show-label' );
			advancedToggleButton.text( label );
			advancedToggleButton.attr( 'aria-expanded', expanded ? 'true' : 'false' );
			advancedFields.prop( 'hidden', ! expanded );
		}

		authTypeField.on( 'change', toggleAuthFields );
		toggleAuthFields();

		if ( advancedToggleButton.length && advancedFields.length ) {
			setAdvancedToggleState( false );

			advancedToggleButton.on( 'click', function( event ) {
				event.preventDefault();
				var expanded = advancedToggleButton.attr( 'aria-expanded' ) === 'true';
				setAdvancedToggleState( ! expanded );
			} );
		}

		$( document ).on( 'click', '.rohsigma-add-row', function() {
			var targetId = $( this ).data( 'target' );
			if ( targetId ) {
				addRepeaterRow( targetId );
			}
		} );

		$( document ).on( 'click', '.rohsigma-remove-row', function() {
			var tbody = $( this ).closest( 'tbody' );
			if ( tbody.find( 'tr' ).length > 1 ) {
				$( this ).closest( 'tr' ).remove();
				return;
			}

			$( this ).closest( 'tr' ).find( 'input' ).val( '' );
		} );
	} );

})( jQuery );
