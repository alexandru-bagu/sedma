<?php
	if(!isset($_SESSION['user'])) { redirect("index.php?page=login"); return; }
	$user = $_SESSION['user'];
	$res = query("select * from game_cards where username='$user'");
	if($row = fetch_assoc($res))
	{
		$_GET['page'] = 'game_continue';
	}

	if(!$row)
	{
		$res = query("select * from status where username='$user'");
		if(!($row = fetch_assoc($res)))
		{
			if(!isset($_GET['type'])) 
			{
				redirect('index.php');
				return;
			}
			$CREATE_NEW_GAME = "TRUE";
			require('game_new.php');
		}
	}
?>