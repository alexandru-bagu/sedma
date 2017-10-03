<?php
	showErrors();
?>
<form method="post" action="index.php?page=verify_contact">
	<table>
		<tr>
			<td>Name</td>
			<td><input name='name' type='text'/></td>
		</tr>
		<tr>
			<td>Email</td>
			<td><input name='email' type='email'/></td>
		</tr>
		<tr>
			<td>Message</td>
			<td><input name='message' type='text' size="64"/></td>
		</tr>
		<tr>
			<td colspan="2" align="left">
				<input type='submit' name='submit' value='Next'/>
			</td>
		</tr>
	</table>
</form>