/**
 * outlet.js
 *
 * Javascript used by the Arcade theme.
 */
( function() {
	jQuery( window ).load( function() {
		jQuery( '.page-template-template-homepage .storefront-product-section' ).each( function ( i ) {
			i += 1;

			jQuery( this ).addClass( 'position-' + i );
		});

		jQuery( '.page-template-template-homepage .storefront-product-section' ).last().addClass( 'last' );
	});
} )();
