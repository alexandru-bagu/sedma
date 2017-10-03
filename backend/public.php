<?php
if(!isset($_SESSION['user']))
{
	redirect("index.php");
    return;
}

if(isset($_GET['v']))
{
    $user = $_SESSION['user'];
    $res = query("select * from game_cards where username='$user'");
    if($myData = fetch_assoc($res))
    {
        $id = $myData['game_id'];
        $value = intval(escape($_GET['v']));
        query("update game set public=$value where id=$id");
    }
    redirect('index.php?page=game');
}
?>