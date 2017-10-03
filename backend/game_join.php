<?php
	if(!isset($_SESSION['user'])) { redirect("index.php?page=login"); return; }
	if(!isset($_POST['code']) && !isset($_GET['code'])) { return; }
	
	if(isset($_POST['code']))
		$code = escape($_POST['code']);
	else if(isset($_GET['code']))
		$code = escape($_GET['code']);
	
	$user = $_SESSION['user'];
	$res = query("select id,cards,public from game where join_code ='$code'");
	if(!($row = fetch_assoc($res)))
	{
		addError("Code could not be found. It is possibly expired!");
	}
	else
	{
		$id = $row['id'];
		$cards = $row['cards'];
		$public = $row['public'];
		$player1 = fetch_array(query("select username from game_cards where game_id='$id' and username<>'$user'"))[0];
		$player2 = $user;
		if($player1 == $player2)
		{
			addError("You cannot join your own game!");
			return;
		}
		
		$pCards = explode(',', $cards);
		$player1Cards = "";
		$player2Cards = "";
		for($i = 0; $i < 4; $i++)
		{
			$player1Cards = $player1Cards . ',' . $pCards[$i * 2];
			query("insert into action_history (game_id, username, action, value, stage) values ('$id', '$player1', 'draw', '" . $pCards[$i * 2] . "','0')");
			$player2Cards = $player2Cards . ',' . $pCards[$i * 2 + 1];
			query("insert into action_history (game_id, username, action, value, stage) values ('$id', '$player2', 'draw', '" . $pCards[$i * 2 + 1] . "','0')");
		}
		$player1Cards = substr($player1Cards, 1);
		$player2Cards = substr($player2Cards, 1);
		$cards = substr($cards, strlen($player1Cards) + strlen($player2Cards) + 2);

		query("insert into game_cards (username, game_id, cards) values ('$user', $id, '')");
		query("update game set cards='$cards' where id='$id'");
		query("update game_cards set cards='$player1Cards' where username='$player1'");
		query("update game_cards set cards='$player2Cards' where username='$player2'");

		if($public == 1)
		{
			$EMAIL = 1;
			require ('email.php');
			$array = fetch_array(query("select email,nickname from users where username='$user'"));
			$email = $array[0];
			$nickname = $array[1];
			sendEmail($email, $nickname, 'Game started', 
			"A player has joined your public game.<br/><br/>
				Go to <a href=\"81.4.106.74/index.php?page=game\">Join game</a> or manually at <br/>
			<a href=\"81.4.106.74/index.php?page=game\">81.4.106.74/index.php?page=game</a><br/>");
		}
		
		redirect("index.php?page=game");
	}
?>