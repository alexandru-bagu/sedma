<?php
if(isset($_SESSION['user']))
{
	redirect("index.php");
}
else
{
	if(isset($_POST['submit']) 
		&& strlen($_POST['password1']) > 0
		&& strlen($_POST['password2']) > 0
		&& strlen($_GET['key']) > 0)
	{
		$key = escape($_GET['key']);
		$pw1 = $_POST['password1'];
		$pw2 = $_POST['password2'];
		if($pw1 != $pw2)
		{
			addError("The passwords are different!");
		}
		if(!hasErrors())
		{
			$res = query("select * from password_reset where `key`='$key'");
			if(num_rows($res) != 0)
			{
				$data = fetch_assoc($res);
				$user = $data['username'];
				$salt = generateRandomString();
				$dbPassword = md5($pw1 . $salt);
				query("update users set password='$dbPassword', salt='$salt' where username='$user'");
				query("delete from password_reset where `key`='$key'");
				addError("Password has been successfully reset!", "#882152");
			}
			else
			{
				addError("Key has expired. Request a new password reset!");
			}
		}
	}
	else
	{
		if(isset($_POST['submit']))
		{
			addError("You must fill the entire form.");
		}
	}
}
?>