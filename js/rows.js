function customKeyDown (event){
	//if ENTER
	if (event.which == 13){
		$target = $(event.target);
		$window = $(event.target).parents('.window');
		$window.find('.output').append('<p><b>'+$window.find('.prompt').html()+'</b> '+$target.val()+'</p>');
		input = '{"command":"' + $target.val().replace(/=/gi,'":"').replace(/\s/gi,'","') + '"}';
		console.log(input);
		inputObj = JSON.parse(input);
		console.log(inputObj);
		$.post('php/commands.php',{input:$target.val()}, function(data){
			$window.find('.output').append('<p>'+data+'</p>'); //TEMP. data should be an object. function must handle response object.
		});
		$target.val('');
	}
}

function windowClickHandler(event){
	$(event.target).find('.userInput').focus();
	$(event.target).parents('.window').find('.userInput').focus();
}

$(function() {
	$('.userInput').keydown(customKeyDown);
	$('.window').click(windowClickHandler);
});
