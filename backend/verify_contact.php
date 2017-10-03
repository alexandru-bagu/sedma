<?php
if(isset($_POST['submit']) 
	&& isset($_POST['name']) && strlen($_POST['name']) > 0 
	&& isset($_POST['email']) && strlen($_POST['email']) > 0
	&& isset($_POST['message']) && strlen($_POST['message']) > 0
	&& isset($_POST['captcha']) && strlen($_POST['captcha']) > 0)
{
	$name = escape($_POST['name']);
	$email = escape($_POST['email']);
	$message = escape($_POST['message']);
	$captcha = escape($_POST['captcha']);

	if(strtolower($captcha) !== strtolower($_SESSION['captcha']['code']))
	{
		addError("Captcha is not correct!");
	}
	
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
	{
		addError("Email is invalid!");	
	}
	else
	{
		query("insert into contact (name, email, message) values ('$name','$email','$message')");
		addError("Message sent!", "#885500");
		redirect("index.php?page=contact");
	}
}
else
{
	if(isset($_POST['submit']) && isset($_POST['captcha']))
	{
		addError("You need to fill the entire form.");
	}
}
?>