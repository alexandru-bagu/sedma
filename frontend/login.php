<?php
	showErrors();
?>
<form method="post" action="index.php?page=login">
	<table>
		<tr>
			<td>Username</td>
			<td><input name='username' type='text'/></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input name='password' type='password'/></td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type='submit' name='submit' value='Login'/>
			</td>
		</tr>
	</table>
</form>
<br/>
<a href="index.php?page=register">Register?</a>