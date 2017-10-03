<div style="line-height: 30px; height: 30px; width=100%;">
<?php
	if(isset($_SESSION["user"]))
	{
		?>
		<div style="display: inline; float:left; margin-left:10px;">
		<?php
			echo "Welcome, ". $_SESSION["user"] . ".";
		?>
		</div>
		<div style="display: inline; float:right; margin-right:10px;">
			<a href='index.php?page=logout'>Log out?</a>
		</div>
		<?php
	}
	else
	{
		?>
		<a href="index.php?page=register" style="display: inline; margin-left:10px; float:left;">Register?</a>
		<div style="display: inline; margin-right:10px; float:right;" align="right">
			<div style="display: inline;">
				<form method="post" style="display: inline;" action="index.php?page=login">
					Username: <input name='username' type='text'/>
					Password: <input name='password' type='password'>
					<input type='submit' name='submit' value='Login'/>
				</form>
			</div>
			<div style="display: inline; margin-right:10px;">
				<a href="index.php?page=forgot">Forgot the password?</a>
			</div>
		</div>
		<?php
	}
?>
</div>