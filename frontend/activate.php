<?php
if(hasErrors())
{
	showErrors();
	$code = escape($_GET['code']);
	$res = query("select * from activation_code where `code`='$code'");
	if(num_rows($res) == 0)
	{
		return;
	}
}

?>


<form method="post" action="index.php?page=activate">
	<table>
		<tr>
			<td>Code</td>
			<td><input name='code' type='textbox'/></td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type='submit' name='submit' value='Activate'/>
			</td>
		</tr>
	</table>
</form>