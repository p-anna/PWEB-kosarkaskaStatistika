<?php
/**
 * Created by PhpStorm.
 * User: tosa
 * Date: 22.1.17.
 * Time: 15.04
 */

$conn = mysqli_connect('localhost','root','root','mydb');

if(mysqli_connect_errno())
{
    printf("SQL CONNECT ERROR: %s\n", mysqli_connect_error());
}

$filter = "where true";

$meseci = array("January" => "1", "February" => "2", "March" => "3", "April" => "4", "May" => "5", "June" => "6", "July"=>"7", "August"=>"8", "September"=>"9", "October"=>"10", "November"=>"11" ,"Decebmer" => "12");

$b=$_GET['seasonMonth'];
if($b != 'null'){
    $filter = $filter . " and exists (select * from Game g1 where month(g1.dateOfGame)= '$meseci[$b]' and g1.gameCode = ts.gameCode and g1.season = ts.season)";
}
//$a = "2016";
//  2016 2015 ....
$a=$_GET['season'];
if($a != 'null'){
    $filter = $filter . " and season = $a";
}
$header = array();

switch($_GET['statisticType']){

    case "Average | Per Game":
    //case 0:
        $sql = "SELECT distinct ts.teamId, teamName, round(avg(PTS)) as PTS, round(avg(FTM)) as FTM, round(avg(FTA)) as FTA, round((avg(FTM)*100)/(avg(FTM) + avg(FTA))) as P ";
        $newsql= ", round(avg(2FGM)) as 2FGM, round(avg(2FGA)) as 2FGA, round((avg(2FGM)*100)/(avg(2FGM) + avg(2FGA))) as 2P";
        $sql = $sql.$newsql;
        $newsql= ", round(avg(3FGM)) as 3FGM, round(avg(3FGA)) as 3FGA, round((avg(3FGM)*100)/(avg(3FGM) + avg(3FGA))) as 3P";
        $sql = $sql.$newsql;
        $newsql= ", round(avg(OR2)) as OR2, round(avg(ASS)) as ASS, round(avg(TO2)) as TO2, round(avg(STL)) as STL, round(avg(BLK)) as BLK, round(avg(BLKA)) as BLKA, round(avg(CM)) as CM, round(avg(RV)) as RV";
        $sql = $sql.$newsql;
        $sqlnew = " FROM TeamStats ts join Team t on ts.teamId = t.idTeam ";
        $sql = $sql.$sqlnew;
        $sqlnew = " $filter group by ts.teamId";
        $sql = $sql.$sqlnew;
        $h0 = new stdClass(); $h0->name="Name"; $h0->nameOfProperty="teamName";
        array_push($header, $h0);
        $h1 = new stdClass(); $h1->name="PTS"; $h1->nameOfProperty="PTS";
        array_push($header, $h1);
        $h2 = new stdClass(); $h2->name="FTM"; $h2->nameOfProperty="FTM";
        array_push($header, $h2);
        $h3 = new stdClass(); $h3->name="FTA"; $h3->nameOfProperty="FTA";
        array_push($header, $h3);
        $h4 = new stdClass(); $h4->name="P%"; $h4->nameOfProperty="P";
        array_push($header, $h4);

        $h5 = new stdClass(); $h5->name="2FGM"; $h5->nameOfProperty="2FGM";
        array_push($header, $h5);
        $h6 = new stdClass(); $h6->name="2FGA"; $h6->nameOfProperty="2FGA";
        array_push($header, $h6);
        $h7 = new stdClass(); $h7->name="2P%"; $h7->nameOfProperty="2P";
        array_push($header, $h7);

        $h8 = new stdClass(); $h8->name="3FGM"; $h8->nameOfProperty="3FGM";
        array_push($header, $h8);
        $h9 = new stdClass(); $h9->name="3FGA"; $h9->nameOfProperty="3FGA";
        array_push($header, $h9);
        $h10 = new stdClass(); $h10->name="3P%"; $h10->nameOfProperty="3P";
        array_push($header, $h10);


        $h11 = new stdClass(); $h11->name="OR"; $h11->nameOfProperty="OR2";
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
        $h17 = new stdClass(); $h17->name="CM"; $h17->nameOfProperty="CM";
        array_push($header, $h17);
        $h18 = new stdClass(); $h18->name="RV"; $h18->nameOfProperty="RV";
        array_push($header, $h18);


        break;
    case "Accumulated Statistics":
    //case 1:
        $sql = "SELECT distinct teamName, round(sum(PTS)) as PTS, round(sum(FTM)) as FTM, round(sum(FTA)) as FTA, round((sum(FTM)*100)/(sum(FTM) + sum(FTA))) as P ";
        $newsql= ", round(sum(2FGM)) as 2FGM, round(sum(2FGA)) as 2FGA, round((sum(2FGM)*100)/(sum(2FGM) + sum(2FGA))) as 2P";
        $sql = $sql.$newsql;
        $newsql= ", round(sum(3FGM)) as 3FGM, round(sum(3FGA)) as 3FGA, round((sum(3FGM)*100)/(sum(3FGM) + sum(3FGA))) as 3P";
        $sql = $sql.$newsql;
        $newsql= ", round(sum(OR2)) as OR2, round(sum(ASS)) as ASS, round(sum(TO2)) as TO2, round(sum(STL)) as STL, round(sum(BLK)) as BLK, round(sum(BLKA)) as BLKA, round(sum(CM)) as CM, round(sum(RV)) as RV";
        $sql = $sql.$newsql;
        $sqlnew = " FROM TeamStats ts join Team t on ts.teamId = t.idTeam ";
        $sql = $sql.$sqlnew;
        $sqlnew = " $filter group by ts.teamId";
        $sql = $sql.$sqlnew;
        $h0 = new stdClass(); $h0->name="Name"; $h0->nameOfProperty="teamName";
        array_push($header, $h0);
        $h1 = new stdClass(); $h1->name="PTS"; $h1->nameOfProperty="PTS";
        array_push($header, $h1);
        $h2 = new stdClass(); $h2->name="FTM"; $h2->nameOfProperty="FTM";
        array_push($header, $h2);
        $h3 = new stdClass(); $h3->name="FTA"; $h3->nameOfProperty="FTA";
        array_push($header, $h3);
        $h4 = new stdClass(); $h4->name="P%"; $h4->nameOfProperty="P";
        array_push($header, $h4);

        $h5 = new stdClass(); $h5->name="2FGM"; $h5->nameOfProperty="2FGM";
        array_push($header, $h5);
        $h6 = new stdClass(); $h6->name="2FGA"; $h6->nameOfProperty="2FGA";
        array_push($header, $h6);
        $h7 = new stdClass(); $h7->name="2P%"; $h7->nameOfProperty="2P";
        array_push($header, $h7);

        $h8 = new stdClass(); $h8->name="3FGM"; $h8->nameOfProperty="3FGM";
        array_push($header, $h8);
        $h9 = new stdClass(); $h9->name="3FGA"; $h9->nameOfProperty="3FGA";
        array_push($header, $h9);
        $h10 = new stdClass(); $h10->name="3P%"; $h10->nameOfProperty="3P";
        array_push($header, $h10);


        $h11 = new stdClass(); $h11->name="OR"; $h11->nameOfProperty="OR2";
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
        $h17 = new stdClass(); $h17->name="CM"; $h17->nameOfProperty="CM";
        array_push($header, $h17);
        $h18 = new stdClass(); $h18->name="RV"; $h18->nameOfProperty="RV";
        array_push($header, $h18);
        break;
    case "Average Height":
    //case 2:
        $sql = "select distinct teamName, round(avg(p.height)) as height from PlayerStats ts join Team t on t.idTeam = ts.teamId join Player p on ts.playerId = p.idPlayer ";
        $sqlnew = " $filter group by ts.teamId";
        $sql = $sql.$sqlnew;
        $h1 = new stdClass(); $h1->name="Name"; $h1->nameOfProperty="teamName";
        array_push($header, $h1);
        $h2 = new stdClass(); $h2->name="Height (cm)"; $h2->nameOfProperty="height";
        array_push($header, $h2);
        break;
     case "Average Age":
    //case 3:
        $sql = "select distinct teamName, 2017 - round(avg(year(bornDate))) as age from PlayerStats ts join Team t on t.idTeam = ts.teamId join Player p on ts.playerId = p.idPlayer ";
        $sqlnew = " $filter group by ts.teamId";
        $sql = $sql.$sqlnew;
        $h1 = new stdClass(); $h1->name="Name"; $h1->nameOfProperty="teamName";
        array_push($header, $h1);
        $h2 = new stdClass(); $h2->name="Age"; $h2->nameOfProperty="age";
        array_push($header, $h2);
        break;


}


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
