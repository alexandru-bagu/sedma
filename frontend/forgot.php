<?php
if(hasErrors())
	showErrors();
?>


<form method="post" action="index.php?page=forgot">
	<table>
		<tr>
			<td>Email</td>
			<td><input name='email' type='text'/></td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type='submit' name='submit' value='Reset password'/>
			</td>
		</tr>
	</table>
</form>