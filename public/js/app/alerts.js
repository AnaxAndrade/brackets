$(document).ready(function(){
	var a = $('.alert');

	$(a).on('click', '.close', function(ev){
		hideAlert($(this).parent());
	});
});

// Hide all alerts or a specific alert
function hideAlert(elem, speed)
{
	var elem = (elem instanceof jQuery ? elem : $('.alert'));
	var speed = (speed === undefined ? 100 : speed);

	elem.slideUp(speed);
}