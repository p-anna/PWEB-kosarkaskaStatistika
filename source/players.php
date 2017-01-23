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
        $sql = "select p.playerName, count(*) as GP, avg(ps.pts) as PPG, avg(ps.FTM) as FTM, avg(ps.FTA) as FTA, avg((ps.FTM/ps.FTA)*100) as P1, avg(ps.2FGM) as 2FGM, avg(ps.2FGA) as 2FGA, avg((ps.2FGM/ps.2FGA)*100) as P2, avg(ps.3FGM) as 3FGM, avg(ps.3FGA) as 3FGA, avg((ps.3FGM/ps.3FGA)*100) as P3 from playerStats ps join player p on p.idPlayer=ps.playerId $filter group By ps.playerId order by 2 desc";


        $h0 = new stdClass(); $h0->name="Name"; $h0->nameOfProperty="playerName";
        array_push($header, $h0);
        $h1 = new stdClass(); $h1->name="GP"; $h1->nameOfProperty="GP";
        array_push($header, $h1);
        $h2 = new stdClass(); $h2->name="PPG"; $h2->nameOfProperty="PPG";
        array_push($header, $h2);

        $h0 = new stdClass(); $h0->name="FTM"; $h0->nameOfProperty="FTM";
        array_push($header, $h0);
        $h1 = new stdClass(); $h1->name="FTA"; $h1->nameOfProperty="FTA";
        array_push($header, $h1);
        $h2 = new stdClass(); $h2->name="FT%"; $h2->nameOfProperty="P1";
        array_push($header, $h2);


        $h0 = new stdClass(); $h0->name="2FGM"; $h0->nameOfProperty="2FGM";
        array_push($header, $h0);
        $h1 = new stdClass(); $h1->name="2FGA"; $h1->nameOfProperty="2FGA";
        array_push($header, $h1);
        $h2 = new stdClass(); $h2->name="2FG%"; $h2->nameOfProperty="P2";
        array_push($header, $h2);

        $h0 = new stdClass(); $h0->name="3FGM"; $h0->nameOfProperty="3FGM";
        array_push($header, $h0);
        $h1 = new stdClass(); $h1->name="3FGA"; $h1->nameOfProperty="3FGA";
        array_push($header, $h1);
        $h2 = new stdClass(); $h2->name="3FG%"; $h2->nameOfProperty="P2";
        array_push($header, $h2);


        break;
        /*
        $sql = "select p.idPlayer, p.playerName, count(*) as 'GP', avg(pts) as 'PPG' from playerStats join player p on p.idPlayer=playerId $filter group By playerId order by 2 desc";
        $h0 = new stdClass(); $h0->name="Name"; $h0->nameOfProperty="playerName";
        array_push($header, $h0);
        $h1 = new stdClass(); $h1->name="GP"; $h1->nameOfProperty="GP";
        array_push($header, $h1);
        $h2 = new stdClass(); $h2->name="PPG"; $h2->nameOfProperty="PPG";
        array_push($header, $h2);
        break;
        */
    case "Accumulated Statistics":
        $sql = "select count(*) as 'GP', sum(pts) as 'PTS' from playerStats $filter groupBy playerId";
        break;
    case "Advanced Statistics":
        $sql = "select p.playerName, sum(min2) as MIN2, (sum(2fgm)+0.5*sum(3fgm))/(sum(2fga)+sum(3fga))*100 as 'eFGP', " .
            " sum(or2)/(sum(teamOffReb)+sum(teamOppDefReb))*100 as 'ORP', " .
            " sum(dr) /(sum(teamDefReb)+sum(teamOppOffReb))*100 as 'DRP', " .
            " sum(dr+or2)/(sum(teamOffReb+teamDefReb)+sum(teamOppOffReb+teamOppDefReb))*100 as 'TRP', " .
            " sum(ass)/sum(to2) as 'ASS/TO', " .
            //100 * ((FGA + 0.44 * FTA + TOV) * (Tm MP / 5)) / (MP * (Tm FGA + 0.44 * Tm FTA + Tm TOV))
            " 100 * ((sum(2fga+3fga) + 0.44 * sum(fta) + to2) * sum(40)) / (sum(min2) * sum(teamPoints+35)) as 'USG' " .
            " from playerstats join player p on p.idPlayer = playerId $filter group by playerId" ;
        $h0 = new stdClass(); $h0->name="Name"; $h0->nameOfProperty="playerName";
        array_push($header, $h0);
        $h0 = new stdClass(); $h0->name="MIN"; $h0->nameOfProperty="MIN2";
        array_push($header, $h0);
        $h1 = new stdClass(); $h1->name="eFG%"; $h1->nameOfProperty="eFGP";
        array_push($header, $h1);
        $h2 = new stdClass(); $h2->name="OR%"; $h2->nameOfProperty="ORP";
        array_push($header, $h2);
        $h2 = new stdClass(); $h2->name="DR%"; $h2->nameOfProperty="DRP";
        array_push($header, $h2);
        $h2 = new stdClass(); $h2->name="TR%"; $h2->nameOfProperty="TRP";
        array_push($header, $h2);
        $h2 = new stdClass(); $h2->name="USG%"; $h2->nameOfProperty="USG";
        array_push($header, $h2);

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
