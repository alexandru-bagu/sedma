<?php
if(!isset($_SESSION['user'])) { redirect("index.php?page=login"); return; }
$user = $_SESSION['user'];
$res = query("select * from game_cards where username='$user'");
if($myData = fetch_assoc($res))
{
	$id = $myData['game_id'];
	
	$res = query("select * from game where id='$id'");
	$game = fetch_assoc($res);
	
	$res = query("select * from game_cards where game_id='$id' and username<> '$user'");
	echo "<div id=\"game\">";
	if(num_rows($res) == 1)
	{
		$opData = fetch_assoc($res);
		$opUser = $opData['username'];
		?>
		<div align="center">
			<table border="0" width="60%">
				<tr class="border">
					<td colspan="2" width="80%" align="center" class="border">
						<div id="opponentCards">
							<?php
							$res = query("select nickname from users where username='$opUser'");
							$nickname = fetch_array($res)[0];
							echo "<div style=\"width=60%; margin:auto;\"><h4>$nickname</h4></div>";
							$cards = explode(',', $opData['cards']);
							$highlight = $opData['highlightedCard'];
							if((count($cards) == 1 && $cards[0] != "") || count($cards) > 1)
							{
								for($i = 0; $i < count($cards); $i++)
								{
									if($highlight == $i && $game['turn'] != $user)
									{
										echo "<div class=\"card highlight\"><img src=\"images/back.png\" id=\"$i\" /> </div>";
									}
									else
									{
										echo "<div class=\"card\"><img src=\"images/back.png\" id=\"$i\" /> </div>";
									}
								}
							}
							?>
						</div>
					</td>
					<td width="20%" class="border" style="max-width:200px;">
						<div id="opponentsPoints" style="max-width:200px; width:60%; margin-left:40%;">
							<?php
								$count = $opData['cards_taken'];
								$points = $opData['points'];
								echo "$points points";
								for($i = 0; $i < $count; $i+=10)
								{
									echo "</br>";
								}
								if($count > 0) 
								{
									?>
									<figure class="stack stack-spread active">
										<?php
										for($i = 0; $i < $count; $i++)
										{
											$x = (0) .'px';
											$y = (-$i * 2) .'px';
											echo "<img style=\" -webkit-transform: translate($x,$y); transform: translate($x,$y);\" 
												src=\"images/back.png\" alt=\"deck$i\">";
										}
										?>
									</figure>
									<?php
								}
							?>
						</div>
					</td>
				</tr>
				<tr>
					<td class="border">
						<div id="bottomCard" style="width:100%; margin-left: 30%;">
							<?php
								$cards = explode(',', $game['table_cards']);
								if(count($cards) > 0 && $cards[0] != "")
								{
									echo "<img src=\"images/$cards[0].png\" id=\"bottomCardValue\" />";
								}	
							?>
							<div style="width:80%; margin-left:-1%;">Bottom Card</div>
						</div>
					</td>
					<td width="60%" class="border" style="max-width:400px; min-width:200px; min-height:120px;">
						<div id="playArea" style="min-width: 200px; max-width:400px; min-height:120px; width:60%; margin-left:40%;">
							<?php
								$cards = explode(',', $game['table_cards']);
								$count = count($cards);
								for($i = 0; $i < $count; $i+=5)
								{
									echo "</br>";
								}
								if($count > 0 && $cards[0] != "") 
								{
									?>
									<figure class="stack stack-spread active">
										<?php
										for($i = 0; $i < $count; $i++)
										{
											$card = $cards[$i];
											$x = (0) .'px';
											$y = (-$i * 3) .'px';
											echo "<img style=\" -webkit-transform: translate($x,$y); transform: translate($x,$y);\" 
												src=\"images/$card.png\" alt=\"deck$i\">";
										}
										?>
									</figure>
									<?php
								}
							?>
						</div>
					</td>
					<td class="border"  style="max-width:200px;">
						<div id="deck" style="max-width:200px; width:60%; margin-left:30%;">
							<?php
								$cards = explode(',', $game['cards']);
								$left = count($cards);
								if($left == 1 && $cards[0] == "") $left = 0;
								if($left > 0)
								{	
									for($i = 0; $i < $left; $i+=10)
									{
										echo "</br>";
									}
									?>
									<figure class="stack stack-spread active">
										<?php
										for($i = 0; $i < $left; $i++)
										{
											$x = (0) .'px';
											$y = (-$i * 2) .'px';
											echo "<img style=\" -webkit-transform: translate($x,$y); transform: translate($x,$y);\" 
												src=\"images/back.png\" alt=\"deck$i\">";
										}
										?>
									</figure>
									<?php
								}
							?>
						</div>
					</td>
				</tr>
				<tr>
					<td width="20%" class="border" style="min-width:200px; max-width:200px;">
						<div id="myPoints" style="max-width:200px; width:60%; margin-left:40%;">
							<?php
								$count = $myData['cards_taken'];
								$points = $myData['points'];
								echo "$points points";
								for($i = 0; $i < $count; $i+=10)
								{
									echo "</br>";
								}
								if($count > 0) 
								{
									?>
									<figure class="stack stack-spread active">
										<?php
										for($i = 0; $i < $count; $i++)
										{
											$x = (0) .'px';
											$y = (-$i * 2) .'px';
											echo "<img style=\" -webkit-transform: translate($x,$y); transform: translate($x,$y);\" 
												src=\"images/back.png\" alt=\"deck$i\">";
										}
										?>
									</figure>
									<?php
								}
							?>
						</div>
					</td>
					<td colspan="2" width="80%" align="center" class="border">
						<div id="myCards">
							<?php
							$cards = explode(',', $myData['cards']);
							if($game['turn'] == $user) 
							{
								if(count($cards) == 1 && $cards[0] != "" || count($cards) > 1)
								{
									for($i = 0; $i < count($cards); $i++)
									{
										echo "<div id=\"my$i\" class=\"card\"><img src=\"images/$cards[$i].png\" id=\"$i\" 
											onclick=\"clickCard($i)\"
											onmouseover=\"showHover($i)\"
											onmouseout=\"removeHover($i)\"/> </div>";
									}
									echo "<div align=\"center\">It's your turn!</div>";
								}
							}
							else
							{
								if((count($cards) == 1 && $cards[0] != "") || count($cards) > 1)
								{
									for($i = 0; $i < count($cards); $i++)
									{
										echo "<div class=\"card\"><img src=\"images/$cards[$i].png\" id=\"$i\" /> </div>";
									}
								}
							}
							?>
						</div>
					</td>
				</tr>
			</table>
			<div align="center">
			<?php
			if($game['can_contest'] == 'true' && $game['turn'] == $user) 
			{
				$card = explode('_', $game['table_cards'])[0];
				$cardType = "";
				if($card != '7' && $card != "")
				{
					$cardType = " or " . strtoupper($card) .'s';
				}
				echo "<h3>You can contest the current card sequence -- ONLY 7s$cardType! If you don't want to, click <a href=\"#\" onclick=\"noContest();return false;\">here<a/></h3>";
			}
			?>
			</div>
		</div>
		<script>
			window.REFRESH_DELAY = 500;
		</script>
		<?php
		global $track;
		$track = 0;
	}
	else
	{
		?>
		<script>
			window.REFRESH_DELAY = 3000;
		</script>
		<?php
		$res = query("select join_code, public from game where id='$id'");
		$row = fetch_array($res);
		
		echo "<h3 style=\"display: inline;\">Share the game code with a friend: </h3> <h2 style=\"display: inline;\">$row[0]</h2></br>";
		if($row[1] == 0) {
			echo "<h3 style=\"display: inline;\">The game code is currently <b style=\"color: red;\">private</b>. Would you like to make it public? </h3> <h2 style=\"display: inline;\"><a href=\"index.php?page=public&v=1\">Yes</a></h2>";
		} else {
			echo "<h3 style=\"display: inline;\">The game code is currently <b style=\"color: green;\">public</b>. Would you like to make it private? </h3> <h2 style=\"display: inline;\"><a href=\"index.php?page=public&v=0\">Yes</a></h2>";
		}
		
	}
	echo "</div>";
	?>
	
	<script>
	window.timer = setTimeout(refresh, window.REFRESH_DELAY);
	function refresh()
	{
		clearTimeout(window.timer);
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{	
				c1class = "card"; c2class = "card";
				c3class = "card"; c4class = "card";
				c1 = document.getElementById("my0");
				if(c1 != null) c1class = c1.className;
				c2 = document.getElementById("my1");
				if(c2 != null) c2class = c2.className;
				c3 = document.getElementById("my2");
				if(c3 != null) c3class = c3.className;
				c4 = document.getElementById("my3");
				if(c4 != null) c4class = c4.className;
			
				document.getElementById("content").innerHTML = this.responseText;
				
				disableRefresh = document.getElementById("disableRefresh");
				if(disableRefresh != null)
				{	
					refreshSide();
					return;
				}
				
				if(document.getElementById("game") == null) return;
				c1 = document.getElementById("my0");
				c2 = document.getElementById("my1");
				c3 = document.getElementById("my2");
				c4 = document.getElementById("my3");
				if(c1 != null) c1.className = c1class;
				if(c2 != null) c2.className = c2class;
				if(c3 != null) c3.className = c3class;
				if(c4 != null) c4.className = c4class;
				
				opCards = document.getElementById("opponentCards");
				if(opCards != null)
					window.REFRESH_DELAY = 500;
				
				window.timer = setTimeout(refresh, window.REFRESH_DELAY);
			 }
		};
		xhttp.open("GET", "index.php?page=game&ajax=1", true);
		xhttp.send();
	}
	
	function refreshSide()
	{
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				document.getElementById("side").innerHTML = this.responseText;
			}
		};
		xhttp.open("GET", "side.php?ajax=1", true);
		xhttp.send();
	}
	
	function clickCard(number) 
	{			
		var xhttp = new XMLHttpRequest();
		xhttp.open("GET", "index.php?page=game_update&ajax=1&click=" + number, true);
		xhttp.send();
		refresh();
	}
	
	function noContest() 
	{
		var xhttp = new XMLHttpRequest();
		xhttp.open("GET", "index.php?page=game_update&ajax=1&no_contest=1", true);
		xhttp.send();
		refresh();
	}
	
	function removeHighlight(elem)
	{
		if(elem == null) return;
		hilight = " highlight";
		cls = elem.className;
		while(cls.includes(hilight)) 
			cls = elem.className = cls.replace(" highlight", "");
	}
	
	function setHighlight(elem)
	{
		if(elem == null) return;
		hilight = " highlight";
		cls = elem.className;
		if(!cls.includes(hilight)) 
			elem.className += hilight;
	}
	
	function showHover(number) 
	{
		for(i = 0; i < 4; i++)
			removeHighlight(document.getElementById("my" + i));
		elem = document.getElementById("my" + number);
		setHighlight(elem);
		
		var xhttp = new XMLHttpRequest();
		xhttp.open("GET", "index.php?page=game_update&ajax=1&setHover=" + number, true);
		xhttp.send();
	}
	
	function removeHover(number) 
	{
		elem = document.getElementById("my" + number);
		removeHighlight(elem);
		
		var xhttp = new XMLHttpRequest();
		xhttp.open("GET", "index.php?page=game_update&ajax=1&removeHover", true);
		xhttp.send();
	}
	</script>
	
	<?php
}
else
{
	require("game.php");
}	
?>