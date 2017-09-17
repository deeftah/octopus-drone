jQuery(document).ready(function($){

	jQuery( '.product-reviews' ).owlCarousel({
		items: 				carousel_parameters.columns,
		autoHeight: 		true,
		itemsDesktop:  		[1199,carousel_parameters.columns],
		itemsDesktopSmall:	[979,carousel_parameters.columns],
		itemsTablet:		[768,1],
		itemsMobile: 	 	[479,1],
		theme:				'sr-carousel',
		pagination: 		false,
		navigation: 		true,
		navigationText: 	[carousel_parameters.previous,carousel_parameters.next],
	});

});