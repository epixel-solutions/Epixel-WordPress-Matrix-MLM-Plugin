jQuery(function ($) {
    var ajaxUrl = fx.ajax_url;
    
    $(".email-select").change(function() {
        var allSelected = $(".email-select:not(:checked)").length == 0;
        $("#selectAll").prop("checked", allSelected);
    });
    
    $("#selectAll").change(function() {
        if ($(this).prop("checked")) {
            $(".email-select").prop("checked", true);
        } else {
            $(".email-select").prop("checked", false);
        }
    });
    
    function getSelected() {
        var ids = [];
        
        $(".email-select:checked").each(function() {
            ids.push($(this).data("id"));
        });
        
        return ids;
    }
    
    $("[data-email-action=mark-read]").click(function() {
        var ids = getSelected();
        
        if (ids.length == 0) {
            return;
        }
        
        $.post(ajaxUrl, {
            action: "email_read",
            ids: ids.join(",")
        }, function() {
            $(".email-select:checked").prop("checked", false);
            
            ids.forEach(function(id) {
                $("[data-id=" + id + "]").parent().parent().removeClass("unread");
            });
        });
    });
    
    $("[data-email-action=delete]").click(function() {
        var ids = getSelected();
        
        if (ids.length == 0) {
            return;
        }
        
        $.post(ajaxUrl, {
            action: "email_delete",
            ids: ids.join(",")
        }, function() {
            
            ids.forEach(function(id) {
                $("[data-id=" + id + "]").parent().parent().remove();
            });
        });
    });
    
    $("#emailContentArea a").click(function(e) {
        $(this).attr("href", window.location.href + "&redirect=" + encodeURIComponent($(this).attr("href")));
    });
    
    if ($("#compose").length) {
        $("#recipient_product").select2({
			dropdownParent: $("#compose")
		});
        
        $("#recipient_individual_user").select2({
			dropdownParent: $("#compose")
		});
		
		$("#recipient_individual_type").change(function() {
		    switch ($(this).val()) {
		        case "email":
		        case "sms":
		            $("#recipient_individual_name").parents(".form-group").show();
		            $("#recipient_individual_email").parents(".form-group").show();
		            $("#recipient_individual_user").parents(".form-group").hide();
		            break;
		        case "user":
		            $("#recipient_individual_name").parents(".form-group").hide();
		            $("#recipient_individual_email").parents(".form-group").hide();
		            $("#recipient_individual_user").parents(".form-group").show();
		            break;
		    }
		}).change();
		
		$("#email_recipient_type").change(function() {
		    var recipientTypes = {
		        "all": [],
		        "group": ["recipient_group"],
		        "product": ["recipient_product"],
		        "individual": ["recipient_individual_type", "recipient_individual_name", "recipient_individual_email", "recipient_individual_user"]
	        };
	        
	        for (var type in recipientTypes) {
	            recipientTypes[type].forEach(function (field) {
	                $("#" + field).parents(".form-group").hide();
	            });
	        }
	        
            recipientTypes[$(this).val()].forEach(function (field) {
                $("#" + field).parents(".form-group").show();
            });
            
            if ($(this).val() == "individual") {
                $("#recipient_individual_type").change();
            }
		}).change();
		
		if (!window.isSms) {
    		tinymce.init({ selector:'#body', menubar: false, statusbar: false,
    		    plugins: "link",
                setup: function (editor) {
                    editor.on('change', function () {
                        editor.save();
                    });
                }
            });
		}
        
        var submitting = false;
        
        function checkForErrors() {
        	var hasError = false;
        	
        	$("#compose").find("select, input, textarea").each(function() {
        		if ($(this).is(":visible")) {
    			    hasError = hasError || !$(this).val();
        		}
        	});
        	
        	if (!window.isSms) {
        	    hasError = hasError || !tinymce.get("body").getContent();
        	}
        	
        	return hasError;
        }
        
        setInterval(function() {
            if (submitting || checkForErrors()) {
                $("#compose").find("button").prop("disabled", true);
            } else {
                $("#compose").find("button").prop("disabled", false);
            }
        }, 500);
        
        $("#compose").find("button").click(function() {
            if (!checkForErrors()) {
                submitting = true;
                
                $("#compose").find("input, select, textarea").prop("disabled", true);
                
                if (!window.isSms) {
                    tinymce.get("body").setMode('readonly');
                }
                
                $.post(ajaxUrl, {
                    action: window.isSms ? "send_sms" : "send_email",
                    email_recipient_type: $("#email_recipient_type").val(),
                    sms_recipient_type: $("#email_recipient_type").val(),
                    recipient_group: $("#recipient_group").val(),
                    recipient_product: $("#recipient_product").val(),
                    recipient_individual_type: $("#recipient_individual_type").val(),
                    recipient_individual_name: $("#recipient_individual_name").val(),
                    recipient_individual_email: $("#recipient_individual_email").val(),
                    recipient_individual_sms: $("#recipient_individual_email").val(),
                    recipient_individual_user: $("#recipient_individual_user").val(),
                    subject: $("#subject").val(),
                    body: $("#body").val(),
                }, function (response) {
                    if (response.trim() != "OK") {
                        alert("Error: " + response);
                    } else {
                        
                        if (window.isSms) {
                            alert("SMS sent.");
                            window.location.href = "/my-account/sent-sms";
                        } else {
                            alert("Email sent.");
                            window.location.href = "/my-account/sent";
                        }
                    }
                    
                    submitting = false;
                    $("#compose").find("input, select, textarea").prop("disabled", false);
                    tinymce.get("body").setMode('design');
                });
            }
        });
    }
});

function search() {
    $("#emailSearchForm").submit();
}