<?php
	if(!isset($_SESSION['user'])) { redirect("index.php?page=login"); return; }
	$user = $_SESSION['user'];
	$res = query("select game_id from game_cards where username='$user'");
	if($row = fetch_array($res))
	{
		$id = $row[0];
		if(fetch_assoc(query("select count(*) from game_cards where id='$id'"))[0] > 1)
		{
			$winArray = fetch_array(query("select username, points from game_cards where game_id='$id' and username <> '$user'"));
			$winner = $winArray[0];
			$winnerPoints = $winArray[1];

			$lossArray = fetch_array(query("select username, points from game_cards where game_id='$id' and username <> '$winner'"));
			$loser = $lossArray[0];
			$loserPoints = $lossArray[1];
			
			query("delete from game where id='$id'");
			query("delete from game_cards where game_id='$id'");
			
			query("update statistics set wins = wins + 1 where username='$winner'");
			query("update statistics set loss = loss + 1 where username='$loser'");
			query("delete from status where usernane='$winner' or username='$loser'");

			query("insert into status (username, status) values ('$winner', 'win')");
			query("insert into status (username, status) values ('$loser', 'loss')");
			
			query("insert into game_history (username, status, points) values ('$winner', 'win', $winnerPoints)");
			query("insert into game_history (username, status, points) values ('$loser', 'loss', $loserPoints)");
		}
		else
		{
			query("delete from game where id='$id'");
			query("delete from game_cards where game_id='$id'");
		}
	}
	redirect("index.php?page=game");
?>