<?php
/**
 * Created by PhpStorm.
 * User: pveb_student
 * Date: 04/01/17
 * Time: 15:17
 */

function obradiTimskuStatistiku($boxscore, $gameCode, $season, $idTeam, $i, $conn){
    $t = $boxscore->tts[$i]->totr;
    $p = $boxscore->tts[$i]->tmr;

    //var_dump($p);

    $query = "insert into TeamStats(gameCode,season, teamId, POS, PTS, 2FGM, 2FGA, 3FGM, 3FGA, FTM, FTA, OR2, DR, ASS, STL, TO2, BLK, BLKA, CM, RV)
values($gameCode, $season, '$idTeam', 0, $t->puntos, $t->fgm2, $t->fga2, $t->fgm3, $t->fga3, $t->ftm, $t->fta, $t->o + $p->o, $t->d + $p->d, $t->as2, $t->st, $t->to2 + $p->to2, $t->fv, $t->ag, $t->cm, $t->rv)";
    $res = $conn->query($query);

    //var_dump($res);
}

function obradiIgrace($boxscore, $gameCode, $season, $teamId, $i, $conn){
    foreach($boxscore->tts[$i]->trs as $v) {
        $ind = $conn->query("insert into Player(idPlayer, playerName) values('$v->jpjugd', '$v->acname')");

        $query = "insert into PlayerStats(playerId, gameCode, season, teamId, MIN2, PTS, 2FGM, 2FGA, 3FGM, 3FGA, FTM, FTA, OR2, DR, ASS, STL, TO2, BLK, BLGA, CM, RV)
values('$v->jpjugd', $gameCode, $season, '$teamId', '$v->min', $v->puntos, $v->fgm2, $v->fga2, $v->fgm3, $v->fga3, $v->ftm, $v->fta, $v->o, $v->d, $v->as2, $v->st, $v->to2, $v->fv, $v->ag, $v->cm, $v->rv)";
        $conn->query($query);
    }
}

function obradiUtakmicu($gameCode, $season, $conn){
    $json_string = file_get_contents("http://live.euroleague.net/api/header?gamecode=$gameCode&seasoncode=E$season");
    $header = json_decode($json_string);

    $r1 = proveriSudiju($conn, $header->re1);
    $r2 = proveriSudiju($conn, $header->re2);
    $r3 = proveriSudiju($conn, $header->re3);

    proveriTim($conn, $header->tA, $header->cA);
    proveriTim($conn, $header->tB, $header->cB);

    $datum = date_create_from_format("d/m/Y", $header->dat);
    $s = $datum->format('Y-m-d');
//    echo $datum;
    $query = "insert into Game(gameCode, season, round, teamH, teamA, dateOfGame, ref1Id, ref2Id, ref3Id, coachH, coachA, stadium, attendance)
 values($gameCode, $season, $header->rnd, '$header->cA', '$header->cB', '$s', $r1, $r2, $r3, '$header->coA', '$header->coB', '$header->sta', $header->cap)";
    $res = $conn->query($query);

    //   var_dump($res);
    $json_string2 = file_get_contents("http://live.euroleague.net/api/boxscore?gamecode=$gameCode&seasoncode=E$season");
    $boxscore = json_decode($json_string2);

    obradiTimskuStatistiku($boxscore, $gameCode, $season, $header->cA, 0, $conn);
    obradiTimskuStatistiku($boxscore, $gameCode, $season, $header->cB, 1, $conn);

    obradiIgrace($boxscore, $gameCode, $season, $header->cA, 0, $conn);
    obradiIgrace($boxscore, $gameCode, $season, $header->cB, 1, $conn);
}

function proveriSudiju($conn, $sudija){
    $nacija = substr($sudija, -4, 3);
    $sudija = substr($sudija, 0, -5);

    $refQ ="select * from Referee where refereeName='$sudija'";
    $ind = $conn->query($refQ);
    if($ind->num_rows == 0){
        $tmp2 = $conn->query('select max(idReferee) as br from Referee');
        $tmp = $tmp2->fetch_object();
        //var_dump($tmp);
        if($tmp->br == NULL){
            $novi_id = 0;
            echo "dfkjah\n";
        }
        else{
            echo "ludilo\n";
            $novi_id = $tmp->br + 1;
        }
        $upitTekst = "INSERT INTO Referee(idReferee, refereeName, nationality) VALUES ($novi_id, '$sudija','$nacija')";
        if($conn->query($upitTekst) === TRUE)
            echo "uspeh\n";
        else
            echo $conn->error;
        //echo $upit->num_rows;
    }

    $res = $conn->query("select idReferee from Referee where refereeName = '$sudija'");
    $re2 = $res->fetch_object();
    return $re2->idReferee;
};

function proveriTim($conn, $tim, $id_tima){

    $refQ ="select * from Team where teamName = '$tim'";
    $ind = $conn->query($refQ);
    if($ind->num_rows == 0){
        $upitTekst = "INSERT INTO Team(idTeam, teamName) VALUES ('$id_tima', '$tim')";
        if($conn->query($upitTekst) === TRUE)
            echo "uspeh\n";
        else
            echo $conn->error;
        //echo $upit->num_rows;
    }
};


$baza = new stdClass();
$baza->host = "localhost";
$baza->user = "root";
$baza->pass = "root";
$baza->name = "mydb";

$conn = new mysqli($baza->host, $baza->user, $baza->pass, $baza->name);

if($conn->connect_errno)
    DIE("PROBLEM POVEZIVANJE");
else
    echo "osuri pavijana\n";

$i = 0;
for($i = 1; $i < 17; $i++) {
    obradiUtakmicu($i, 2016, $conn);
}