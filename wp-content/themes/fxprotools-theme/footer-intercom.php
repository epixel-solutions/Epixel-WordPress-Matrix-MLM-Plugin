<?php
/**
 * Intercom setting
 */
if (is_user_logged_in() && isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'copyprofitsuccess.com'): ?>
	<?php $user = wp_get_current_user(); ?>
	<script>
        window.intercomSettings = {
            app_id: "tyotu8pw",
            email: "<?= $user->user_email ; ?>",
            user_hash: "<?= CPS_Intercom::get_user_intercom_HMAC($user); ?>"
        };
        (function () {
            var w = window;
            var ic = w.Intercom;
            if (typeof ic === "function") {
                ic('reattach_activator');
                ic('update', intercomSettings);
            } else {
                var d = document;
                var i = function () {
                    i.c(arguments)
                };
                i.q = [];
                i.c = function (args) {
                    i.q.push(args)
                };
                w.Intercom = i;

                function l() {
                    var s = d.createElement('script');
                    s.type = 'text/javascript';
                    s.async = true;
                    s.src = 'https://widget.intercom.io/widget/tyotu8pw';
                    var x = d.getElementsByTagName('script')[0];
                    x.parentNode.insertBefore(s, x);
                }

                if (w.attachEvent) {
                    w.attachEvent('onload', l);
                } else {
                    w.addEventListener('load', l, false);
                }
            }
        })()

	</script>
<?php endif; ?>
