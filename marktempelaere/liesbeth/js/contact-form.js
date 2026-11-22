$(document).ready(function() {
	$('#buttonsend').click( function() {

		var email   = $('#email').val();
		var message = $('#message').val();
		
		if (email !== "" && message !== "")
			{
				$.ajax(
					{
						url: '../sendemail.php',
						type: 'POST',
						data: "email=" + email + "&message=" + message,
						success: function(result) 
						{
							if(result === "email_error") {
								$('#email').css({"background":"#FFFCFC","border":"2px solid #ffadad"}).next('.require').text(' !');
							} else {
								$('#email, #message').val("");
								$('<div class="success-contact">Your message has been sent successfully. Thank you! </div>').insertBefore('#contactformarea');
								$('.success-contact').fadeOut(5000, function(){ $(this).remove(); });
							}
						}
					}
				);
				return false;
				
			} 
		else 
			{
				if(email === "" ) {
					$('#email').css({"border":"2px solid #ec008c"});
				}
				if(message === "") {
					$('#message').css({"border":"2px solid #ec008c"});
				}
				return false;
			}
	});

	$('#email,#message').focus(function(){
		$(this).css({"background":"#000"});
	});
 	
});




