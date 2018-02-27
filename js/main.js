
$('.thumb').click(function(e) {
	var val = $(this).data('value');

	$('.thumb').removeClass('selected');
	if ($(this).hasClass('selected')) {
		$(this).removeClass('selected');
	} else {
		$(this).addClass('selected');
	}
	$('input.rating').val(val);
});