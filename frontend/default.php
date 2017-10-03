<?php

$res = query("select a.join_code, (select nickname from users where username = a.turn) as nickname from game a where public=1 and (select count(*) from game_cards b where b.game_id = a.id)");
$total = num_rows($res);
$num = 0;
if($total != 0)
{
    if(isset($_SESSION['user']))
    {
	    echo "<h4>There are open games you can join. <a href=\"index.php?page=game_join\">Click here to get started.</a></h4>";
    }
    else
    {
	    echo "<h4>There are open games you can join. <a href=\"index.php?page=register\">Click here to get started.</a></h4>";
    }
}

$data = file_get_contents("https://www.pagat.com/sedma/sedma.html");
$data = "<h1>" .explode("<h1>", $data)[1];
$data = explode("</div>", $data)[0];
echo  $data;
?>