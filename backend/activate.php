<?php
if(isset($_SESSION['user']))
{
	redirect("index.php");
}
else
{
	if((isset($_GET['code']) && strlen($_GET['code']) > 0) || (isset($_POST['code']) && strlen($_POST['code']) > 0))
	{
        if(isset($_GET['code']))
		    $code = escape($_GET['code']);
        else
            $code = escape($_POST['code']);
        
        $res = query("select * from activation_code where `code`='$code'");
        if(num_rows($res) != 0)
        {
            $data = fetch_assoc($res);
            $username = $data['username'];
            query("insert into users (select * from inactive_users where username='$username')");
            query("delete from inactive_users where username='$username'");
            query("delete from activation_code where `username`='$username'");

            query("insert into statistics (username) values ('$username')");
            $res = query("select * from users where `username`='$username'");
            $data = fetch_assoc($res);
            $_SESSION['user'] = $username;
            $_SESSION['name'] = $data['nickname'];
            $_SESSION['is_admin'] = 0;

			redirect("index.php");
            addError("Account has been activated!", "#882152");
        }
        else
        {
            addError("Code has expired!");
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