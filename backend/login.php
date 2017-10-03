<?php
if(isset($_SESSION['user']))
{
	redirect('index.php');
}
else
{
	if(isset($_POST['submit']) && strlen($_POST['username']) > 0 && strlen($_POST['password']) > 0)
	{	
		$username = escape($_POST['username']);
		$password = $_POST['password'];
		$res = query("select * from users where username ='$username'");
		if($data = fetch_assoc($res))
		{
			$salt = $data['salt'];
			if(md5($password . $salt) === $data['password'])
			{
				$_SESSION['user'] = $username;
				$_SESSION['name'] = $data['nickname'];
				$_SESSION['is_admin'] = $data['is_admin'] == 1;
				redirect("index.php");
				return;
			}
			else
			{
				addError("Invalid username or password!");
			}
		}
		else
		{
			addError("Invalid username or password!");
		}
	}
}
?>