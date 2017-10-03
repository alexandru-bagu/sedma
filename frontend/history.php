<?php

$user = $_SESSION['user'];

$page = 0;
if(isset($_GET['page'])) $page = intval(escape($_GET['page']));
$a = $page * 10;
$total = fetch_array(query("select count(*) from game_history where username='$user'"))[0];
if($total == 0)
{
    echo "There's nothing here.";
}
else
{
    $res = query("select * from game_history where username='$user' limit $a,10");

    while($data = fetch_assoc($res))
    {
        $id = $data['game_id'];
        $res2 = query("select b.nickname from game_history a, users b where a.game_id=$id and a.username <> '$user' and a.username=b.username");
        $opp = fetch_array($res2)[0];
        $status = $data['status'];
        $a++;
        echo "$a. You versus $opp. You $status. - <a href=\"http://81.4.106.74/index.php?page=history&id=$id\">View</a> | <a href=\"http://81.4.106.74/index.php?page=history&id=$id&dl=1\">Download</a>";
        echo "<br/>";
    }

    echo "<br/>";
    $next = $page + 1;
    $prev = $page - 1;

    if($total >= $next * 10)
        echo "<a href=\"http://81.4.106.74/index.php?page=history&page=$next\">Next page</a>";
    if($prev >= 0)
        echo "<a href=\"http://81.4.106.74/index.php?page=history&page=$prev\">Previous page</a>";
}
?>