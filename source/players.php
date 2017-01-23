<?php
/**
 * Created by PhpStorm.
 * User: msuko
 * Date: 22/01/2017
 * Time: 13:14
 */

//echo var_dump($_GET);
//exit(0);
$conn = new mysqli("localhost", "root", "root", "mydb");
$filter = "where true";
$a=$_GET['idTeam'];
if($a != 'null'){
    $filter = $filter . " and trim(teamId)=trim('$a')";
}
$a = $_GET['position'];
if($a != 'null'){
    $filter = $filter . " and exists (select * from Player p where p.idPlayer = playerId and p.playerPos = '$a')";
}

$sql="";
$header = array();
switch($_GET['statisticType']){
    case "Average | Per Game":
        $sql = "select p.playerName, count(*) as 'GP', avg(pts) as 'PPG' from playerStats join player p on p.idPlayer=playerId $filter group By playerId order by 2 desc";
        $h0 = new stdClass(); $h0->name="Name"; $h0->nameOfProperty="playerName";
        array_push($header, $h0);
        $h1 = new stdClass(); $h1->name="GP"; $h1->nameOfProperty="GP";
        array_push($header, $h1);
        $h2 = new stdClass(); $h2->name="PPG"; $h2->nameOfProperty="PPG";
        array_push($header, $h2);
        break;
    case "Accumulated Statistics":
        $sql = "select count(*) as 'GP', sum(pts) as 'PTS' from playerStats where $filter groupBy playerId order by 2 desc";
        break;
    case "Advanced Statistics":


        //Effective Field Goal Percentage; the formula is (FG + 0.5 * 3P) / FGA.
    $sql = "select sum(min2) as MIN, ";

}
//echo $sql;
//echo var_dump($header);
//exit(0);

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
$poruka->players = $tabela;
echo json_encode($poruka);
