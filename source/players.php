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


$meseci = array("January" => "1", "February" => "2", "March" => "3", "April" => "4", "May" => "5", "June" => "6", "July"=>"7", "August"=>"8", "September"=>"9", "October"=>"10", "November"=>"11" ,"Decebmer" => "12");

//$b = 'October';
$b=$_GET['seasonPart'];
if($b != 'null'){
    $filter = $filter . " and exists (select * from Game g1 where month(g1.dateOfGame)= '$meseci[$b]' and g1.gameCode = ps.gameCode and g1.season = ps.season)";
}

$sql="";
$index = "";
$header = array();
switch($_GET['statisticType']){
    case "Average | Per Game":
        $index = " round((avg(ps.PTS) - avg(ps.2FGA) - avg(ps.3FGA) - avg(ps.FTA) + avg(ps.OR2) + avg(ps.DR) + avg(ps.ASS) + avg(ps.STL) - avg(ps.TO2) + avg(ps.BLK) - avg(ps.BLGA) - avg(ps.CM) +avg(ps.RV)),1) as 'PIR'";
        $sql = "select ps.playerId as idPlayer, p.playerName as playerName, count(*) as GP, round(avg(ps.pts),1) as PPG, round(avg(ps.FTM),1) as FTM, round(avg(ps.FTA),1) as FTA, round(avg((ps.FTM/ps.FTA)*100),2) as P1"
            . ", round(avg(ps.2FGM),1) as 2FGM, round(avg(ps.2FGA),1) as 2FGA, round(avg((ps.2FGM/ps.2FGA)*100),2) as P2, round(avg(ps.3FGM),1) as 3FGM "
            . ", round(avg(ps.3FGA),1) as 3FGA, round(avg((ps.3FGM/ps.3FGA)*100),2) as P3 "
            . ", round(avg(ps.OR2),1) as OR2, round(avg(ps.DR),1) as DR, round(avg(ps.ASS),1) as ASS "
            . ", round(avg(ps.TO2),1) as TO2, round(avg(ps.STL),1) as STL, round(avg(ps.BLK),1) as BLK, round(avg(ps.BLGA),1) as BLKA "
            . ", round(avg(ps.CM),1) as CM, round(avg(ps.RV),1) as RV, $index, round((avg(ps.teamPoints)-avg(ps.teamOppPoints))) as '+/-' "
        . " from playerStats ps join player p on p.idPlayer=ps.playerId $filter group By ps.playerId order by 2 desc";


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

        $h11 = new stdClass(); $h11->name="OR"; $h11->nameOfProperty="OR2";
        array_push($header, $h11);

        $h11 = new stdClass(); $h11->name="DR"; $h11->nameOfProperty="DR";
        array_push($header, $h11);
        $h12 = new stdClass(); $h12->name="ASS"; $h12->nameOfProperty="ASS";
        array_push($header, $h12);
        $h13 = new stdClass(); $h13->name="TO"; $h13->nameOfProperty="TO2";
        array_push($header, $h13);
        $h14 = new stdClass(); $h14->name="STL"; $h14->nameOfProperty="STL";
        array_push($header, $h14);
        $h15 = new stdClass(); $h15->name="BLK"; $h15->nameOfProperty="BLK";
        array_push($header, $h15);
        $h16 = new stdClass(); $h16->name="BLKA"; $h16->nameOfProperty="BLKA";
        array_push($header, $h16);


        $h15 = new stdClass(); $h15->name="CM"; $h15->nameOfProperty="CM";
        array_push($header, $h15);
        $h16 = new stdClass(); $h16->name="RV"; $h16->nameOfProperty="RV";
        array_push($header, $h16);

        $h16 = new stdClass(); $h16->name="PIR"; $h16->nameOfProperty="PIR";
        array_push($header, $h16);

        $h16 = new stdClass(); $h16->name="+/-"; $h16->nameOfProperty="+/-";
        array_push($header, $h16);

        break;

    case "Accumulated Statistics":
        $index = " sum(ps.PTS) - sum(ps.2FGA) - sum(ps.3FGA) - sum(ps.FTA) + sum(ps.OR2) + sum(ps.DR) + sum(ps.ASS) + sum(ps.STL) - sum(ps.TO2) + sum(ps.BLK) - sum(ps.BLGA) - sum(ps.CM) +sum(ps.RV) as 'PIR' ";
        $sql = "select ps.playerId as idPlayer, p.playerName as playerName,  sum(PTS) as PTS "
            . ", sum(ps.FTM) as FTM, sum(ps.FTA) as FTA, round((sum(ps.FTM)/sum(ps.FTA)*100),2) as P1"
            . ", sum(ps.2FGM) as 2FGM, sum(ps.2FGA) as 2FGA, round((sum(ps.2FGM)/sum(ps.2FGA)*100),2) as P2"
            . ", sum(ps.3FGM) as 3FGM "
            . ", sum(ps.3FGA) as 3FGA, round((sum(ps.3FGM)/sum(ps.3FGA)*100),2) as P3 "
            . ", sum(ps.OR2) as OR2, sum(ps.DR) as DR, sum(ps.ASS) as ASS "
            . ", sum(ps.TO2) as TO2, sum(ps.STL) as STL, sum(ps.BLK) as BLK, sum(ps.BLGA) as BLKA "
            . ", sum(ps.CM) as CM, sum(ps.RV) as RV , $index, (sum(ps.teamPoints)-sum(ps.teamOppPoints)) as '+/-' "
        . " from playerStats ps join player p on p.idPlayer=ps.playerId $filter  group by ps.playerId";

        $h0 = new stdClass(); $h0->name="Name"; $h0->nameOfProperty="playerName";
        array_push($header, $h0);
       // $h1 = new stdClass(); $h1->name="GP"; $h1->nameOfProperty="GP";
       // array_push($header, $h1);
        $h2 = new stdClass(); $h2->name="PTS"; $h2->nameOfProperty="PTS";
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

        $h11 = new stdClass(); $h11->name="OR"; $h11->nameOfProperty="OR2";
        array_push($header, $h11);

        $h11 = new stdClass(); $h11->name="DR"; $h11->nameOfProperty="DR";
        array_push($header, $h11);
        $h12 = new stdClass(); $h12->name="ASS"; $h12->nameOfProperty="ASS";
        array_push($header, $h12);
        $h13 = new stdClass(); $h13->name="TO"; $h13->nameOfProperty="TO2";
        array_push($header, $h13);
        $h14 = new stdClass(); $h14->name="STL"; $h14->nameOfProperty="STL";
        array_push($header, $h14);
        $h15 = new stdClass(); $h15->name="BLK"; $h15->nameOfProperty="BLK";
        array_push($header, $h15);
        $h16 = new stdClass(); $h16->name="BLKA"; $h16->nameOfProperty="BLKA";
        array_push($header, $h16);


        $h15 = new stdClass(); $h15->name="CM"; $h15->nameOfProperty="CM";
        array_push($header, $h15);
        $h16 = new stdClass(); $h16->name="RV"; $h16->nameOfProperty="RV";
        array_push($header, $h16);

        $h16 = new stdClass(); $h16->name="PIR"; $h16->nameOfProperty="PIR";
        array_push($header, $h16);

        $h16 = new stdClass(); $h16->name="+/-"; $h16->nameOfProperty="+/-";
        array_push($header, $h16);
        break;
    case "Advanced Statistics":
        $sql = "select playerId as idPlayer, p.playerName, sum(min2) as MIN2, round(((sum(2fgm)+0.5*sum(3fgm))/(sum(2fga)+sum(3fga))*100),2) as 'eFGP', " .
            " round((sum(or2)/(sum(teamOffReb)+sum(teamOppDefReb))*100),2) as 'ORP', " .
            " round((sum(dr) /(sum(teamDefReb)+sum(teamOppOffReb))*100),2) as 'DRP', " .
            " round((sum(dr+or2)/(sum(teamOffReb+teamDefReb)+sum(teamOppOffReb+teamOppDefReb))*100),2) as 'TRP', " .
            " round((sum(ass)/sum(to2)),2) as 'ASS/TO', " .
            //100 * ((FGA + 0.44 * FTA + TOV) * (Tm MP / 5)) / (MP * (Tm FGA + 0.44 * Tm FTA + Tm TOV))
            " round((100 * ((sum(2fga+3fga) + 0.44 * sum(fta) + to2) * sum(40)) / (sum(min2) * sum(teamPoints+35))),2) as 'USG' " .
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
