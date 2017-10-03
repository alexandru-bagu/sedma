<?php
$CONFIG = "TRUE";
require('config.php');

connect_sql();
try 
{
	$BACKEND = "TRUE";
	if(isset($_GET["page"])) 
	{
		$page = "backend/" . $_GET["page"] . ".php";
		if(file_exists($page)) require($page);
	}
	$BACKEND = "F";

	if(isset($_GET['ajax']) && $_GET['ajax'] == '1')
	{
		if(isset($_GET["page"])) $page = "frontend/" . $_GET["page"] . ".php";
		if(file_exists($page)) { require ($page); }
		return;
	}
	?>

	<html>
		<head>
			<title>Sedma A.K.A Şeptică [OPEN BETA]</title>
			<link rel="stylesheet" type="text/css" href="stylesheet.css">
		</head>
	
		<body>
			<table align="center" width="100%">
				<tr>
					<td colspan="2" style=" background:rgba(123,123,123,0.5);">
						<?php require('header.php'); ?>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<h1>Şeptică</h1>
					</td>
				</tr>
				<tr> 
					<td width="80%">
						<div id="content">
							<?php
							$page = "";
							if(isset($_GET["page"])) $page = "frontend/" . $_GET["page"] . ".php";
							if(!file_exists($page)) $page = "frontend/default.php";
							if(file_exists($page)) require ($page);
							?> 
						</div>
					</td>
					<td valign="top">
						<div id="side">
							<?php
								require ("side.php");
							?>
						</div>
					</td>
				</tr>
			</table>
			<div style="left:40%;" align="center">Copyright (c) Alexandru Bagu 2016</div>
			<div style="left:40%;" align="center">
				<?php
				$res = query("select count(*) from visitors group by ip");
				$uniqueVisitors = num_rows($res);
				$res = query("select count(*) from users");
				$users = fetch_array($res)[0];
				$page = $_SERVER['REQUEST_URI'];
				$res = query("select count(*) from visitors");
				$visits = fetch_array($res)[0];
				echo " $uniqueVisitors unique visitors to date; $users registered users; $visits visits";
				?>
			</div>
		</body>
	</html>

<?php
}
finally
{
	global $track;
	if(!isset($track))
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		$referer = "";
		if (isset($_SERVER['HTTP_REFERER']))
			$_SERVER['HTTP_REFERER'];
		if (!isset($_SESSION['tracked'])) {
			query("insert into visitors (ip, referer) values ('$ip', '$referer')");
		}
		$_SESSION['tracked'] = 1;
	}
	close_sql();
}
?>