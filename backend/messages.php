<?php

if(!isset($_SESSION['user'])) { redirect("index.php?page=login"); return; }
if($_SESSION['is_admin'] == 1)
{
	if(isset($_GET['delete']))
	{
		$id = escape($_GET['delete']);
		$res = query("delete from contact where id='$id'");
	}
}

?>