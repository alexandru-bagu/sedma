<?php
if(isset($_GET['ajax']))
{
	$CONFIG = "TRUE";
	require('config.php');
	connect_sql();
}

try 
{
	if(isset($_SESSION['user']))
	{
		$res = query("select * from game_cards where username='" . $_SESSION['user'] . "'");
	
		if($row = fetch_assoc($res))
		{
			echo '<a class="link" style="background-image: url(images/icons/continue.png);" href="index.php?page=game">Continue game </a>';	
			echo '<a class="link" style="background-image: url(images/icons/forfeit.png);" href="index.php?page=forfeit">Forfeit game </a>';	
		}
		else
		{	
			echo '<a class="link" style="background-image: url(images/icons/join.png);" href="index.php?page=game_join">Join game</a>';
			echo '<a class="link" style="background-image: url(images/icons/create.png);" href="index.php?page=game&type=new">Create game</a>';
		}
		echo '<a class="link" style="background-image: url(images/icons/account.png);" href="index.php?page=account">Account</a>';
		echo '<a class="link" style="background-image: url(images/icons/history.png);" href="index.php?page=history">History</a>';
	}
}
finally
{
	if(isset($_GET['ajax']))
	{
		close_sql();
	}
}
?>
<a class="link" style="background-image: url(images/icons/contact.png);" href="index.php?page=contact">Contact</a>