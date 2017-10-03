<?php
if(!isset($_SESSION['user'])) { redirect("index.php?page=login"); return; }
if(!isset($CREATE_NEW_GAME)) { return; }
if(!isset($_GET['type'])) { return; }
$user = $_SESSION['user'];
$cards = array("a", "k", "q", "j", "10", "9", "8", "7");
$types = array("_1", "_2", "_3", "_4");
$allCards = array();
for($i = 0; $i < count($cards); $i++)
{
	for ($j = 0; $j < count($types); $j++)
	{
		$allCards []= $cards[$i] . $types[$j];
	}
}

shuffle($allCards);
$cards = $allCards[0];
for($i = 1; $i < count($allCards); $i++)
{
	$cards = $cards . ',' . $allCards[$i];
}

query("insert into game (cards,turn) values ('$cards','$user')");
$id = insert_id();

if($_GET['type'] == 'new')
{
	$code = strtoupper(generateRandomString(6));
	while(num_rows(query("select id from game where join_code='$code'")) > 0)
	{
		$code = strtoupper(generateRandomString(6));
	}
	query("update game set join_code='$code' where id='$id'");	
	query("insert into game_cards (username, game_id, cards) values ('$user', '$id', '')");
}

redirect('index.php?page=game');
?>