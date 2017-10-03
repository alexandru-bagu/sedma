<?php
	if(isset($_SESSION['user']))
	{
		redirect("index.php");
	}
	else
	{
		if(isset($_POST['submit']) 
			&& strlen($_POST['username']) > 0 
			&& strlen($_POST['password']) > 0
			&& strlen($_POST['password2']) > 0
			&& strlen($_POST['nickname']) > 0
			&& strlen($_POST['email']) > 0
			//&& strlen($_POST['captcha']) > 0
			)
		{
			$username = escape($_POST['username']);
			$nickname = escape($_POST['nickname']);
			$password = $_POST['password'];
			$password2 = $_POST['password2'];
			$email = escape($_POST['email']);
			//$captcha = escape($_POST['captcha']);
			$pwmail = isset($_POST['pwmail']);
			$res = query("select * from users where username ='$username' or email='$email'");
			if($data = fetch_row($res))
			{
				addError("Username or email already in use!");
			}
			query("delete from inactive_users where username ='$username' and datediff(now(), register_date) > 1");
			$res = query("select * from inactive_users where username ='$username'");
			if($data = fetch_row($res))
			{
				addError("Username already in use!");
			}
			if($password !== $password2)
			{
				addError("Passwords are different!");
			}
			//if(strtolower($captcha) !== strtolower($_SESSION['captcha']['code']))
			//{
			//	addError("Captcha is not correct!");
			//}
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
			{
				addError("Email is invalid!");	
			}
			if(!hasErrors())
			{
				$salt = generateRandomString();
				$dbPassword = md5($password . $salt);
				
				query("insert into inactive_users (username, password, salt, email, nickname) values ('$username', '$dbPassword', '$salt', '$email', '$nickname')");
				$code = generateRandomString(64);
				query("insert into activation_code (username, code) values ('$username', '$code')");
				$EMAIL = 1;
				require ('email.php');
				$pw = "Password:$password<br/>";
				if($pwmail == false) $pw = "";
				if(sendEmail($email, $nickname, 'Account activation', 
				"This website requires you to activate your account before being able to use it.<br/>
				Username: $username<br/>" . $pw . "				
				Activation code:$code<br/>
					Go to <a href=\"digitaldevt.com/septica/index.php?page=activate&code=$code\">Activate account</a> or manually at <br/>
				<a href=\"digitaldevt.com/septica/index.php?page=activate\">digitaldevt.com/septica/index.php?page=activate</a><br/> to activate the account.<br/><br/>The activation code can expire in 1 day!"))
				{
					addError("An email has been sent! Check your inbox for further details. (It might be in the spam folder.)", "#882152");
				}
				else
				{
					addError("Could not send the email. Contact the server administration.");
				}
			}
		}
		else
		{
			if(isset($_POST['submit']))
			{
				addError("You need to fill the entire form.");
			}
		}
	}
?>