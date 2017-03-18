$(document).ready(function(){
	$('.check-box button').click(function(){
		var b = $(this);
		b.html('Done!');
		b.css({
			backgroundColor: '#EEEEEE',
			color: '#444'
		});
		b.animate({
			top: '-3px'
		}, 30, function(){
			b.animate({
				top: 0
			}, 50);
		});
		$.ajax({
			url: '/php/checkin.php',
			data: {
				checkin: true
			},
			type: 'post'
		});
	});
});