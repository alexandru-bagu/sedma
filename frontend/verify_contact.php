<?php
	showErrors();
	$_SESSION['captcha'] = simple_php_captcha();
?>
<form method="post" action="index.php?page=verify_contact">
	<table>
		<tr>
			<td>Name</td>
			<td><input name='name' type='text' value='<?php echo $_POST["name"] ?>'/></td>
		</tr>
		<tr>
			<td>Email</td>
			<td><input name='email' type='email' value='<?php echo $_POST["email"] ?>'/></td>
		</tr>
		<tr>
			<td>Message</td>
			<td><input name='message' type='text' size="64" value='<?php echo $_POST["message"] ?>'/></td>
		</tr>
		<tr>
			<td>Captcha</td>
			<td><input name='captcha' type='text'/></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<?php 
				$img = $_SESSION['captcha']['image_src'];
				echo "<img src=\"$img\"/>";	
				?>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="left">
				<input type='submit' name='submit' value='Send'/>
			</td>
		</tr>
	</table>
</form>