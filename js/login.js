$(document).ready(function(){
	$('.login form').submit(function(e){
		e.preventDefault();
		var data = $(this).serialize();
		$.ajax({
			url: '/php/login.php',
			data: data,
			type: "POST",
			success: function(msg){
				console.log(msg);
				if (msg == 'yes'){
					window.location.href = '/account';
				}else{
					$('.error').show();
				}
			}
		});
	});
});