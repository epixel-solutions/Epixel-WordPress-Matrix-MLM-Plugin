/* global jQuery */
jQuery(function($) {
    // Remove the drafting functions.
    //$(".misc-pub-section.misc-pub-post-status, #minor-publishing-actions, .misc-pub-section.curtime.misc-pub-curtime, .misc-pub-section.misc-pub-visibility").remove();
    $(".hndle span").first().text("Send");
    
    // Change the "Title" label to "Subject".
    $("label[for=title]").text("Enter subject here");
    
    if ($("#post-status-display").text() == "Published") {
        $("#wpbody-content input, #wpbody-content select, #wpbody-content button, #wpbody-content textarea").prop("disabled", true);
        
        var interval = setInterval(function() {
            if (tinymce && tinymce.activeEditor && tinymce.activeEditor.getContent().length > -1) {
                var content = tinymce.activeEditor.getContent();
                $("#wp-email_content-wrap").empty().append($("<textarea />").prop("disabled", true).val(content).css("width", "100%").css("height", "250px"));
                clearInterval(interval);
            }
        }, 100);
    }
});