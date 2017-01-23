<?php
/**
 * Created by PhpStorm.
 * User: tosa
 * Date: 22.1.17.
 * Time: 20.14
 */

$conn = mysqli_connect('localhost','root','root','mydb');

if(mysqli_connect_errno())
{
    printf("SQL CONNECT ERROR: %s\n", mysqli_connect_error());
}

$meseci = array("January" => "1", "February" => "2", "March" => "3", "April" => "4", "May" => "5", "June" => "6", "July"=>"7", "August"=>"8", "September"=>"9", "October"=>"10", "November"=>"11" ,"Decebmer" => "12");

$filter = "";
//$b = 'October';
$b=$_GET['seasonMonth'];
if($b != 'null'){
    $filter = $filter . " and exists (select * from Game g1 where month(g1.dateOfGame)= '$meseci[$b]' and g1.gameCode = ts1.gameCode and g1.season = ts1.season)";
}
//$a = "2016";
//  2016 2015 ....
$a=$_GET['season'];
if($a != 'null'){
    $filter = $filter . " and ts1.season = $a";
}

$header = array();

$sql = "select distinct ts1.gameCode as 'gameCode', ts1.season as 'season', round, t1.teamName as team1, t2.teamName as team2, ts1.pts as ptsH, ts2.pts as ptsA, dateOfGame as dateO, attendance as att, stadium as arena, r1.refereeName as ref1, r2.refereeName as ref2, r3.refereeName as ref3 from Game g, Referee r1, Referee r2, Referee r3, TeamStats ts1, TeamStats ts2, Team t1 , Team t2 where r1.idReferee = g.ref1Id and r2.idReferee = g.ref2Id and r3.idReferee = g.ref3Id and g.gameCode = ts1.gameCode and g.gameCode = ts2.gameCode and ts1.teamId=g.teamH and ts2.teamId=g.teamA and t1.idTeam=ts1.teamId and t2.idTeam = ts2.teamId";

$h0 = new stdClass(); $h0->name="Round"; $h0->nameOfProperty="round";
array_push($header, $h0);
$h1 = new stdClass(); $h1->name="TeamH"; $h1->nameOfProperty="team1";
array_push($header, $h1);
$h2 = new stdClass(); $h2->name="TeamA"; $h2->nameOfProperty="team2";
array_push($header, $h2);
$h3 = new stdClass(); $h3->name="PtsH"; $h3->nameOfProperty="ptsH";
array_push($header, $h3);
$h4 = new stdClass(); $h4->name="PtsA"; $h4->nameOfProperty="ptsA";
array_push($header, $h4);
$h3 = new stdClass(); $h3->name="Date"; $h3->nameOfProperty="date";
array_push($header, $h3);
$h4 = new stdClass(); $h4->name="Att"; $h4->nameOfProperty="att";
array_push($header, $h4);
$h3 = new stdClass(); $h3->name="Arena"; $h3->nameOfProperty="arena";
array_push($header, $h3);
$h4 = new stdClass(); $h4->name="Ref1"; $h4->nameOfProperty="ref1";
array_push($header, $h4);
$h4 = new stdClass(); $h4->name="Ref2"; $h4->nameOfProperty="ref2";
array_push($header, $h4);
$h4 = new stdClass(); $h4->name="Ref3"; $h4->nameOfProperty="ref3";
array_push($header, $h4);

$sqlnew = " $filter ";
$sql = $sql.$sqlnew;


$poruka = new stdClass();
$poruka->header = $header;
$tabela = array();
//echo var_dump($sql);
$rez = $conn->query($sql);
//echo var_dump($rez);
//exit(0);
$i = 0;
for($i = 0; $i < $rez->num_rows; $i++){
    array_push($tabela, $rez->fetch_assoc());
}
$poruka->teams = $tabela;
echo json_encode($poruka);
