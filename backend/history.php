<?php
if(!isset($_SESSION['user']))
{
    redirect('index.php');
    return;
}

$user = $_SESSION['user'];

if(isset($_GET['id']))
{
    $id = $_GET['id'];
    include("fpdf/fpdf.php");
        
    $pdf = new FPDF();
    $pdf->SetFont('Arial','B',16);
    $pdf->AddPage();

    $y = 10;
    $x = 10;
    $pdf->Text($x,$y,'You initially drew:');
    $y += 10;
    $res = query("select * from action_history where game_id = $id and stage = 0 and username = '$user' limit 4");
    while($data = fetch_assoc($res))
    {
        $value = $data['value'];
        $x += 30;
        $pdf->Image("images/$value.png", $x, $y);
    }
    $x = 10;
    $y += 40;
    $pdf->Text($x,$y,'Your opponent initially drew:');
    $y += 10;
    $res = query("select * from action_history where game_id = $id  and stage = 0 and username <> '$user' limit 4");
    while($data = fetch_assoc($res))
    {
        $value = $data['value'];
        $x += 30;
        $pdf->Image("images/$value.png", $x, $y);
    }
    $y += 40;

    $res = query("select * from action_history where game_id = $id");
    for ($i = 0; $i < 8; $i++) fetch_assoc($res);
    $x = 10;
    while($data = fetch_assoc($res))
    {
        $u = $data['username'];
        if($y >= 250) 
        { 
            $pdf->AddPage();
            $y = 10; 
        }

        if($data['action'] == 'put')
        {
            if($u == $user)
            {
                $pdf->Text($x,$y + 30,'You put on the table:');
            }
            else
            {
                $pdf->Text($x,$y + 30,'Your opponent put on the table:');
            }
            $y += 10;
            $value = $data['value'];
            $pdf->Image("images/$value.png", $x + 100, $y + 5);
            $y += 40;
        }
        else if($data['action'] == 'draw')
        {
            if($u == $user)
            {
                $pdf->Text($x,$y + 30,'You drew:');
            }
            else
            {
                $pdf->Text($x,$y + 30,'Your opponent drew:');
            }
            $y += 10;
            $value = $data['value'];
            $pdf->Image("images/$value.png", $x + 100, $y + 5);
            $y += 40;
        }
        else if($data['action'] == 'take cards')
        { 
            $y += 10;
            if($u == $user)
            {
                $pdf->Text($x,$y,'You took the cards on the table and got: ' . $data['value'] . ' point(s)!');
            }
            else
            {
                $pdf->Text($x,$y,'Your opponent took the cards on the table and got: ' . $data['value'] . ' point(s)!');
            }
            $y += 10;
            $pdf->Line($x, $y, 200, $y);
        }
    }
    $y += 20;
    $res = query("select * from game_history where game_id = $id");
    while($data = fetch_assoc($res))
    {
        if($data['status'] == 'won')
        {
            if($data['username'] == $user)
            {
                $pdf->Text($x,$y,'You won with: ' . $data['points'] . ' points!');
            }
            else
            {
                $pdf->Text($x,$y,'Your opponent won with: ' . $data['points'] . ' points!');
            }
        }
    }
    if(isset($_GET['dl']))
    {   
        $pdf->Output('D', 'history.pdf');
    }
    else
    {
        $pdf->Output();
    }
}
?>