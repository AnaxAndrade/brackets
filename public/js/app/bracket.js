$(document).ready(function(){
	$('body').removeClass('preload');
	if(isMobile()) initMobile();

	// new player form
	$('#createBracketPlayerForm').on('submit', createBracketPlayer);

	// list actions
	$('ol, ul').on('touchclick', '.listRemove', function(ev){
		console.log('werk you dirty bastage');

		var el = $(this);
		var parent = el.parent();
		var itemId = el.data('item-id');
		var uri = '/' + el.data('uri') + '/' + itemId;

		if(el.hasClass('confirm'))
		{
			if( ! confirm(el.data('confirm-msg')))
			{
				return;
			}
		}

		var request = ajaxRequest(uri, false, 'get');

		// when the request is finished, add some data to the dom or remove the player if the addition failed.
		request.done(function(data) {
			if(data.code == 200)
			{
				parent.remove();
			}else{
				alert(data.msg);
			}
		});

		request.fail(function(jqXHR, textStatus) {
			alert( "Request failed: " + textStatus );
		});

		request.always(function(){

		});

		return;
	});

	// Match Flip
	$('.match.ready .front, .match.complete .front').on('touchclick', function(){
		$('.flipped').removeClass('flipped');
		$(this).parent().addClass('flipped');

		return false;
	});
	$('.match .back .close').on('touchclick', function(e){
		$('.flipped').removeClass('flipped');
		e.stopPropagation();
	});

	// Panels
    $('#btnNav').on('touchclick', function(){
    	togglePanel('left');
    	return false;
    });
	// Panels
    $('#btnSettings').on('touchclick', function(){
    	togglePanel('right');
    	return false;
    });

	$('#leftPanelWrapper').hammer().on('swipeleft', function(e) {
    	togglePanel('left');
	});
	$('#rightPanelWrapper').hammer().on('swiperight', function(e) {
    	togglePanel('right');
	});
});

function togglePanel(position)
{
	var el = $('#' + position + 'PanelWrapper');
	var stage = $('#stage');
	var stageOpenClass = 'shift' + position;

	var btn = $('#btnNav');

	el.toggleClass('open');
	stage.toggleClass(stageOpenClass);
}

function createBracketPlayer(ev)
{
	ev.preventDefault();		// don't submit.

	var form = $(this);
	var data = form.serialize();
	var url = form.attr('action');
	var name = $('input[name=playerName]', form).val();
	var list = $('#playerList ol');

	if( ! name) return;
	
	// add player to dom immediately. 
	var nameArr = name.split(' ');
	var player = {
		first_name : nameArr[0],
		last_name : nameArr[1] ? nameArr[1] : ''
	};

	// new elem
	var newElem = $('<li>');
	// don't show the remove button until we've confirmed the player has been saved.
	newElem.append('<button class="listOpt listRemove confirm" data-confirm-msg="Are you sure you want to remove this player?" data-uri="bracket/delete_player">Remove</button>'); 
	newElem.append('<h3>' + player.first_name + ' ' + player.last_name + '</h3>');
	var removeBtn = $('button.listRemove',newElem);
	removeBtn.hide();

	$('.emptyListMsg', list).hide();
	$('input[type=text]', form).focus().val('');

	// append to the player list
	list.append(newElem);

	var request = ajaxRequest(url, data, 'post');

	// when the request is finished, add some data to the dom or remove the player if the addition failed.
	request.done(function(data) {
		var res = data.attributes;

		if(res)
		{
			// let's add the new player ID to the dome and show the "remove player" button
			removeBtn.data('item-id', res.id).show();


		}else{ 
			// if there is no result, let's remove 
			// the player from the list and alert the user.
			if(list.children('li').length == 1) $('.emptyListMsg', list).show(); // show empty message if this is the last player.
			newElem.remove();

			alert('There was a system error adding ' + name);
		}

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

	$('a').on('touchclick', function(){
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
	return navigator.userAgent.match(/(iPhone|iPod|iPad|Android|BlackBerry|Mobile)/);
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
