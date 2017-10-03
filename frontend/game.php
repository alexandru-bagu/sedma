<?php
if(!isset($_SESSION['user'])) return;
$user = $_SESSION['user'];
$res = query("select * from status where username='$user'");

if($row = fetch_assoc($res))
{
	$status = $row['status'];
	$id = $row['id'];
	query("delete from status where id='$id'");
	echo "<div id=\"disableRefresh\"> </div>";
	if($status == "win")
	{
		echo "<h1>You have won!</h1>";
	}
	else if($status == "loss")
	{
		echo "<h1>You have lost!</h1>";
	}
	else 
	{
		echo "<h1>Game has been tied.</h1>";
	}
}

?>