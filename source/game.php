<?php
/**
 * Created by PhpStorm.
 * User: tosa
 * Date: 22.1.17.
 * Time: 18.32
 */


$conn = mysqli_connect('localhost','root','root','mydb');

if(mysqli_connect_errno())
{
    printf("SQL CONNECT ERROR: %s\n", mysqli_connect_error());
}

$filter = "where true";
$header = array();
$gameCode=$_GET['gameCode'];
//$gameCode = 1;

$season=$_GET['season'];
//$season = 2016;


$h0 = new stdClass(); $h0->name="Name"; $h0->nameOfProperty="playerName";
array_push($header, $h0);
$h0 = new stdClass(); $h0->name="MIN"; $h0->nameOfProperty="min2";
array_push($header, $h0);
$h2 = new stdClass(); $h2->name="PTS"; $h2->nameOfProperty="pts";
array_push($header, $h2);
$h3 = new stdClass(); $h3->name="2FGM"; $h3->nameOfProperty="2fgm";
array_push($header, $h3);
$h4 = new stdClass(); $h4->name="2FGA %"; $h4->nameOfProperty="2FGA";
array_push($header, $h4);
$h5 = new stdClass(); $h5->name="3FGM"; $h5->nameOfProperty="3fgm";
array_push($header, $h5);
$h6 = new stdClass(); $h6->name="3FGA"; $h6->nameOfProperty="3FGA";
array_push($header, $h6);
$h7 = new stdClass(); $h7->name="OR"; $h7->nameOfProperty="or2";
array_push($header, $h7);
$h8 = new stdClass(); $h8->name="DR"; $h8->nameOfProperty="dr";
array_push($header, $h8);
$h9 = new stdClass(); $h9->name="ASS"; $h9->nameOfProperty="ass";
array_push($header, $h9);
$h10 = new stdClass(); $h10->name="STL"; $h10->nameOfProperty="stl";
array_push($header, $h10);
$h11 = new stdClass(); $h11->name="TO"; $h11->nameOfProperty="to2";
array_push($header, $h11);
$h12 = new stdClass(); $h12->name="BLK"; $h12->nameOfProperty="blk";
array_push($header, $h12);
$h13 = new stdClass(); $h13->name="BLKA"; $h13->nameOfProperty="blga";
array_push($header, $h13);
$h14 = new stdClass(); $h14->name="CM"; $h14->nameOfProperty="cm";
array_push($header, $h14);
$h15 = new stdClass(); $h15->name="RV"; $h15->nameOfProperty="rv";
array_push($header, $h15);
$h16 = new stdClass(); $h16->name="PIR"; $h16->nameOfProperty="PIR";
array_push($header, $h16);
$h17 = new stdClass(); $h17->name="+/-"; $h17->nameOfProperty="+/-";
array_push($header, $h17);


$sql = "select t.teamName , g.teamH as teamID from Team t join Game g on t.idTeam = g.teamH where g.gameCode = $gameCode and g.season = $season";
$poruka = new stdClass();
$poruka->header = $header;
$tabela = array();

$rez = $conn->query($sql);
array_push($tabela, $rez->fetch_assoc());

$sql = "select t.teamName , g.teamA as teamID from Team t join Game g on t.idTeam = g.teamA where g.gameCode = $gameCode and g.season = $season";

$rez = $conn->query($sql);
array_push($tabela, $rez->fetch_assoc());

$poruka->teams = $tabela;

$index = "ps.PTS - ps.2FGA - ps.3FGA - ps.FTA + ps.OR2 + ps.DR + ps.ASS + ps.STL - ps.TO2 + ps.BLK - ps.BLGA - ps.CM +ps.RV as 'PIR' ";
$sql = "select p.playerName , ps.min2, ps.pts, ps.2fgm , (ps.2fgm+ps.2fga) as '2FGA', ps.3fgm, (ps.3fgm+ps.3fga) as '3FGA' , or2, dr, ass,stl, to2, blk, blga, cm, rv, $index, ps.teamPoints-ps.teamOppPoints as '+/-' from PlayerStats ps join Game g on g.gameCode = ps.gameCode join Player p where g.gameCode = $gameCode and g.season = $season and ps.teamId = g.teamH and p.idPlayer = ps.playerId";
$rez = $conn->query($sql);

$tabela = array();
$i = 0;
for($i = 0; $i < $rez->num_rows; $i++){
    array_push($tabela, $rez->fetch_assoc());
}
$poruka->team1 = $tabela;

$index = "ps.PTS - ps.2FGA - ps.3FGA - ps.FTA + ps.OR2 + ps.DR + ps.ASS + ps.STL - ps.TO2 + ps.BLK - ps.BLGA - ps.CM +ps.RV as 'PIR' ";
$sql = "select p.playerName , ps.min2, ps.pts, ps.2fgm , (ps.2fgm+ps.2fga) as '2FGA', ps.3fgm, (ps.3fgm+ps.3fga) as '3FGA' , or2, dr, ass,stl, to2, blk, blga, cm, rv, $index, ps.teamPoints-ps.teamOppPoints as '+/-' from PlayerStats ps join Game g on g.gameCode = ps.gameCode join Player p where g.gameCode = $gameCode and g.season = $season and ps.teamId = g.teamA and p.idPlayer = ps.playerId";
$tabela = array();

$rez = $conn->query($sql);
$i = 0;
for($i = 0; $i < $rez->num_rows; $i++){
    array_push($tabela, $rez->fetch_assoc());
}
$poruka->team2 = $tabela;


$indexT = "ps.PTS +ps.2FGM + ps.FTM + ps.3FGM - ps.2FGA - ps.3FGA - ps.FTA + ps.OR2 + ps.DR + ps.ASS + ps.STL - ps.TO2 + ps.BLK - ps.BLKA - ps.CM +ps.RV as 'PIR' ";
$sql="select 'TOTAL' as 'playerName', ' ' as 'MIN', PTS as 'PTS',  2FGM as '2fgm', 2FGA, 3FGM as '3fgm', 3FGA, or2, dr, ass,stl, to2, blk, blka, cm, rv, $indexT, ' ' as '+/-' from TeamStats ps join Game g on g.gameCode = ps.gameCode and g.season = ps.season where ps.gameCode = 1 and ps.season = 2016 and g.TeamH = ps.teamId ";
$poruka->team1Total = $conn->query($sql)->fetch_assoc();


$sql="select 'TOTAL' as 'playerName', ' ' as 'min2', PTS as 'pts',  2FGM as '2fgm', 2FGA, 3FGM as '3fgm', 3FGA, or2, dr, ass,stl, to2, blk, blka as 'blga', cm, rv, $indexT, ' ' as '+/-' from TeamStats ps join Game g on g.gameCode = ps.gameCode and g.season = ps.season where ps.gameCode = 1 and ps.season = 2016 and g.TeamA = ps.teamId ";
$poruka->team2Total = $conn->query($sql)->fetch_assoc();

echo json_encode($poruka);

