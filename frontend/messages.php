<?php

if(!isset($_SESSION['user'])) { redirect("index.php?page=login"); return; }
if($_SESSION['is_admin'] == 1)
{
	$res = query("select * from contact");
	$rows = num_rows($res);
	while($row = fetch_assoc($res))
	{
		$id = $row['id'];
		$from = $row['name'];
		$email = $row['email'];
		$message = $row['message'];
		echo "<div>";
		echo "From: " . $from . "<br/>";
		echo "Email: " . $email . "<br/>";
		echo "Message: " . $message . "<br/>";
		echo "<a href=\"index.php?page=messages&delete=$id\">Delete</a></br></br>";
		echo "</div>";
	}
	if($rows == 0)
	{
		echo "There is nothing here.";
	}
}

?>