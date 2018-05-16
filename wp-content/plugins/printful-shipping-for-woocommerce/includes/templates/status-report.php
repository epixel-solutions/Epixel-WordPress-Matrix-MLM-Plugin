<div class="support-report-wrap">
	<p>Copy the box content below and add it to your support message</p>
	<textarea class="support-report"><?php echo esc_html($status_report); ?></textarea>
	<button class="button button-primary button-large support-report-btn">Copy</button>
	<script type="text/javascript">
        var copyTextareaBtn = document.querySelector('.support-report-btn');

        copyTextareaBtn.addEventListener('click', function() {
            var copyTextarea = document.querySelector('.support-report');
            copyTextarea.select();

            try {
                document.execCommand('copy');
            } catch (err) {
                //do nothing
            }
        });
	</script>
</div>