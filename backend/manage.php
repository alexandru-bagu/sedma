<?php
if(!isset($_SESSION['user']))
{
	redirect('index.php');
    return;
}

if(isset($_POST['submit_deactivate']))
{
    if(isset($_POST['username']) && strlen($_POST['username']) > 0)
    {
        $user = escape($_POST['username']);
        if(fetch_array(query("select is_admin from users where username='$user'"))[0] == 0)
        {
            query("insert into inactive_users (select * from users where username='$user')");
            query("delete from users where username='$user'");
            addError("Account $user has been deactivated.", "#882152");
        }
        else
        {
		    addError("You cannot deactivate admin users!");
        }
    }
    else
    {
		addError("You must input an username!");
    }
}

if(isset($_POST['submit_activate']))
{
    if(isset($_POST['username']) && strlen($_POST['username']) > 0)
    {
        $user = escape($_POST['username']);
        query("insert into users (select * from inactive_users where username='$user')");
        query("delete from inactive_users where username='$user'");
        query("delete from activation_code where username='$user'");
        addError("Account $user has been activated.", "#882152");
    }
    else
    {
		addError("You must input an username!");
    }
}
?>