<?php
if(!isset($_SESSION['user'])) { redirect("index.php?page=login"); return; }
$user = $_SESSION['user'];
if(isset($_GET['setHover']))
{
	$card = escape($_GET['setHover']);
	query("update game_cards set highlightedCard='$card' where username='$user'");
}
else if(isset($_GET['removeHover']))
{
	$card = escape($_GET['removeHover']);
	query("update game_cards set highlightedCard='-1' where username='$user'");
}
else if(isset($_GET['click']))
{
	$cardIndex = escape($_GET['click']);
	
	$res = query("select cards, game_id, points from game_cards where username = '$user'");
	$row = fetch_assoc($res);
	$id = $row['game_id'];
	$myCards = $row['cards'];
	$myPoints = $row['points'];
	
	$res = query("select cards, table_cards, turn, first_card, contester, can_contest from game where id='$id'");
	$row = fetch_assoc($res);
	$turn = $row['turn'];
	if($turn == $user)
	{
		$tableCards = $row['table_cards'];
		$deck = $row['cards'];
		$firstCard = $row['first_card'];
		$contester = $row['contester'];
		$canContest = $row['can_contest'];
		$split = explode(',', $myCards);
		
		if(count($split) > $cardIndex && $split[$cardIndex] != "")
		{
			$card = $split[$cardIndex];

			if($canContest == 'true')
			{
				$cardType = explode('_', $card)[0];
				$bottomCardType = explode('_', $tableCards)[0];
				if(!($cardType == $bottomCardType || $cardType == '7'))
				{
					return;
				}
				query("insert into action_history (game_id, username, action, value, stage) values ('$id', $user', 'contest', '', '1')");
			}
			
			query("insert into action_history (game_id, username, action, value, stage) values ('$id', '$user', 'put', '$card', '1')");

			if($tableCards == "") 
			{
				$tableCards = $card;
				$firstCard = $contester = $user;
			}
			else 
			{
				$tableCards = $tableCards . "," . $card;
				$cardType = explode('_', $card)[0];
				$bottomCardType = explode('_', $tableCards)[0];
				if($cardType == '7' || $cardType == $bottomCardType)
				{
					$contester = $user;
				}
			}
			
			$myCards = "";
			for($i = 0; $i < count($split); $i++)
			{
				if($i != $cardIndex)
				{
					$myCards = $myCards . $split[$i] . ',';
				}
			}
			
			if(strlen($myCards) > 0)
			{
				$myCards = substr($myCards, 0, strlen($myCards) - 1);
			}
			
			$winPoints = 0;
			$takenCards = 0;
			
			$res = query("select username, cards, points from game_cards where game_id='$id' and username<> '$user'");
			$row = fetch_assoc($res);
			$opp = $row['username'];
			$oppCards = $row['cards'];
			$turn = $opp;
			$canContest = 'false';
			$oppPoints = $row['points'];

			if($firstCard != $user)
			{
				if($contester == $firstCard)
				{
					$tableSplit = explode(',', $tableCards);
					$splitCount = count($tableSplit);
					for($i = 0; $i < $splitCount; $i++)
					{
						$currentCard = $tableSplit[$i];
						$type = explode('_', $currentCard)[0];
						if($type == 'a' || $type == '10')
						{
							$winPoints ++;
						}
						$takenCards ++;
					}
					$tableCards = "";
					$turn = $contester;
				}
				else
				{
					$cards = $row['cards'];
					$cardSplit = explode(',', $cards);
					$splitCount = count($cardSplit);
					$bottomCardType = explode('_', $tableCards)[0];
					for($i = 0; $i < $splitCount; $i++)
					{
						$currentCard = $cardSplit[$i];
						$type = explode('_', $currentCard)[0];
						if($type == $bottomCardType || $type == '7')
						{
							$canContest = 'true';
						}
					}

					if($canContest == 'false')
					{
						$tableSplit = explode(',', $tableCards);
						$splitCount = count($tableSplit);
						for($i = 0; $i < $splitCount; $i++)
						{
							$currentCard = $tableSplit[$i];
							$type = explode('_', $currentCard)[0];
							if($type == 'a' || $type == '10')
							{
								$winPoints ++;
							}
							$takenCards ++;
						}
						$tableCards = "";
						$turn = $contester;
					}
				}
			}
			
			if($tableCards == "" && $deck != "") //someone TOOK THEM ALL!
			{
				$deckSplit = explode(',', $deck);
				$cardsToGive = 4;
				if( strlen($myCards) > 0 )
				{
					$cardsToGive = 4 - count(explode(',', $myCards));
				}
				$total = count($deckSplit);
				if($total < $cardsToGive * 2)
				{
					$cardsToGive = $total / 2;
				}
				
				$deckIndex = 0;
				for($j = 0; $j < $cardsToGive; $j++)
				{
					query("insert into action_history (game_id, username, action, value, stage) values ('$id', '$user', 'draw', '" . $deckSplit[$deckIndex] . "', '1')");
					if($myCards == "")
					{
						$myCards = $deckSplit[$deckIndex];
					}
					else
					{
						$myCards = $myCards . "," . $deckSplit[$deckIndex];
					}
					$deckIndex++;
					
					query("insert into action_history (game_id, username, action, value, stage) values ('$id', '$opp', 'draw', '" . $deckSplit[$deckIndex] . "', '1')");
					if($oppCards == "")
					{
						$oppCards = $deckSplit[$deckIndex];
					}
					else
					{
						$oppCards = $oppCards .  "," . $deckSplit[$deckIndex];
					}
					$deckIndex++;
				}
				
				$deck = "";
				for($i = $deckIndex; $i < count($deckSplit); $i++)
				{
					$deck = $deck . $deckSplit[$i] . ',';
				}
				if(strlen($deck) > 0)
				{
					$deck = substr($deck, 0, strlen($deck) - 1);
				}
			}
			
			if($tableCards == "")
			{
				query("insert into action_history (game_id, username, action, value, stage) values ('$id', '$turn', 'take cards', '$winPoints', '1')");
			}
			query("update game_cards set points = points + $winPoints, cards_taken = cards_taken + $takenCards where username='$turn'");
					
			if(!check_end_game($id))
			{
				query("update game_cards set cards='$myCards' where username='$user'");
				query("update game_cards set cards='$oppCards' where username='$opp'");
				query("update game set cards='$deck', table_cards='$tableCards', turn='$turn', first_card='$firstCard', contester='$contester', can_contest='$canContest' where id='$id'");
			}
		}
	}
}
else if(isset($_GET['no_contest']))
{
	$res = query("select cards, game_id from game_cards where username = '$user'");
	$row = fetch_assoc($res);
	$cards = $row['cards'];
	$id = $row['game_id'];
	
	$res = query("select cards, table_cards, turn, first_card, contester, can_contest from game where id='$id'");
	$row = fetch_assoc($res);
	$turn = $row['turn'];
	$deck = $row['cards'];
	$tableCards = $row['table_cards'];
	$firstCard = $row['first_card'];
	$contester = $row['contester'];
	$canContest = $row['can_contest'];

	$res = query("select username, cards, points from game_cards where game_id='$id' and username<> '$user'");
	$row = fetch_assoc($res);
	$opp = $row['username'];
	$oppCards = $row['cards'];
	$oppPoints = $row['points'];
	
	$winPoints = 0;
	$takenCards = 0;
	
	if($firstCard == $user)
	{
		if($contester != $firstCard)
		{
			$cardSplit = explode(',', $cards);
			$splitCount = count($cardSplit);
			$bottomCardType = explode('_', $tableCards)[0];
			for($i = 0; $i < $splitCount; $i++)
			{
				$currentCard = $cardSplit[$i];
				$type = explode('_', $currentCard)[0];
				if($type == $bottomCardType || $type == '7')
				{
					$canContest = 'true';
				}
			}
			
			if($canContest == 'true')
			{
				$tableSplit = explode(',', $tableCards);
				$splitCount = count($tableSplit);
				for($i = 0; $i < $splitCount; $i++)
				{
					$currentCard = $tableSplit[$i];
					$type = explode('_', $currentCard)[0];
					if($type == 'a' || $type == '10')
					{
						$winPoints ++;
					}
					$takenCards ++;
				}
				$tableCards = "";
				$turn = $contester;
			}
		}
		$myCards = $cards;
		if($tableCards == "" && $deck != "") //someone TOOK THEM ALL!
		{
			$deckSplit = explode(',', $deck);
			$cardsToGive = 4;
			if( strlen($myCards) > 0 )
			{
				$cardsToGive = 4 - count(explode(',', $myCards));
			}
			
			$total = count($deckSplit);
			if($total < $cardsToGive * 2)
			{
				$cardsToGive = $total / 2;
			}
			
			$deckIndex = 0;
			for($j = 0; $j < $cardsToGive; $j++)
			{
				query("insert into action_history (game_id, username, action, value, stage) values ('$id', '$user', 'draw', '" . $deckSplit[$deckIndex] . "', '1')");
				if($myCards == "")
				{
					$myCards = $deckSplit[$deckIndex];
				}
				else
				{
					$myCards = $myCards . "," . $deckSplit[$deckIndex];
				}
				$deckIndex++;
				
				query("insert into action_history (game_id, username, action, value, stage) values ('$id', '$opp', 'draw', '" . $deckSplit[$deckIndex] . "', '1')");
				if($oppCards == "")
				{
					$oppCards = $deckSplit[$deckIndex];
				}
				else
				{
					$oppCards = $oppCards . "," . $deckSplit[$deckIndex];
				}
				$deckIndex++;
			}
			
			$deck = "";
			for($i = $deckIndex; $i < count($deckSplit); $i++)
			{
				$deck = $deck . $deckSplit[$i] . ',';
			}
			if(strlen($deck) > 0)
			{
				$deck = substr($deck, 0, strlen($deck) - 1);
			}
		}

		query("insert into action_history (game_id, username, action, value, stage) values ('$id', '$user', 'give in', '', '1')");
		query("insert into action_history (game_id, username, action, value, stage) values ('$id', '$oppCards', 'take cards', '$winPoints', '1')");
		query("update game_cards set cards='$oppCards', points = points + $winPoints, cards_taken = cards_taken + $takenCards where username='$opp'");

		if(!check_end_game($id))
		{
			query("update game_cards set cards='$myCards' where username='$user'");
			query("update game set cards='$deck', table_cards='$tableCards', turn='$turn', first_card='$firstCard', contester='$contester', can_contest='false' where id='$id'");
		}
	}
}

function check_end_game($id)
{
	$winArray = fetch_array(query("select username, points from game_cards where game_id='$id' order by points desc limit 1"));
	$winner = $winArray[0];
	$winnerPoints = $winArray[1];

	$lossArray = fetch_array(query("select username, points from game_cards where game_id='$id' and username <> '$winner'"));
	$loser = $lossArray[0];
	$loserPoints = $lossArray[1];
	
	if($winnerPoints + $loserPoints == 8 || $winnerPoints > 4 || $loserPoints > 4)
	{
		query("delete from game where id='$id'");
		query("delete from game_cards where game_id='$id'");
		query("update statistics set wins = wins + 1 where username='$winner'");
		query("update statistics set loss = loss + 1 where username='$loser'");
		query("delete from status where usernane='$winner' or username='$loser'");

		if($winnerPoints == 4 && $loserPoints == 4)
		{
			query("insert into status (username, status) values ('$winner', 'tie')");
			query("insert into status (username, status) values ('$loser', 'tie')");

			query("insert into game_history (game_id, username, status, points) values ('$id', '$winner', 'tied', $winnerPoints)");
			query("insert into game_history (game_id, username, status, points) values ('$id', '$loser', 'tied', $loserPoints)");
		}
		else
		{
			query("insert into status (username, status) values ('$winner', 'win')");
			query("insert into status (username, status) values ('$loser', 'loss')");
			
			query("insert into game_history (game_id, username, status, points) values ('$id', '$winner', 'won', $winnerPoints)");
			query("insert into game_history (game_id, username, status, points) values ('$id', '$loser', 'lost', $loserPoints)");
		}
		return true;
	}
	return false;
}


?>