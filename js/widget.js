jQuery( document ).ready( function ( $ ) {
    $( '.imdb-ratings-charts-link' ).click( function ( ev ) {
        $( ev.currentTarget )
            .parents( '.imdb-widget' )
            .find( '.imdb-widget-charts' )
            .show();
        return false;
    } );
    $( '.imdb-widget-charts-close' ).click( function ( ev ) {
        $( ev.currentTarget ).parents( '.imdb-widget-charts' ).hide();
    } );
} );

