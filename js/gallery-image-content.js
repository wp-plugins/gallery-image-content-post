(function ( $ ) {
	/**
	 *    Main javascript for gallery image content post
	 */
	function GalleryImageContent() {
		var gallery = true,
			zoom = true;
		if ( $( 'body' ).hasClass( 'single-lightbox' ) ) {
			gallery = false;
		}

		if ( !$( 'body' ).hasClass( 'gallery-image-zoom-effect' ) ) {
			zoom = false;
		}

		$( 'a[rel^="gallery-image-content"]' ).magnificPopup( {
			type               : 'image',
			closeOnContentClick: true,
			closeBtnInside     : false,
			fixedContentPos    : true,
			image              : {
				verticalFit: true
			},
			gallery            : {
				enabled: gallery
			},
			zoom               : {
				enabled : zoom,
				duration: 300 // don't foget to change the duration also in CSS
			}
		} );
	}

	GalleryImageContent();

})( jQuery );