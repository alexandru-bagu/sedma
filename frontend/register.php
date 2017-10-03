<?php
	showErrors();
	$_SESSION['captcha'] = simple_php_captcha();
?>
<form method="post" action="index.php?page=register">
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
			<td>Password confirmation</td>
			<td><input name='password2' type='password'/></td>
		</tr>
		<tr>
			<td>Email</td>
			<td><input name='email' type='email'/></td>
		</tr>
		<tr>
			<td>Nickname</td>
			<td><input name='nickname' type='text'/></td>
		</tr>
		<!--<tr>
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
		</tr>-->
		<tr>
			<td>Include password in activation email</td>
			<td><input name='pwmail' type='checkbox'/></td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type='submit' name='submit' value='Register'/>
			</td>
		</tr>
	</table>
</form>