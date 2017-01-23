<?php
/**
 * Created by PhpStorm.
 * User: msuko
 * Date: 23/01/2017
 * Time: 00:27
 */

$conn = new mysqli("localhost", "root", "root", "mydb");

$filter = "where true ";

$a = $_GET['idReferee'];
if($a != 'null'){
    $filter = $filter . " and (ref1Id = $a or ref2Id= $a or ref3Id=$a)";
}

$a = $_GET['idTeam'];
if($a != 'null'){
    $filter = $filter . " and (teamH = '$a' or teamA = '$a')";
}

$sql = "select t1.teamName as 'Host', t2.teamName as 'Away', concat(ts1.PTS, ' - ', ts2.PTS) as 'Result', concat(ts1.FTA, ' - ', ts2.FTA) as 'FTS', concat(ts1.CM, ' - ', ts2.CM) as 'Fouls' " .
      " from game g join team t1 on t1.idTeam = teamH join team t2 on t2.idTeam = teamA " .
      " join teamstats ts1 on ts1.gamecode = g.gamecode and ts1.season = g.season and ts1.teamId = teamH" .
      " join teamstats ts2 on ts2.gamecode = g.gamecode and ts2.season = g.season and ts2.teamId = teamA $filter";

$header = array();
$h = new stdClass(); $h->name = 'Host'; $h->nameOfProperty = 'Host';
array_push($header, $h);
$h = new stdClass(); $h->name = 'Away'; $h->nameOfProperty = 'Away';
array_push($header, $h);
$h = new stdClass(); $h->name = 'Result'; $h->nameOfProperty = 'Result';
array_push($header, $h);
$h = new stdClass(); $h->name = 'Free Throws'; $h->nameOfProperty = 'FTS';
array_push($header, $h);
$h = new stdClass(); $h->name = 'Fouls'; $h->nameOfProperty = 'Fouls';
array_push($header, $h);

$poruka = new stdClass();
$poruka->header = $header;
$rez = $conn->query($sql);

$obj = array();
for($i = 0; $i < $rez->num_rows; $i++){
    array_push($obj, $rez->fetch_assoc());
}
$poruka->games = $obj;
echo json_encode($poruka);