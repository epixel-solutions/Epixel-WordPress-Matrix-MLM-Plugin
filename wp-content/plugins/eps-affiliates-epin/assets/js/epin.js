jQuery(document).ready(function($){
    $(".copy-pin").click(function() {
		var $temp = $("<input>");
		$("body").append($temp);
		var $spanid = $(this).closest('tr').find('td:eq(2) span').attr('id');
		$spanid = '#'+$spanid;
		$temp.val($($spanid).text().trim()).select();
		document.execCommand("copy");
		$temp.remove();
		return false;
    });
});
