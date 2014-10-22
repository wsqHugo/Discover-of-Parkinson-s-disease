$(function() 
{
	//help function
	//check password validity
	function string_check(string,length)
	{
		for(var i=0;i<length;i++)
		{
			if((string[i]>='a'&&string[i]<='z')||(string[i]>='A'&&string[i]<='Z')||string[i]>='0'&&string[i]<='9')
				continue;
			else
				return false;
		}
		return true;
	}
	
	//compare two password
	function check_password(pass1, pass2)
	{
		if(pass1.val().length==0&&pass2.val().length==0)
		{
			$("#password_signup_check_1").html('');
			pass1.removeClass( "ui-state-error" );
			remove_error_msg("#signup_error_password_1");
			
			$("#password_signup_check_2").html('');
			pass2.removeClass( "ui-state-error" );
			remove_error_msg("#signup_error_password_2");
			return;
		}
		else if (pass1.val().length==0)
		{
			$("#password_signup_check_1").html('');
			pass1.removeClass( "ui-state-error" );
			remove_error_msg("#signup_error_password_1");
			
			error_msg("#signup_error_password_2",'Two passwords are not same!');
			pass2.addClass( "ui-state-error" );
			$("#password_signup_check_2").html('<img src="img/check_error.gif" alt="check_error"/>');
			return;
		}
		else if (pass2.val().length>=0)
		{
			if(pass1.val().length<6 || pass1.val().length>20)
			{
				error_msg("#signup_error_password_1",'Password: 6~20 characters!');
				pass1.addClass( "ui-state-error" );
				$("#password_signup_check_1").html('<img src="img/check_error.gif" alt="check_error"/>');
			}
			else if(string_check(pass1.val(),pass1.val().length)==false)
			{
				error_msg("#signup_error_password_1",'Only accept: a~z, A~Z, 0~9!');
				pass1.addClass( "ui-state-error" );
				$("#password_signup_check_1").html('<img src="img/check_error.gif" alt="check_error"/>');
			}
			else
			{
				remove_error_msg("#signup_error_password_1");
				pass1.removeClass( "ui-state-error" );
				$("#password_signup_check_1").html('<img src="img/check_right.gif" alt="check_right"/>');
			}
			
			if (pass2.val().length==0)
			{
				$("#password_signup_check_2").html('');
				pass2.removeClass( "ui-state-error" );
				remove_error_msg("#signup_error_password_2");
				return;
			}
			else if(pass1.val()!=pass2.val())
			{
				error_msg("#signup_error_password_2",'Two passwords are not same!');
				pass2.addClass( "ui-state-error" );
				$("#password_signup_check_2").html('<img src="img/check_error.gif" alt="check_error"/>');
				return;
			}
			else
			{
				remove_error_msg("#signup_error_password_2");
				pass2.removeClass( "ui-state-error" );
				$("#password_signup_check_2").html('<img src="img/check_right.gif" alt="check_right"/>');
				return;
			}
		}
	}
	//error msg help function
	function error_msg(id,msg)
	{
		$(id).html('<span class="ui-icon ui-icon-alert style_error_text" ></span>'+ msg);
		$(id).addClass( "ui-corner-all ui-state-error style_error_box" );
	}
	
	function remove_error_msg(id)
	{
		$(id).html('');
		$(id).removeClass( "ui-corner-all ui-state-error style_error_box" );
	}
	
	function ajax_fail(id)
	{
		$(id).html("<div id='loading_error'></div>");
		error_msg("#loading_error","Connection Failure!");
		$(id).dialog( "option", "dialogClass", "process_dialog_show" );
		$(id).dialog("open");
	}
	
	//get form by ajax
	function get_form(formType,title,width)
	{
		$("#form_container")
			.html("<div class='loading_dialog'><img src='img/loader-earth.gif' alt='Loading'/><span> Loading</span></div>")
			.dialog( "option", "dialogClass", "process_dialog" )
			.dialog( "option", "title", "" )
			.dialog( "option", "width", 352 )
			.dialog( "option", "position", { my: "center", at:"center",of: window} )
			.dialog("open");
			
		$.ajax
		({
			type: "POST",
			url: "accountForm.php",
			data:{formType: formType},
			success: function(msg)
				{
					$("#form_container").html(msg);
					jqueryButton();
					$("#form_container")
						.dialog( "option", "title", title )
						.dialog( "option", "width", width )
						.dialog( "option", "dialogClass", "process_dialog_show" )
						.dialog( "option", "position", { my: "center", at:"center",of: window} );
					$(".Captcha_Img").attr("src","CAPTCHA.php?"+Math.random());
				},
			error: function()
				{
					ajax_fail("#form_container");
				}
		});
	}
	
	//initialize the form_container and load_container dialog
	$("#form_container,#load_container").dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		width:352
	});
	
	//if submit, call processing function (helper function)
	function processing(container)
	{
		$("#load_container" )
			.html("<div class='loading_dialog'><img src='img/loader-earth.gif' alt='Processing'/><span> Processing</span></div>")
			.dialog( "option", "dialogClass", "process_dialog" )
			.dialog( "option", "position", { my: "center", at:"center",of: container} )
			.dialog("open");
	}

	function jqueryButton()
	{
		$(".jqueryButton")
		.button
			({
				icons:{primary: "ui-icon-refresh"},
				text:false
			})
	}
	
	jqueryButton();
	
	//get login form by ajax
	$( "#Login" )
		.button()
		.click
		(
			function() {get_form("login","Login",450);}
		);
	
	//get signup form by ajax
	$( "#Sign_up" )
		.button()
		.click
		(
			function() {
				get_form("signup","Signup",480);
			}
		);
	
	$("#Sign_up_request").live(
		"click",
		function() {get_form("signup","Signup",480);}
	);
	
	//get reset password form by ajax
	$(".reset_password").live(
		"click",
		function() 
		{
			get_form("reset password","Reset Password",452);
		}
	);
	
	//login ajax
	$("#form_login").live("submit",function()
	{
		processing("#form_container");

		xmlRequest = $.ajax
		({
			type: "POST",
			url: "account.php", 
			data: {username: $("#username_login").val(), password: $("#password_login").val(), cookie: $("#cookie").is(":checked"), mode: "login"},
			success: function()
					{
						$("input.text").removeClass( "ui-state-error" );
						remove_error_msg("p.text");
						
						a = xmlRequest.responseText;
						if(xmlRequest.responseText==1)
						{
							error_msg("#login_error_username",'Account not exist!');
							$("#username_login").addClass( "ui-state-error" );
						}
						else if(xmlRequest.responseText==2)
						{
							error_msg("#login_error_password",'Wrong password');
							$("#password_login").addClass( "ui-state-error" );
						}
						else
						{
							$("#load_container" ).html("<div class='loading_success'>Login Success</div>")
							setTimeout(function(){window.location.reload();},1000);
							return true;
						};
						$("#load_container").dialog("close");
					},
			error: function()
					{
						ajax_fail("#load_container");
					}
			
		});
		
		return false;
	});
	
	//check username by ajax while signing up
	var time_username_signup;
	$( "#username_signup" ).live
	(
		"keyup",
		function()
		{
			clearTimeout(time_username_signup);
			time_username_signup = 	setTimeout
			(
				function()
				{
					if($( "#username_signup" ).val().length==0)
						return;
					$("#username_signup_check").html("<img src='img/ajax-loader.gif' alt='check'/>");

					$.ajax
					({
						type: "POST",
						url: "account.php",
						data:{username: $( "#username_signup" ).val(), mode: "checkusername"},
						success: function(msg)
							{
								if(msg=="exist")
								{
									error_msg("#signup_error_username",'Username has been used!');
									$("#username_signup").addClass( "ui-state-error" );
									$("#username_signup_check").html('<img src="img/check_error.gif" alt="check_error"/>');
								}
								else if(msg=="notexist")
								{
									remove_error_msg("#signup_error_username");
									$("#username_signup").removeClass( "ui-state-error" );
									$("#username_signup_check").html('<img src="img/check_right.gif" alt="check_right"/>');
								}
								else if(msg=="invalid")
								{
									error_msg("#signup_error_username",'Length 6~20 characters');
									$("#username_signup").addClass( "ui-state-error" );
									$("#username_signup_check").html('<img src="img/check_error.gif" alt="check_error"/>');
								}
							},
						error: function()
								{
									ajax_fail("#load_container");
									$("#username_signup_check").html("");
								}
					});
				},
				1500
			);
		}
	);
	
	//check password
	var time_pass1;
	$( "#pass1" ).live
	(
		"keyup",
		function()
		{
			pass1 = $("#pass1");
			pass2 = $("#pass2");
			clearTimeout(time_pass1);
			time_pass1 = setTimeout
			(
				function()
				{
					$("#password_signup_check_1").html("<img src='img/ajax-loader.gif' alt='check'/>");
					check_password(pass1, pass2);					
				},
				1000
			);
		}
	);
	
	var time_pass2;
	$( "#pass2" ).live
	(
		"keyup",
		function()
		{
			pass1 = $("#pass1");
			pass2 = $("#pass2");
			clearTimeout(time_pass2);
			time_pass2 = setTimeout
			(
				function()
				{
					$("#password_signup_check_2").html("<img src='img/ajax-loader.gif' alt='check'/>");
					check_password(pass1, pass2);
				},
				1000
			);
		}
	);
	
	//check email by ajax
	var time_signup_email
	$( "#signup_email" ).live(
		"keyup",
		function()
		{
			clearTimeout(time_signup_email);
			time_signup_email = setTimeout
			(
				function()
				{
					if($( "#signup_email" ).val().length==0)
						return;
					$("#email_signup_check").html("<img src='img/ajax-loader.gif' alt='check'/>");

					$.ajax
					({
						type: "POST",
						url: "account.php",
						data:{email: $( "#signup_email" ).val(), mode: "checkemail"},
						success: function(msg)
							{
								if(msg=="exist")
								{
									error_msg("#signup_error_email",'Email has been used!');
									$("#signup_email").addClass( "ui-state-error" );
									$("#email_signup_check").html('<img src="img/check_error.gif" alt="check_error"/>');
								}
								else if(msg=="notexist")
								{
									remove_error_msg("#signup_error_email");
									$("#signup_email").removeClass( "ui-state-error" );
									$("#email_signup_check").html('<img src="img/check_right.gif" alt="check_right"/>');
								}
								else if(msg=="invalid")
								{
									error_msg("#signup_error_email",'Email is invalid!');
									$("#signup_email").addClass( "ui-state-error" );
									$("#email_signup_check").html('<img src="img/check_error.gif" alt="check_error"/>');
								}
							},
						error: function()
							{
								ajax_fail("#load_container");
								$("#email_signup_check").html("");
							}
					});
				},
				1500
			);
		}
	);
	
	//check captcha by ajax
	var time_signup_captcha;
	$( "#signup_captcha,#reset_password_captcha" ).live
	(
		"keyup",
		function()
		{
			clearTimeout(time_signup_captcha);
			time_signup_captcha = 	setTimeout
			(
				function()
				{
					$("#captcha_signup_check").html("<img src='img/ajax-loader.gif' alt='check'/>");
					
					$.ajax
					({
						type: "POST",
						url: "account.php",
						data:{captcha: $( ".text_captcha" ).val(), mode: "checkcaptcha"},
						success: function(msg)
							{
								if(msg=="incorrect")
								{
									
									$(".text_captcha").addClass( "ui-state-error" );
									$("#captcha_signup_check").html('<img src="img/check_error.gif" alt="check_error"/>');
								}
								else if(msg=="correct")
								{
									$(".text_captcha").removeClass( "ui-state-error" );
									$("#captcha_signup_check").html('<img src="img/check_right.gif" alt="check_right"/>');
								}
							},
						error: function()
							{
								ajax_fail("#load_container");
								$("#captcha_signup_check").html("");
							}
					});
				},
				1500
			);
		}
	);
	
	//sign up by ajax
	$("#form_signup").live("submit",function()
	{
		processing("#form_container");
		
		xmlRequest = $.ajax
		({
			type: "POST",
			url: "account.php", 
			data: {username: $("#username_signup").val(), password1: $("#pass1").val(),password2: $("#pass2").val(), email: $("#signup_email").val(), type: $("#signup_type").val(), captcha: $("#signup_captcha").val(), mode: "signup", location:window.location.href},
			success:function()
					{
						$("input.text").removeClass( "ui-state-error" );
						$("span.text").html('');
						remove_error_msg("p.text");
						
						if(xmlRequest.responseText==0)
						{
							$("#load_container" ).html("<div class='loading_success'>Welcome to WhiteCoat!</div>")
							setTimeout(function(){window.location.reload();},1000);
							return true;
						}
						else 
						{
							if(xmlRequest.responseText>=8)
							{
								error_msg("#signup_error_password_2",'Passwords are invalid!');
								$("#pass1,#pass2").addClass( "ui-state-error" );
								$("#password_signup_check_1,#password_signup_check_2").html('<img src="img/check_error.gif" alt="check_error"/>');
								xmlRequest.responseText -=8;
							}

							if(xmlRequest.responseText>=4)
							{
								$("#signup_captcha").addClass( "ui-state-error" );
								$("#captcha_signup_check").html('<img src="img/check_error.gif" alt="check_error"/>');
								xmlRequest.responseText -=4;
							}
							else 
							{
								$("#captcha_signup_check").html('<img src="img/check_right.gif" alt="check_right"/>');
							}
							
							if(xmlRequest.responseText>=2)
							{
								error_msg("#signup_error_email",'E-mail is invalid');
								$("#signup_email").addClass( "ui-state-error" );
								$("#email_signup_check").html('<img src="img/check_error.gif" alt="check_error"/>');
								xmlRequest.responseText -=2;
							}
							else
							{
								$("#email_signup_check").html('<img src="img/check_right.gif" alt="check_right"/>');
							}
								
							if(xmlRequest.responseText>=1)
							{
								error_msg("#signup_error_username",'Username is invalid!');
								$("#username_signup").addClass( "ui-state-error" );
								$("#username_signup_check").html('<img src="img/check_error.gif" alt="check_right"/>');
							}
							else
							{
								$("#username_signup_check").html('<img src="img/check_right.gif" alt="check_right"/>');
							}
							$("#pass1,#pass2").val("");
							$("#load_container").dialog("close");
							$(".Captcha_Img").attr("src","CAPTCHA.php?"+Math.random());
						}
					},
			error: function()
					{
						ajax_fail("#load_container");
					}
		});
		return false;
	});
	
	//reset password
	$("#form_reset_password").live("submit",function()
	{
		processing("#form_container");
		
		xmlRequest = $.ajax
		({
			type: "POST",
			url: "account.php", 
			data: {username: $("#reset_password_username").val(), email: $("#reset_password_email").val(), captcha: $("#reset_password_captcha").val(), mode: "reset_password"},
			success:function()
					{
						$("input.text").removeClass( "ui-state-error" );
						remove_error_msg("p.text");
						if(xmlRequest.responseText==0)
						{
							$("#load_container" ).html("<div class='loading_success'>Email send success!</div>")
							setTimeout(function(){window.location.reload();},1000);
							return true;
						}
						else
						{
							if(xmlRequest.responseText>=2)
							{
								error_msg("#reset_password_error",'Username and Email error!');
								$("#reset_password_email,#reset_password_username").addClass( "ui-state-error" );
								xmlRequest.responseText -=2;
							}
								
							if(xmlRequest.responseText>=1)
							{
								$("#reset_password_captcha").addClass( "ui-state-error" );
								$("#captcha_signup_check").html('<img src="img/check_error.gif" alt="check_error"/>');
							}
						}
						$("#load_container").dialog("close");
						$(".Captcha_Img").attr("src","CAPTCHA.php?"+Math.random());
					},
			error: function()
					{
						ajax_fail("#load_container");
					}
			
		});
		
		return false;
	});
	
	//refresh Captcha
	$(".Captcha_Refresh").live
	(
		"click",
		function()
		{
			$(".Captcha_Img").attr("src","CAPTCHA.php?"+Math.random());
		}
	);
	
	$( "#My_Account" )
		.button();
	$( "#Logout" )
		.button()
		.click
		(
			function()
			{
				processing(window);
				$.ajax
				({
					type: "POST",
					url: "account.php",
					data: {mode:"logout"},
					success: function()
						{
							$("#load_container" ).html("<div class='loading_success'>Logout success!</div>");
							setTimeout(function(){window.location.reload();},1000);
						},
					error: function()
						{
							ajax_fail("#load_container");
						}
				})
			}
		);
		
	$("#reset_password_submit")
	.submit(function(){
		processing(window);
	})
});