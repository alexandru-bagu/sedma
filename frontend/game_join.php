<?php
if(hasErrors())
	showErrors();
	if(!isset($_SESSION['user']))
	{
		redirect('index.php');
		return;
	}
	$user = $_SESSION['user'];
	$res = query("select * from game_cards where username='$user'");
	if($myData = fetch_assoc($res))
	{
		redirect('index.php?page=game');
		return;
	}
?>
<form action="index.php?page=game_join" method="post">
	Input join code: <input id="ijc" type="text" name="code" />
	<input type="submit" name="submit" value="Join">
</form>

<?php
$res = query("select a.join_code, (select nickname from users where username = a.turn) as nickname from game a where public=1 and (select count(*) from game_cards b where b.game_id = a.id)");
$total = num_rows($res);
$num = 0;
if($total == 0)
{
	echo "There are no public games available.";
}
else
{
?>
<br/>
<br/>
<h2>
Available games to join:
<br/>
<table width="30%">
	<tr>
		<td></td><td><b>nickname</b></td><td><b>join code</b></td><td></td>
	</tr>
	<?php
		while($data = fetch_array($res))
		{
			$num++;
			$n = $data[0];
			$g = $data[1];
			echo "<tr>
				<td>$num.</td><td>$g</td><td>$n</td><td><a href=\"index.php?page=game_join&code=$n\">Join</a></td>
			</tr>";
		}
	?>
</table>
<?php
}
?>
</h2>

<script>
	window.REFRESH_DELAY = 5000;
	window.timer = setTimeout(refresh, window.REFRESH_DELAY);
	function refresh()
	{
		clearTimeout(window.timer);
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				content = document.getElementById("ijc").value;
				document.getElementById("content").innerHTML = this.responseText;
				document.getElementById("ijc").value = content;
				window.timer = setTimeout(refresh, window.REFRESH_DELAY);
			 }
		};
		xhttp.open("GET", "index.php?page=game_join&ajax=1", true);
		xhttp.send();
	}
</script>