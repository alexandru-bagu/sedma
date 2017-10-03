<?php
if(hasErrors())
{
	showErrors();
}

?>

<h1> Deactivate account </h1>
<form method="post" action="index.php?page=manage">
	<table>
		<tr>
			<td>Username</td>
			<td><input name='username' type='username'/></td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type='submit' name='submit_deactivate' value='Deactivate'/>
			</td>
		</tr>
	</table>
</form>

<h1> Activate account </h1>

<?php
if(fetch_array(query("select count(*) from inactive_users"))[0] > 0)
{?>
    <table border="1">
        <tr>
            <td>Username</td><td>Email</td><td>Register date</td>
        </tr>
        <?php
            $res = query("select * from inactive_users");
            while($data = fetch_assoc($res))
            {
                $u = $data['username'];
                $e = $data['email'];
                $r = $data['register_date'];
                echo "
                <tr>
                    <td>$u</td><td>$e</td><td>$r</td>
                </tr>";
            }
        ?>
    </table>
<?php 
}?>

<form method="post" action="index.php?page=manage">
	<table>
		<tr>
			<td>Username</td>
			<td><input name='username' type='username'/></td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type='submit' name='submit_activate' value='Activate'/>
			</td>
		</tr>
	</table>
</form>