
$(document).ready(function(){
	if(isMobile()) initMobile();

	// show winner btn when clicking a side.
	$('#bracket').on('touchclick', '.side.active', toggleSliderWinnerBtn);

	// new player form
	$('#createBracketPlayerForm').on('submit', createBracketPlayer);

	// list actions
	$('.listRemove').on('touchclick', function(ev){
		var el = $(this);
		var parent = el.parent();

		if(el.hasClass('confirm'))
		{
			if( ! confirm(el.data('confirm-msg')))
			{
				return;
			}
		}

		// get rid of the LI
		parent.remove();
	});

	var leftPanel = document.getElementById('leftPanelWrapper');
    Hammer(leftPanel).on("swipeleft", function(event) {
    	toggleSideMenu('close');
    });
    Hammer(leftPanel).on("swiperight", function(event) {
    	toggleSideMenu('open');
    });
    $('#leftPanelWrapper .togglePanel').on('touchclick', function(){
    	var el = $(this);
    	if(el.parent().parent().hasClass('open'))
    	{
    		toggleSideMenu('close');
    	}else{
    		toggleSideMenu('open');
    	}
    });
});

function toggleSideMenu(type)
{
	var el = $('#leftPanelWrapper');
	var btn = $('.close', el);
	var type = (type === undefined) ? 'open' : type;

    switch(type)
    {
    	case 'close':
    		el.removeClass('open');
    		btn.addClass('closed').html('&raquo;');
	    	break;
    	case 'open':
	    default:
    		el.addClass('open');
    		btn.removeClass('closed').html('x');
    		break;
    }
}

function createBracketPlayer(ev)
{
	ev.preventDefault();		// don't submit.

	var form = $(this);
	var data = form.serialize();
	var url = form.attr('action');

	if( ! $('input[name=playerName]', form).val()) return;
	
	var request = ajaxRequest(url, data, 'post');

	request.done(function(data) {
		var player = data.attributes;
		var list = $('#playerList');

		var newElem = $('<li>');
		newElem.append('<button class="listOpt listRemove">Remove</button>');
		newElem.append('<h3>' + player.first_name + ' ' + (player.last_name === false ? '' : player.last_name) + '</h3>');
		list.append(newElem);
		
		$('.emptyListMsg', list).hide();

		$('input[type=text]', form).focus().val('');
	});

	request.fail(function(jqXHR, textStatus) {
		alert( "Request failed: " + textStatus );
	});

	request.always(function(){

	});
}

// Initialie mobile settings for displaying the bracket / handling 'mobile only' events.
function initMobile()
{
	window.scrollTo(0, 1);

	$('a').on('click', function(){
		window.location=$(this).attr('href');
		return false;
	});

	iniSlider('tournament', 300, 0, false, function(){
		$("html, body").animate({ scrollTop: 0 }, 100)
	});
}

// show the win button in bracket view.
function toggleSliderWinnerBtn(ev)
{
	ev.preventDefault;

	var el = $(this);
	var btn = $('.winnerBtn', el);
	var parent = el.parent();
	var parentBtns = $('.btn:visible',parent);

	if(btn.is(':visible'))
	{
		btn.hide();
	}else{
		if(parentBtns.length == 1) parentBtns.hide();
		btn.show();
	}
}

// Initialize a slider on an id.
function iniSlider(id, speed, startSlide, autoStart, callback)
{

	var speed = (speed !== undefined ? speed : 400);
	var startSlide = (startSlide !== undefined ? startSlide : 0);
	var autoStart = (autoStart === true ? autoStart : false);
	var callback = (typeof callback === 'function' ? callback : function(a1,a2,a3){});

	window.mySwipe = new Swipe(document.getElementById(id), {
	    startSlide: startSlide,
	    speed: speed,
	    auto: false,
	    callback: function(event, index, elem){
	    	callback(event, index, elem);	
	    }
	});
}

// Is this user device a recognized mobile device (for the most part...)
function isMobile()
{
	return navigator.userAgent.match(/(iPhone|iPod|iPad|Android|BlackBerry)/);
}

// Create a default jquery ajax request
function ajaxRequest(url, data, type)
{
	var url = (url === undefined) ? false : url;
	var data = (data === undefined ? false : data);
	var type = (type === undefined ? 'get' : type);

	if( ! url) return;


	var config = {
		url: url,
		type: type,
		data: data,
		dataType: 'json'
	}
	return $.ajax(config);


}