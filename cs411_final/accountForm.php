<?php
Switch($_REQUEST['formType'])
{
case "login":
?>
		<form id="form_login" method="POST" action="#" >
			<div class="style_form">
				<div class="group">
					<label class="text" for="username_login">Username</label>
					<input id="username_login" class="text ui-widget-content ui-corner-all" type="text" name ="username" placeholder="Username/E-mail" required />
					<label class="text"></label>
					<p id="login_error_username" class="text"></p>
				</div>
				<div class="group">
					<label class="text" for="password_login">Password</label>
					<input id="password_login" class="text ui-widget-content ui-corner-all" type="password" name = "password" placeholder="Password"  required />
					<label class="text"></label>
					<p id="login_error_password" class="text"></p>
				</div>
				<div>
					<input id="cookie" type="checkbox" name="cookie"  />
					<label for="cookie"> Remember me on the computer</label></br>&nbsp&nbsp&nbsp&nbsp
					<!--<a class="reset_password" >Forget Password?</a>-->
				</div>
			</div>
			<div id="jqueryButtonDiv">
				<input type="submit" value="Login" class="jqueryButton"/>
				<input type="button" value="Sign up" class="jqueryButton" id="Sign_up_request"/>
			</div>
		</form>
<?php
	break;

case "signup":
?>
		<form id="form_signup"  method="POST" >
			<div>
				<div class="group">
					<label class="text" for="username_signup">Username</label>
					<input id="username_signup" class="text ui-widget-content ui-corner-all" type="text" name ="username" placeholder="Username"   required />
					<span id="username_signup_check" class="text" ></span>
					<label class="text"></label>
					<p id="signup_error_username" class="text"></p>
				</div>
				<div class="group">
					<label class="text" for="pass1">Password</label>
					<input id="pass1" class="text ui-widget-content ui-corner-all" type="password" name = "passcode1" placeholder="6-18 Characters" required  />
					<span id="password_signup_check_1" class="text" ></span>
					<label class="text"></label>
					<p id="signup_error_password_1" class="text"></p>
				</div>
				<div class="group">
					<label class="text" for="pass2">Password</label>
					<input id="pass2" class="text ui-widget-content ui-corner-all" type="password" name = "passcode2" placeholder="Type it again"  required />
					<span id="password_signup_check_2" class="text" ></span>
					<label class="text"></label>
					<p id="signup_error_password_2" class="text"></p>
				</div>
				<div class="group">
					<label class="text" for="signup_email">E-mail</label>
					<input id="signup_email" class="text ui-widget-content ui-corner-all" type="email" name ="signup_email" placeholder="E-mail" required />
					<span id="email_signup_check" class="text" ></span>
					<label class="text"></label>
					<p id="signup_error_email" class="text"></p>
				</div>
				<div class="group">
					<label class="text" for="signup_type">Type</label>
					<select id="signup_type" class="select ui-widget-content ui-corner-all" name="type">
  						<option value="1">Potential Patient</option>
					  	<option value="2">Physician</option>
					</select>
				</div>
				<div class="group">
					<label class="Captcha_Label" for="signup_captcha"><img class="Captcha_Img" src=""/></label>
					<input id="signup_captcha" type="text" class="text_captcha ui-widget-content ui-corner-all" name="signup_captcha" maxlength="5"  required>
					<span class="Captcha_Refresh jqueryButton"></span>
					<span id="captcha_signup_check" class="text" ></span>
				</div>
			</div>
			<div id="jqueryButtonDiv">
				<input type="submit" value="Submit" class="jqueryButton"/>
			</div>
		</form>

<?php
	break;

case "reset password":
?>
		<form id="form_reset_password">
			<div>
				<div class="group">
					<label class="text" for="reset_password_username">Username</label>
					<input id="reset_password_username" class="text ui-widget-content ui-corner-all" type="text" name ="username" placeholder="Username"   required />
					<label class="text"></label>
				</div>
				<div class="group">
					<label class="text" for="reset_password_email">E-mail</label>
					<input id="reset_password_email" class="text ui-widget-content ui-corner-all" type="email" name ="reset_password_email" placeholder="E-mail" required />
					<label class="text"></label>
					<p id="reset_password_error" class="text"></p>
				</div>
				<div class="group">
					<label  class="Captcha_Label" for="reset_password_captcha"><img  class="Captcha_Img" src=""/></label>
					<input id="reset_password_captcha" class="text_captcha ui-widget-content ui-corner-all" type="text" name="reset_password_captcha" maxlength="5"  required>
					<span class="Captcha_Refresh jqueryButton"></span>
					<span id="captcha_signup_check" class="text" ></span>
				</div>
			</div>
			<div id="jqueryButtonDiv">
				<input type="submit" value="Send" class="jqueryButton"/>
			</div>
		</form>
<?php
	break;
	
default:
	print "error";
}
?>