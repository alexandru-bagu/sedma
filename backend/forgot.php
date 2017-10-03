<?php
if(isset($_SESSION['user']))
{
	redirect("index.php");
}
else
{
	if(isset($_POST['submit']) 
		&& strlen($_POST['email']) > 0)
	{
		$email = escape($_POST['email']);
		$res = query("select * from users where email='$email'");
		$data = fetch_assoc($res);
		if(!$data)
		{
			addError("Email could not be found!");
		}
		if(!hasErrors())
		{
			$user = $data['username'];
			query("delete from password_reset where username='$user'");
			$forgotKey = generateRandomString();
			while(num_rows(query("select * from password_reset where `key`='$forgotKey'")) > 0)
				$forgotKey = generateRandomString();
			query("insert into password_reset (username, `key`) values ('$user', '$forgotKey')");
			$EMAIL = 1;
			require ('email.php');
			
			if(sendEmail($email, $data['nickname'], 'Password reset', 
			"You have requested a password reset for <br/>Username: $user<br/>Your reset key is: $forgotKey<br/>
				Go to <a href=\"81.4.106.74/index.php?page=reset&key=$forgotKey\">Reset my password</a> or manually at <br/>
			81.4.106.74/index.php?page=reset&key=$forgotKey .<br/> Have a great day! <br/> --DO NOT REPLY--"))
			{
				addError("An email has been sent! Check your inbox for further details. (It might be in the spam folder.)", "#882152");
			}
			else
			{
				addError("Could not send the email. Contact the server administration.");
			}
			return;
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