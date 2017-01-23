<?php
/**
 * Created by PhpStorm.
 * User: msuko
 * Date: 23/01/2017
 * Time: 00:01
 */

$id = $_GET['idPlayer'];
//$id = 'P000046';
    //switch($argc)

$conn = new mysqli("localhost", "root", "root", "mydb");
$sql = "select * from Player where trim(idPlayer)=trim('$id')";
$result = $conn->query($sql);


$poruka = new stdClass();
$json = mysqli_fetch_assoc($result);
$poruka->info = $json;

$header = array();

$index = "ps.PTS - ps.2FGA - ps.3FGA - ps.FTA + ps.OR2 + ps.DR + ps.ASS + ps.STL - ps.TO2 + ps.BLK - ps.BLGA - ps.CM +ps.RV as 'PIR' ";
$sql = "select concat('vs ',t.teamName) as teamName, ps.MIN2 as MIN2, ps.PTS as PTS, ps.FTM as FTM, ps.FTA as FTA, (ps.FTM/ps.FTA)*100 as P1, ps.2FGM, ps.2FGA, (ps.2FGM/ps.2FGA)*100 as P2 ,ps.3FGM, ps.3FGA, (ps.3FGM/ps.3FGA)*100 as P3 ,ps.OR2, ps.DR,ps.ASS, ps.STL, ps.TO2, ps.BLK, ps.BLGA, ps.CM, ps.RV, $index,(ps.teamPoints-ps.teamOppPoints) as '+/-' from Game gg join PlayerStats ps on gg.season = ps.season and gg.gameCode = ps.gameCode join Team t on t.idTeam = gg.teamA where ps.teamId != gg.teamA and trim(ps.playerId) = '$id' union select concat('vs ',t.teamName) as teamName, ps.MIN2 as MIN2, ps.PTS as PTS, ps.FTM as FTM, ps.FTA as FTA, (ps.FTM/ps.FTA)*100 as P1, ps.2FGM, ps.2FGA, (ps.2FGM/ps.2FGA)*100 as P2 ,ps.3FGM, ps.3FGA, (ps.3FGM/ps.3FGA)*100 as P3 ,ps.OR2, ps.DR,ps.ASS, ps.STL, ps.TO2, ps.BLK, ps.BLGA, ps.CM, ps.RV, $index,(ps.teamPoints-ps.teamOppPoints) as '+/-' from Game gg join PlayerStats ps on gg.season = ps.season and gg.gameCode = ps.gameCode join Team t on t.idTeam = gg.teamH where ps.teamId != gg.teamH and trim(ps.playerId) = '$id' ";

$h0 = new stdClass(); $h0->name="Game"; $h0->nameOfProperty="teamName";
array_push($header, $h0);
$h0 = new stdClass(); $h0->name="MIN"; $h0->nameOfProperty="MIN2";
array_push($header, $h0);
$h2 = new stdClass(); $h2->name="PTS"; $h2->nameOfProperty="PTS";
array_push($header, $h2);
$h3 = new stdClass(); $h3->name="FTM"; $h3->nameOfProperty="FTM";
array_push($header, $h3);
$h4 = new stdClass(); $h4->name="FTA"; $h4->nameOfProperty="FTA";
array_push($header, $h4);
$h4 = new stdClass(); $h4->name="FT %"; $h4->nameOfProperty="P1";

$h3 = new stdClass(); $h3->name="2FGM"; $h3->nameOfProperty="2FGM";
array_push($header, $h3);
$h4 = new stdClass(); $h4->name="2FGA"; $h4->nameOfProperty="2FGA";
array_push($header, $h4);

$h4 = new stdClass(); $h4->name="2P %"; $h4->nameOfProperty="P2";
array_push($header, $h4);
$h5 = new stdClass(); $h5->name="3FGM"; $h5->nameOfProperty="3FGM";
array_push($header, $h5);
$h6 = new stdClass(); $h6->name="3FGA"; $h6->nameOfProperty="3FGA";
array_push($header, $h6);

$h4 = new stdClass(); $h4->name="3P %"; $h4->nameOfProperty="P3";
array_push($header, $h4);
$h7 = new stdClass(); $h7->name="OR"; $h7->nameOfProperty="OR2";
array_push($header, $h7);
$h8 = new stdClass(); $h8->name="DR"; $h8->nameOfProperty="DR";
array_push($header, $h8);
$h9 = new stdClass(); $h9->name="ASS"; $h9->nameOfProperty="ASS";
array_push($header, $h9);
$h10 = new stdClass(); $h10->name="STL"; $h10->nameOfProperty="STL";
array_push($header, $h10);
$h11 = new stdClass(); $h11->name="TO"; $h11->nameOfProperty="TO2";
array_push($header, $h11);
$h12 = new stdClass(); $h12->name="BLK"; $h12->nameOfProperty="BLK";
array_push($header, $h12);
$h13 = new stdClass(); $h13->name="BLKA"; $h13->nameOfProperty="BLGA";
array_push($header, $h13);
$h14 = new stdClass(); $h14->name="CM"; $h14->nameOfProperty="CM";
array_push($header, $h14);
$h15 = new stdClass(); $h15->name="RV"; $h15->nameOfProperty="RV";
array_push($header, $h15);
$h16 = new stdClass(); $h16->name="PIR"; $h16->nameOfProperty="PIR";
array_push($header, $h16);
$h17 = new stdClass(); $h17->name="+/-"; $h17->nameOfProperty="+/-";
array_push($header, $h17);

$rez = $conn->query($sql);
//echo var_dump($sql);
//exit(0);

$stats = array();
for($i = 0; $i < $rez->num_rows; $i++)
    array_push($stats, $rez->fetch_assoc());
$poruka->stats=$stats;

echo json_encode($poruka);