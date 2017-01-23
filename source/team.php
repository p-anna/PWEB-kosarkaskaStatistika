<?php
/**
 * Created by PhpStorm.
 * User: tosa
 * Date: 22.1.17.
 * Time: 16.44
 */


$conn = mysqli_connect('localhost','root','root','mydb');

if(mysqli_connect_errno())
{
    printf("SQL CONNECT ERROR: %s\n", mysqli_connect_error());
}


$filter = "";

$meseci = array("January" => "1", "February" => "2", "March" => "3", "April" => "4", "May" => "5", "June" => "6", "July"=>"7", "August"=>"8", "September"=>"9", "October"=>"10", "November"=>"11" ,"Decebmer" => "12");

$b=$_GET['seasonMonth'];
//$b = "October";
if($b != 'null'){
    $filter = $filter . " and exists (select * from Game g1 where month(g1.dateOfGame)= '$meseci[$b]' and g1.gameCode = ts.gameCode and g1.season = ts.season)";
}


//$a = "2016";
//  2016 2015 ....
$a=$_GET['season'];
if($a != 'null'){
    $filter = $filter . " and ts.season = $a";
}

$c = $_GET['teamId'];



$sql = "select distinct ts2.gameCode as 'gameCode', ts.season as 'season', if(ts.teamId = (select teamH from Game g where g.gameCode = ts.gameCode and ts.season = g.season), 'H', 'A') as HOME, t2.teamName as ATeam, ts.PTS as PTS ";
$newsql= ", ts.FTM as FTM, ts.FTA as FTA, round((ts.FTM*100)/(ts.FTM + ts.FTA)) as 1P";
$sql = $sql.$newsql;
$newsql= ", ts.2FGM as 2FGM, ts.2FGA as 2FGA, round((ts.2FGM*100)/(ts.2FGM + ts.2FGA)) as 2P";
$sql = $sql.$newsql;
$newsql= ", ts.3FGM as 3FGM, ts.3FGA as 3FGA, round((ts.3FGM*100)/(ts.3FGM + ts.3FGA)) as 3P";
$sql = $sql.$newsql;
$newsql= ", ts.OR2 as OR2, ts.ASS as ASS, ts.TO2 as TO2, ts.STL as STL, ts.BLK as BLK, ts.BLKA as BLKA, ts.CM as CM, ts.RV as RV";
$sql = $sql.$newsql;
$sqlnew = " from TeamStats ts join TeamStats ts2 on ts.gameCode = ts2.gameCode and ts.season = ts2.season join Team t2 where ts2.teamId = t2.idTeam and trim(ts.teamId) = trim('$c') and ts.teamId != ts2.teamId ";
$sql = $sql.$sqlnew;

$sqlnew = " $filter ";
$sql = $sql.$sqlnew;

$header = array();
$h0 = new stdClass(); $h0->name="H/A"; $h0->nameOfProperty="HOME";
array_push($header, $h0);
$h1 = new stdClass(); $h1->name="Name"; $h1->nameOfProperty="ATeam";
array_push($header, $h1);
$h2 = new stdClass(); $h2->name="PTS"; $h2->nameOfProperty="PTS";
array_push($header, $h2);

$h3 = new stdClass(); $h3->name="FTM"; $h3->nameOfProperty="FTM";
array_push($header, $h3);
$h4 = new stdClass(); $h4->name="FTA"; $h4->nameOfProperty="FTA";
array_push($header, $h4);
$h5 = new stdClass(); $h5->name="P %"; $h5->nameOfProperty="P%";
array_push($header, $h5);

$h6 = new stdClass(); $h6->name="2FGM"; $h6->nameOfProperty="2FGM";
array_push($header, $h6);
$h7 = new stdClass(); $h7->name="2FGA"; $h7->nameOfProperty="2FGA";
array_push($header, $h7);
$h8 = new stdClass(); $h8->name="2P %"; $h8->nameOfProperty="2P";
array_push($header, $h8);

$h9 = new stdClass(); $h9->name="3FGM"; $h9->nameOfProperty="3FGM";
array_push($header, $h9);
$h10 = new stdClass(); $h10->name="3FGA"; $h10->nameOfProperty="3FGA";
array_push($header, $h10);
$h11 = new stdClass(); $h11->name="3P %"; $h11->nameOfProperty="3P";
array_push($header, $h11);


$h12 = new stdClass(); $h12->name="OR"; $h12->nameOfProperty="OR2";
array_push($header, $h12);
$h13 = new stdClass(); $h13->name="ASS"; $h13->nameOfProperty="ASS";
array_push($header, $h13);
$h14 = new stdClass(); $h14->name="TO"; $h14->nameOfProperty="TO2";
array_push($header, $h14);
$h15 = new stdClass(); $h15->name="STL"; $h15->nameOfProperty="STL";
array_push($header, $h15);
$h16 = new stdClass(); $h16->name="BLK"; $h16->nameOfProperty="BLK";
array_push($header, $h16);
$h17 = new stdClass(); $h17->name="BLKA"; $h17->nameOfProperty="BLKA";
array_push($header, $h17);
$h18 = new stdClass(); $h18->name="CM"; $h18->nameOfProperty="CM";
array_push($header, $h18);
$h19 = new stdClass(); $h19->name="RV"; $h19->nameOfProperty="RV";
array_push($header, $h19);
//
//
//echo $sql;
//echo "\n";
//


//
//$result = mysqli_query($link,$sql);
//$json = array();
//for($i=0; $i<mysqli_num_rows($result);$i++) {
//    //$t = mysqli_fetch_object($result);
//    array_push($json, mysqli_fetch_object($result));
//    //echo $t;
//}
//echo json_encode($json);

$poruka = new stdClass();
$poruka->header = $header;
$tabela = array();

$rez = $conn->query($sql);

//echo var_dump($sql);
//*echo var_dump($rez);
//exit(0);

$i = 0;
for($i = 0; $i < $rez->num_rows; $i++){
    array_push($tabela, $rez->fetch_assoc());
}

$poruka->teamName = $conn->query("select teamName from Team where trim(idTeam)=trim('$c')")->fetch_assoc();
$poruka->players = $tabela;
echo json_encode($poruka);
