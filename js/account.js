$(document).ready(function () {
	$(document).on('click', 'button', function () {
		var b = $(this);
		if (b.html() != 'Done!') {
			b.html('Done!');
			b.css({
				backgroundColor: '#EEEEEE',
				color: '#444'
			});
			b.animate({
				top: '-3px'
			}, 30, function () {
				b.animate({
					top: 0
				}, 50);
			});
			$.ajax({
				url: '/php/checkin.php',
				data: {
					checkin: true,
					x: b.data('x'),
					y: b.data('y')
				},
				type: 'post'
			});
		}
	});
});