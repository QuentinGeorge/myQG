$( function() {
    // Transform password text in sha1 hash before sending POST request
    $( "#connexion-submit" ).on( "click", function( oEvent ) {
        oEvent.preventDefault();
        $( "#password" ).val( sha1( $( "#password" ).val() ) );
        $( "#log-form" ).submit();
    } );
} );
