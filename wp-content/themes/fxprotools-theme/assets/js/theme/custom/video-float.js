jQuery(document).ready(function($){

    var $window = $( window ); // 1. Window Object.
    var $featuredMedia = $('#pto--floating-video'); // 1. The Video Container.
    
    if( $featuredMedia.length > 0 ){
        var $featuredVideo = $('#pto--floating-video iframe'); // 2. The Youtube Video.
        var player; // 3. Youtube player object.
        var top = $featuredMedia.offset().top; // 4. The video position from the top of the document;
        var offset = Math.floor( top + ( $featuredMedia.outerHeight() / 2 ) ); //5. offset.

        $window
        .on( "resize", function() {
            top = $featuredMedia.offset().top;
            offset = Math.floor( top + ( $featuredMedia.outerHeight() / 2 ) );
        } )
        
        .on( "scroll", function() {
            $featuredVideo.toggleClass( "is-sticky", $window.scrollTop() > offset );
        } );
    }

    

});