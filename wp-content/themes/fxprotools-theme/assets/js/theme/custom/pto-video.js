(function($, document, window){
    $(document).ready(function(){
        $youtube            = 'youtube.com';
        $vimeo              = 'vimeo.com';
        $auto_start         = $('*[data-ptoautostart*="yes"]');
        $disable_controls   = $('*[data-ptodisablecontrols*="yes"]');
        $disable_related    = $('*[data-ptodisablerelated*="yes"]');
        $hide_info          = $('*[data-ptohideinfo*="yes"]');
        $disable_sharing    = $('*[data-ptodisablesharing*="yes"]');

        var youtube = {
            "autoplay"          : "autoplay=1",
            "disablecontrols"   : "controls=0",
            "disablerelvideos"  : "rel=0",
            "hideinfo"          : "showinfo=0",
            "disablesharing"    : null
        };

        var vimeo = {
            "autoplay"          : "autoplay=1",
            "disablecontrols"   : null,
            "disablerelvideos"  : null,
            "hideinfo"          : "title=0&byline=0&portrait=0",
            // TODO: not working on cross-domain content.
            "disablesharing"    : function(){ $(".fx-video-container iframe").contents().find( ".sidedock" ).hide(); }
        };

        if( $('.fx-video-container iframe').is(':visible') ){
            // auto start option for youtube and vimeo
            if( $auto_start.length > 0 ){
                $auto_start.each(function(){
                    $iframe = $(this).find('iframe');
                    $src = $iframe.attr('src');

                    if($src.indexOf($youtube) !== -1){
                        $new_src = $src.replace( $src, checkQueryString( $src, youtube.autoplay ) );
                        $iframe.attr( 'src', $new_src );
                        // console.log($new_src);
                    }else if($src.indexOf($vimeo) !== -1){ //
                        $new_src = $src.replace( $src, checkQueryString( $src, vimeo.autoplay ) );
                        $iframe.attr( 'src', $new_src );
                        // console.log($new_src);
                    }else{
                        return;
                    }
                    
                });
            }

            // show control option only for vimeo.
            if( $disable_controls.length > 0 ){
                $disable_controls.each(function(){
                    $iframe = $(this).find('iframe');
                    $src = $iframe.attr('src');

                    if($src.indexOf($youtube) !== -1){
                        $new_src = $src.replace( $src, checkQueryString( $src, youtube.disablecontrols ) );
                        $iframe.attr( 'src', $new_src );
                    }else{
                        return;
                    }
                });
            }

            // Disable related videos
            if( $disable_related.length > 0 ){
                $disable_related.each(function(){
                    $iframe = $(this).find('iframe');
                    $src = $iframe.attr('src');

                    if($src.indexOf($youtube) !== -1){
                        $new_src = $src.replace( $src, checkQueryString( $src, youtube.disablerelvideos ) );
                        $iframe.attr( 'src', $new_src );
                    }else if($src.indexOf($vimeo) !== -1){
                        // available only via vimeo admin.
                    }else{
                        return;
                    }
                    
                });
            }
            
            // Hide info
            if( $hide_info.length > 0 ){
                $hide_info.each(function(){
                    $iframe = $(this).find('iframe');
                    $src = $iframe.attr('src');

                    if($src.indexOf($youtube) !== -1){
                        $new_src = $src.replace( $src, checkQueryString( $src, youtube.hideinfo ) );
                        $iframe.attr( 'src', $new_src );
                    }else if($src.indexOf($vimeo) !== -1){
                        $new_src = $src.replace( $src, checkQueryString( $src, vimeo.hideinfo ) );
                        $iframe.attr( 'src', $new_src );
                    }else{
                        return;
                    }
                    
                });
            }

            // Disable sharing
            if( $disable_sharing.length > 0 ){
                $disable_sharing.each(function(){
                    $iframe = $(this).find('iframe');
                    $src = $iframe.attr('src');

                    if($src.indexOf($youtube) !== -1){
                        // works only on vimeo
                    }else if($src.indexOf($vimeo) !== -1){
                        vimeo.disablesharing();
                    }else{
                        return;
                    }
                    
                });
            }
        }

        // Check if starting query string (?) exist in url, then use (&), if not, then use (?) as query string 
        function checkQueryString( src, service_params ){
            if( src.indexOf('?') !== -1 ) return src + ((src.indexOf(service_params) !== -1) ? "" : "&" + service_params);
            else return src + ((src.indexOf(service_params) !== -1) ? "" : "?" + service_params);
        }
    });
})(jQuery, document, window);