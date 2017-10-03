<?php
if(hasErrors())
{
	showErrors();
	$key = escape($_GET['key']);
	$res = query("select * from password_reset where `key`='$key'");
	if(num_rows($res) == 0)
	{
		return;
	}
}

?>


<form method="post" action="index.php?page=reset&key=<?php echo $_GET['key']; ?>">
	<table>
		<tr>
			<td>Password</td>
			<td><input name='password1' type='password'/></td>
		</tr>
		<tr>
			<td>Password conformation</td>
			<td><input name='password2' type='password'/></td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type='submit' name='submit' value='Reset password'/>
			</td>
		</tr>
	</table>
</form>