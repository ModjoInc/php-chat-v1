$(document).ready( function(){
	$.ajax({
		url: "chatLog.php",
		timeout: 1000
		})
	.done(function(data){
		$('#chatLog').html("coucou");
		console.log('test');
	});
});

