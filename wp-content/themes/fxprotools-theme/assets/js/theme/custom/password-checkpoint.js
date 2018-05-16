$(document).ready(function () {
    var ajax_url = fx.ajax_url;
    var $overlay = $('.fx-renewal .overlay');
    var $response = $('.fx-renewal .ajax-response');
    var password_strong = false;
    var password_match = false;

    $('.fx-renewal form').on('submit', function (e) {
        e.preventDefault();
        $overlay.show();

        var data = {
            'action': 'fx_renew_password',
            'fx_action': 'renew_password',
            'new_password': $('.fx-renewal #pwd').val(),
            'confirm_password': $('#pwd-verify').val()
        };

        var redirect_to = ( $('.fx-renewal #redirect_to').val() ) ? $('.fx-renewal #redirect_to').val() : '/dashboard';

        $.post(ajax_url, data, function () {
            $overlay.hide();
        }).done(function (response) {
            if (response.success) {
                var count = 3;
                var message = '<h2>Password successfully updated!</h2><p>You will be redirected in <strong>%s</strong> seconds.</p>';
                $response.html(message.replace(/%s/g, count)).show();
                var countdown = setInterval(function () {
                    if (count == 1) {
                        message = message.replace(/seconds/g, 'second');
                    }
                    $response.html(message.replace(/%s/g, count));
                    if (count == 0) {
                        clearInterval(countdown);
                        window.open(redirect_to, "_self");

                    }
                    count--;
                }, 1000);

            } else {
                $response.html('<h2>Password update failed. Refresh this page and please try again.</h2>').show();
            }
        });

        return false;
    });

    function checkForSubmission () {
        if (password_strong && password_match) {
            $('.fx-renewal button[type="submit"]').prop('readonly',false).prop('disabled', false);
        } else {
            $('.fx-renewal button[type="submit"]').prop('readonly',true).prop('disabled', true);
        }
    }

    function matchPasswords () {
        var $verify_password = $('#pwd-verify');
        var $main_password = $('.fx-renewal #pwd');
        var $verify_password_icon = $('#pwd-icon-verify');

        if ($verify_password.val() == '') {
            input_class = 'has-feedback ';
            icon_class='';
            password_match = false;
        }
        else if ( $main_password.val() == $verify_password.val()) {
            input_class = 'has-feedback has-success';
            icon_class='glyphicon-ok';
            password_match = true;
        } else {
            input_class = 'has-feedback has-error';
            icon_class = 'glyphicon-remove';
            password_match = false
        }
        $verify_password.parent('.has-feedback').removeClass(function (index, className) {
            return (className.match (/(^|\s)has-\S+/g) || []).join(' ');
        }).addClass(input_class);
        $verify_password_icon.removeClass(function (index, className) {
            return (className.match (/(^|\s)glyphicon-\S+/g) || []).join(' ');
        }).addClass(icon_class);
    }

    $('#pwd-verify').on('keyup', function() {
        matchPasswords ();
        checkForSubmission ();
    });

    var options = {};
    options.common = {
        onLoad: function () {
            $('.password-verdict').text('Start typing password');
        },
        onScore: function (options, word, totalScoreCalculated) {
            var icon, class_name;
            if ((totalScoreCalculated === undefined || totalScoreCalculated <= options.ui.scores[3]) ||
                (options.instances.errors != undefined && options.instances.errors.length > 0)) {
                icon = 'glyphicon-remove';
                class_name = 'has-feedback has-error';
                password_strong = false;
            } else {
                icon = 'glyphicon-ok';
                class_name = 'has-feedback has-success';
                password_strong = true;
            }
            $('.fx-renewal #pwd').parent('.has-feedback').removeClass(function (index, className) {
                return (className.match (/(^|\s)has-\S+/g) || []).join(' ');
            }).addClass(class_name);
            $('#pwd-icon').removeClass(function (index, className) {
                return (className.match (/(^|\s)glyphicon-\S+/g) || []).join(' ');
            }).addClass(icon);

            matchPasswords ();

            checkForSubmission ();

            // Fall back to the score that was calculated by the rules engine.
            // Must pass back the score to set the total score variable.
            return totalScoreCalculated;
        },
        maxChar: 50
    };
    options.ui = {
        showVerdicts: false,
        showProgressBar: false,
        showErrors: true,
        showPopover: true,
        // showStatus: true,
        popoverError: function (options) {
            var  message = "<div><strong>Oops!</strong><ul class='error-list' style='margin-bottom: 0;'>";

            jQuery.each(options.instances.errors, function (idx, err) {
                message += "<li>" + err + "</li>";
            });
            message += "</ul></div>";
            return message;
        },
        popoverPlacement: 'top'
    };

    options.rules = {
        activated: {
            wordNotEmail: true,
            wordMinLength: true,
            wordMaxLength: false,
            wordInvalidChar: false,
            wordSimilarToUsername: true,
            wordSequences: true ,
            wordTwoCharacterClasses: false,
            wordRepetitions: false,
            wordLowercase: false,
            wordUppercase: false,
            wordOneNumber: false,
            wordThreeNumbers: false,
            wordOneSpecialChar: false,
            wordTwoSpecialChar: false,
            wordUpperLowerCombo: false,
            wordLetterNumberCombo: false,
            wordLetterNumberCharCombo: false
        }
    };

    if (jQuery.fn.pwstrength != undefined) {
        $('.fx-renewal #pwd').pwstrength(options);
    }

});
