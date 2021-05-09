(function( $ ) {
	'use strict';

	$( document ).on( 'change', '.form-news-author select', function() {
		
		var form = $( this ).closest('form');

		var data = {
			action: 'pj_news_by_author_id',
			_wpnonce: form.find( '#_wpnonce' ).val(),
			author_id: $( this ).val(),
		};

		form.find( '.loader' ).removeClass( 'd-none' );

		$.post( form.attr('action'), data, function (response) {
			form.find( '.loader' ).addClass( 'd-none' );
			
			$( '.news-content' ).html( response.data.content );
		} );
	} );
})( jQuery );
