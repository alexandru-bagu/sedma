<?php
if(!isset($_SESSION['user'])) { redirect("index.php?page=login"); return; }
$user = $_SESSION['user'];
?>

<div>
	<h4>Statistics</h4>
	<?php
		$res = query("select * from statistics where username='$user'");
		$row = fetch_assoc($res);
		echo "Wins: " . $row['wins'] . "</br>";
		echo "Losses: " . $row['loss'] . "</br>";
		
		if($_SESSION['is_admin'] == 1)
		{
			echo "</br>";
			echo "<a href=\"index.php?page=messages\">Read messages</a>";
			echo "</br>";
			echo "<a href=\"index.php?page=manage\">Activate/Deactivate account</a>";
		}
	?>
</div>