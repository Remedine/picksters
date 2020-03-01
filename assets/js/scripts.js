( function( $ ) {

    $( document ).ready( function() {

        $( '.report-a-bug' ).on( 'click', '.show-form', function( event ) {

            // change label and switch class
            $( this ).text( settings.send_label ).removeClass( 'show-form' ).addClass( 'send-report' );

            // show textarea
            $( '.report-a-bug-message' ).slideDown( 'slow' );

        })

    });

})( jQuery );