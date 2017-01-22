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
$a = $_GET['season'];
$filter = $filter . " and season = $a";

$sql="";
$header = array();
switch($_GET['statisticType']){
    case "Average | Per Game":
        $sql = "select p.idPlayer, p.playerName, count(*) as 'GP', avg(pts) as 'PPG', " .
               "avg(2fgm) as '2FGM', avg(2fgm)+avg(2fga) as '2FGA', avg(2fgm)/(avg(2fgm)+avg(2fga))*100 as '2FGP', " .
               "avg(3fgm) as '3FGM', avg(3fgm)+avg(3fga) as '3FGA', avg(3fgm)/(avg(3fgm)+avg(3fga))*100 as '3FGP', " .
               "avg(ftm) as 'FTM', avg(ftm)+avg(fta) as 'fta', avg(ftm)/(avg(ftm)+avg(fta))*100 as 'FTP', " .
               "avg(ass) as 'ASS', avg(stl) as 'STL', avg(to2) as 'TO2', avg(or2) as 'OFF', avg(dr) as 'DEF', avg(or2)+avg(dr) as 'REB', " .
               "avg(blk) as 'BLK', avg(blga) as 'BLKA', avg(cm) as 'PF', avg(rv) as 'RV', " .
               "avg(pts) - avg(2fga) - avg(3fga) - avg(fta) + avg(ass) + avg(stl) - avg(to2) + avg(or2) + avg(dr) + avg(blk)" .
               " - avg(blga) - avg(cm) + avg(rv) as 'PIR'" .
            " from playerStats join player p on p.idPlayer=playerId $filter group By playerId order by 2 desc";
        $h0 = new stdClass(); $h0->name="Name"; $h0->nameOfProperty="playerName";
        array_push($header, $h0);
        $h1 = new stdClass(); $h1->name="GP"; $h1->nameOfProperty="GP";
        array_push($header, $h1);
        $h2 = new stdClass(); $h2->name="PPG"; $h2->nameOfProperty="PPG";
        array_push($header, $h2);
        $h3 = new stdClass(); $h3->name="2FGM"; $h3->nameOfProperty="2FGM";
        array_push($header, $h3);
        $h4 = new stdClass(); $h4->name="2FGA"; $h4->nameOfProperty="2FGA";
        array_push($header, $h4);
        $h4 = new stdClass(); $h4->name="2FG%"; $h4->nameOfProperty="2FGP";
        array_push($header, $h4);
        $h5 = new stdClass(); $h5->name="3FGM"; $h5->nameOfProperty="3FGM";
        array_push($header, $h5);
        $h5 = new stdClass(); $h5->name="3FGA"; $h5->nameOfProperty="3FGA";
        array_push($header, $h5);
        $h5 = new stdClass(); $h5->name="3FG%"; $h5->nameOfProperty="3FGP";
        array_push($header, $h5);
        $h5 = new stdClass(); $h5->name="FTM"; $h5->nameOfProperty="FTM";
        array_push($header, $h5);
        $h5 = new stdClass(); $h5->name="FTA"; $h5->nameOfProperty="FTA";
        array_push($header, $h5);
        $h5 = new stdClass(); $h5->name="FT%"; $h5->nameOfProperty="FTP";
        array_push($header, $h5);
        $h5 = new stdClass(); $h5->name="ASS"; $h5->nameOfProperty="ASS";
        array_push($header, $h5);
        $h5 = new stdClass(); $h5->name="STL"; $h5->nameOfProperty="STL";
        array_push($header, $h5);
        $h5 = new stdClass(); $h5->name="TO"; $h5->nameOfProperty="TO2";
        array_push($header, $h5);
        $h5 = new stdClass(); $h5->name="OFF"; $h5->nameOfProperty="OFF";
        array_push($header, $h5);
        $h5 = new stdClass(); $h5->name="DEF"; $h5->nameOfProperty="DEF";
        array_push($header, $h5);
        $h5 = new stdClass(); $h5->name="REB"; $h5->nameOfProperty="REB";
        array_push($header, $h5);
        $h5 = new stdClass(); $h5->name="BS"; $h5->nameOfProperty="BS";
        array_push($header, $h5);
        $h5 = new stdClass(); $h5->name="BA"; $h5->nameOfProperty="BA";
        array_push($header, $h5);
        $h5 = new stdClass(); $h5->name="PF"; $h5->nameOfProperty="PF";
        array_push($header, $h5);
        $h5 = new stdClass(); $h5->name="RV"; $h5->nameOfProperty="RV";
        array_push($header, $h5);
        $h5 = new stdClass(); $h5->name="PIR"; $h5->nameOfProperty="PIR";
        array_push($header, $h5);
        break;
    case "Accumulated Statistics":
        $sql = "select count(*) as 'GP', sum(pts) as 'PTS' from playerStats where $filter groupBy playerId order by 2 desc";
        break;

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
