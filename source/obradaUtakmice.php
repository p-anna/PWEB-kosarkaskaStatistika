<?php
/**
 * Created by PhpStorm.
 * User: pveb_student
 * Date: 04/01/17
 * Time: 15:17
 */

include("c:/xampp/htdocs/BasketStatistic/PWEB-kosarkaskaStatistika/domProba.php");

function obradiTimskuStatistiku($boxscore, $gameCode, $season, $idTeam, $i, $conn){
    $t = $boxscore->tts[$i]->totr;
    $p = $boxscore->tts[$i]->tmr;

    //var_dump($p);

    $query = "replace into TeamStats(gameCode, season, teamId, POS, PTS, 2FGM, 2FGA, 3FGM, 3FGA, FTM, FTA, OR2, DR, ASS, STL, TO2, BLK, BLKA, CM, RV)
values($gameCode, $season, '$idTeam', 0, $t->puntos, $t->fgm2, $t->fga2, $t->fgm3, $t->fga3, $t->ftm, $t->fta, $t->o + $p->o, $t->d + $p->d, $t->as2, $t->st, $t->to2 + $p->to2, $t->fv, $t->ag, $t->cm, $t->rv)";
    $conn->query($query);
}

function obradiIgrace($boxscore, $gameCode, $season, $teamId, $i, $conn, $posImg){

    echo "teamID = $teamId";
    foreach($boxscore->tts[$i]->trs as $v) {
        $ind = $conn->query("select idPlayer from Player where idPlayer='$v->jpjugd'");
        if($ind->num_rows == 0) {
            $plyr = findPlayerInArrayByID($posImg, $v->jpjugd);
            $a = visinaIDanRodjenja($v->jpjugd, $season);
            $conn->query(
                "insert into Player(idPlayer, playerName, playerPos, photoURL, height, bornDate, nationality) 
                            values('$v->jpjugd', '$v->acname', '$plyr->p', '$plyr->im', $a[0]*100, '$a[1]', '$a[2]')"
            );
        }


        $query = "insert into PlayerStats(playerId, gameCode, season, teamId, MIN2, PTS, 2FGM, 2FGA, 3FGM, 3FGA, FTM, FTA, OR2, DR, ASS, STL, TO2, BLK, BLGA, CM, RV)
values('$v->jpjugd', $gameCode, $season, '$teamId', '$v->min', $v->puntos, $v->fgm2, $v->fga2, $v->fgm3, $v->fga3, $v->ftm, $v->fta, $v->o, $v->d, $v->as2, $v->st, $v->to2, $v->fv, $v->ag, $v->cm, $v->rv)";
        $conn->query($query);
    }


}

function findPlayerInArrayByID($posImg, $id)
{
    $ret = null;
    foreach ($posImg as $plyr)
        if (substr($id, 0, strlen($plyr->ac)) === $plyr->ac) {
            $plyr->ac = $id;
            return $plyr;
        }
}
function obradiUtakmicu($gameCode, $season, $conn){
    $json_string = file_get_contents("http://live.euroleague.net/api/header?gamecode=$gameCode&seasoncode=E$season");
    $header = json_decode($json_string);
    if($header == null) {
        echo "Utakmica se jos nije igrala\n";
        return;
    }
    //var_dump($header->live=="false");
    if($header->live == false)
        $conn->query("delete from PreostaleUtakmice where gameCode=$gameCode");
    else
        return;

    $r1 = proveriSudiju($conn, $header->re1);
    $r2 = proveriSudiju($conn, $header->re2);
    $r3 = proveriSudiju($conn, $header->re3);

    proveriTim($conn, $header->tA, $header->cA);
    proveriTim($conn, $header->tB, $header->cB);

    $datum = date_create_from_format("d/m/Y", $header->dat);
    $s = $datum->format('Y-m-d');
//    echo $datum;
    $query = "replace into Game(gameCode, season, round, teamH, teamA, dateOfGame, ref1Id, ref2Id, ref3Id, coachH, coachA, stadium, attendance)
 values($gameCode, $season, $header->rnd, '$header->cA', '$header->cB', '$s', $r1, $r2, $r3, '$header->coA', '$header->coB', '$header->sta', $header->cap)";
    $conn->query($query);

    $json_string2 = file_get_contents("http://live.euroleague.net/api/boxscore?gamecode=$gameCode&seasoncode=E$season");
    $boxscore = json_decode($json_string2);

    obradiTimskuStatistiku($boxscore, $gameCode, $season, $header->cA, 0, $conn);
    obradiTimskuStatistiku($boxscore, $gameCode, $season, $header->cB, 1, $conn);

    $team1 = strtolower($header->cA);
    $team2 = strtolower($header->cB);
    $posImg1 = json_decode(file_get_contents(
        "http://live.euroleague.net/api/Players?gamecode=$gameCode&seasoncode=E$season&disp=&equipo=$team1&temp=E$season"
    //"http://live.euroleague.net/api/Players?gamecode=1&seasoncode=E2016&disp=&equipo=&temp=E2016"
    ));
    $posImg2 = json_decode(file_get_contents(
        "http://live.euroleague.net/api/Players?gamecode=$gameCode&seasoncode=E$season&disp=&equipo=$team2&temp=E$season"
    ));

    obradiIgrace($boxscore, $gameCode, $season, $header->cA, 0, $conn, $posImg1);
    obradiIgrace($boxscore, $gameCode, $season, $header->cB, 1, $conn, $posImg2);

    $opasanObjekat = dajNaprednu($conn, $header->cA, $boxscore, $season, $gameCode, 0);
    napuniNaprednu($conn, $gameCode, $season, $opasanObjekat);

    $opasanObjekat = dajNaprednu($conn, $header->cB, $boxscore, $season, $gameCode, 1);
    napuniNaprednu($conn, $gameCode, $season, $opasanObjekat);

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

$asdf = $conn->query("select min(gameCode) from PreostaleUtakmice");
$najmanjaTekma = $asdf->fetch_row()[0];
for($i = 1; $i < 25 + 1; $i++) {
    $rez = $conn->query("select * from PreostaleUtakmice where gameCode=$i");
    if ($rez->num_rows == 1) {
        obradiUtakmicu($i, 2016, $conn);
    }
}

function izracunajPoCetvrtini($cetvrtina, $teamA, $conn, $season, $first51, $dosadniObjekat)
{
    $idAs                    = $dosadniObjekat->idAs;
    $idFTM                 = $dosadniObjekat->idFTM;
    $id2FGM1                 = $dosadniObjekat->id2FGM1;
    $id2FGA1                 = $dosadniObjekat->id2FGA1;
    $id3FGM1                 = $dosadniObjekat->id3FGM1;
    $id3FGA1                 = $dosadniObjekat->id3FGA1;
    $idBlock                 = $dosadniObjekat->idBlock;
    $idShotRejected          = $dosadniObjekat->idShotRejected;
    $idFoul                  = $dosadniObjekat->idFoul;
    $idFoulDrawn             = $dosadniObjekat->idFoulDrawn;
    $OffRebound              = $dosadniObjekat->OffRebound;
    $idSteal                 = $dosadniObjekat->idSteal;
    $idDefRebound            = $dosadniObjekat->idDefRebound;

    $idPoeniProtivnik        = $dosadniObjekat->idPoeniProtivnik        ;
    $idBlockProtivnik        = $dosadniObjekat->idBlockProtivnik        ;
    $idShotRejectedProtivnik = $dosadniObjekat->idShotRejectedProtivnik ;
    $idFoulProtivnik         = $dosadniObjekat->idFoulProtivnik         ;
    $idFoulDrawnProtivnik    = $dosadniObjekat->idFoulDrawnProtivnik    ;
    $idOffReboundProtivnik   = $dosadniObjekat->idOffReboundProtivnik   ;
    $idStealProtivnik        = $dosadniObjekat->idStealProtivnik        ;
    $idDefReboundProtivnik   = $dosadniObjekat->idDefReboundProtivnik   ;

//prosirujemo prvih pet na aktuelnih 5

    $n = count($cetvrtina);

    $poslednjiStrelac = null;

    for ($i = 0; $i < $n; $i++) {
        $potez = $cetvrtina[$i]->NTTIPO;

        if (trim($teamA) == trim($cetvrtina[$i]->NTEQUIPO)) {
            switch ($potez) {
                case "AS":
                    foreach ($first51 as $playerInFirst5) {
                        $idAs[$playerInFirst5]++;
                    }
                    echo "ODJE SAM, TREBA UPIT ODRADIT\n";
                    $lfsdjl = $cetvrtina[$i]->NTJUGD;

                    $indijanac = 1;
                    $sql = "insert into assists (player1Id, player2Id, counter, sezona) values ('$lfsdjl', '$poslednjiStrelac', $indijanac, $season) on duplicate key update counter = counter+1";
                    //var_dump($sql);
                    $rz = $conn->query($sql);
                    //var_dump(mysqli_error($conn));


                    break;
                case "FTM":
                    foreach ($first51 as $playerInFirst5) {
                        $idFTM[$playerInFirst5]++;
                        //$idPoeniProtivnik[$playerInFirst5]++;
                        $poslednjiStrelac = $cetvrtina[$i]->NTJUGD;
                    }
                    break;
                case "2FGM":
                    foreach ($first51 as $playerInFirst5) {
                        $id2FGM1[$playerInFirst5]++;
                        //$idPoeniProtivnik[$playerInFirst5]+=2;

                        $poslednjiStrelac = $cetvrtina[$i]->NTJUGD;
                    }
                    break;
                case "2FGA":
                    foreach ($first51 as $playerInFirst5) {
                        $id2FGA1[$playerInFirst5]++;
                    }
                    break;
                case "3FGM":
                    foreach ($first51 as $playerInFirst5) {
                        $id3FGM1[$playerInFirst5]++;
                        //$idPoeniProtivnik[$playerInFirst5]+=3;
                        $poslednjiStrelac = $cetvrtina[$i]->NTJUGD;
                    }
                    break;
                case "3FGA":
                    foreach ($first51 as $playerInFirst5) {
                        $id3FGA1[$playerInFirst5]++;
                    }
                    break;
                case "D":
                    foreach ($first51 as $playerInFirst5) {
                        $idDefRebound[$playerInFirst5]++;
                    }
                    break;
                case "ST":
                    foreach ($first51 as $playerInFirst5) {
                        $idSteal[$playerInFirst5]++;
                    }
                    break;
                case "O":
                    foreach ($first51 as $playerInFirst5) {
                        $OffRebound[$playerInFirst5]++;
                    }
                    break;
                case "FV":
                    foreach ($first51 as $playerInFirst5) {
                        $idBlock[$playerInFirst5]++;
                    }
                    break;
                case "AG":
                    foreach ($first51 as $playerInFirst5) {
                        $idShotRejected[$playerInFirst5]++;
                    }
                    break;
                case "CM":
                    foreach ($first51 as $playerInFirst5) {
                        $idFoul[$playerInFirst5]++;
                    }
                    break;
                case "RV":
                    foreach ($first51 as $playerInFirst5) {
                        $idFoulDrawn[$playerInFirst5]++;
                    }
                    break;
                case "IN":
                    //echo $json2->FirstQuarter[$i]->NTJUGD;
                    array_push($first51, $cetvrtina[$i]->NTJUGD);
                    break;
                case "OUT":
                    $key = array_search($cetvrtina[$i]->NTJUGD, $first51);
                    array_splice($first51, $key, 1);
                    break;
                default:
                    echo "nema akcije";
            }
        } else {
            foreach ($first51 as $playerInFirst5) {
                switch ($potez) {
                    case "FTM":
                        $idPoeniProtivnik[$playerInFirst5] += 1;
                        break;
                    case "2FGM":
                        $idPoeniProtivnik[$playerInFirst5] += 2;
                        break;
                    case "3FGM":
                        $idPoeniProtivnik[$playerInFirst5] += 3;
                        break;
                    case "D":
                        $idDefReboundProtivnik[$playerInFirst5]++;
                        break;
                    case "ST":
                        $idStealProtivnik[$playerInFirst5]++;
                        break;
                    case "O":
                        $idOffReboundProtivnik[$playerInFirst5]++;
                        break;
                    case "FV":
                        $idBlockProtivnik[$playerInFirst5];
                        break;
                    case "AG":
                        $idShotRejectedProtivnik[$playerInFirst5]++;
                        break;
                    case "CM":
                        $idFoulProtivnik[$playerInFirst5]++;
                        break;
                    case "RV":
                        $idFoulDrawnProtivnik[$playerInFirst5]++;
                        break;
                }
            }
        }
    }
    $dosadniObjekat->idAs                    = $idAs                   ;

    $dosadniObjekat->idFTM                 = $idFTM                ;
    $dosadniObjekat->id2FGM1                 = $id2FGM1                ;
    $dosadniObjekat->id2FGA1                 = $id2FGA1                ;
    $dosadniObjekat->id3FGM1                 = $id3FGM1                ;
    $dosadniObjekat->id3FGA1                 = $id3FGA1                ;
    $dosadniObjekat->idBlock                 = $idBlock                ;
    $dosadniObjekat->idShotRejected          = $idShotRejected         ;
    $dosadniObjekat->idFoul                  = $idFoul                 ;
    $dosadniObjekat->idFoulDrawn             = $idFoulDrawn            ;
    $dosadniObjekat->OffRebound              = $OffRebound             ;
    $dosadniObjekat->idSteal                 = $idSteal                ;
    $dosadniObjekat->idDefRebound            = $idDefRebound           ;
    $dosadniObjekat->idPoeniProtivnik        = $idPoeniProtivnik       ;
    $dosadniObjekat->idBlockProtivnik        = $idBlockProtivnik       ;
    $dosadniObjekat->idShotRejectedProtivnik = $idShotRejectedProtivnik;
    $dosadniObjekat->idFoulProtivnik         = $idFoulProtivnik        ;
    $dosadniObjekat->idFoulDrawnProtivnik    = $idFoulDrawnProtivnik   ;
    $dosadniObjekat->idOffReboundProtivnik   = $idOffReboundProtivnik  ;
    $dosadniObjekat->idStealProtivnik        = $idStealProtivnik       ;
    $dosadniObjekat->idDefReboundProtivnik   = $idDefReboundProtivnik  ;

}


function dajNaprednu($conn, $teamA, $json, $season, $gameCode, $i)
{
    $idAs = array();
    $idFTM = array();
    $id2FGM1 = array();
    $id2FGA1 = array();
    $id3FGM1 = array();
    $id3FGA1 = array();
    $idBlock = array();
    $idShotRejected = array();
    $idFoul = array();
    $idFoulDrawn = array();
    $OffRebound = array();
    $idSteal = array();
    $idDefRebound = array();

    $idPoeniProtivnik = array();
    $idBlockProtivnik = array();
    $idShotRejectedProtivnik = array();
    $idFoulProtivnik = array();
    $idFoulDrawnProtivnik = array();
    $idOffReboundProtivnik = array();
    $idStealProtivnik = array();
    $idDefReboundProtivnik = array();


//prosirujemo prvih pet na aktuelnih 5
    $first51 = array();

    $teamTotalStats = $json->tts[$i];
    $igraciSvi = $teamTotalStats->trs;
    $n0 = count($igraciSvi);

    for ($i = 0; $i < $n0; $i++) {
        $jedanIgrac = $igraciSvi[$i];
        $idPlayer = $jedanIgrac->jpjugd;

        //dodajem u mapu
        $idAs[$idPlayer] = 0;
        $idFTM[$idPlayer] = 0;
        $id2FGM1[$idPlayer] = 0;
        $id2FGA1[$idPlayer] = 0;
        $id3FGA1[$idPlayer] = 0;
        $id3FGM1[$idPlayer] = 0;
        $idBlock[$idPlayer] = 0;
        $idShotRejected[$idPlayer] = 0;
        $idFoul[$idPlayer] = 0;
        $idFoulDrawn[$idPlayer] = 0;
        $OffRebound[$idPlayer] = 0;
        $idSteal[$idPlayer] = 0;
        $idDefRebound[$idPlayer] = 0;

        $idPoeniProtivnik[$idPlayer] = 0;
        $idBlockProtivnik[$idPlayer] = 0;
        $idShotRejectedProtivnik[$idPlayer] = 0;
        $idFoulProtivnik[$idPlayer] = 0;
        $idFoulDrawnProtivnik[$idPlayer] = 0;
        $idOffReboundProtivnik[$idPlayer] = 0;
        $idStealProtivnik[$idPlayer] = 0;
        $idDefReboundProtivnik[$idPlayer] = 0;

        if (($jedanIgrac->jpstarter) == 1) {
            array_push($first51, $idPlayer);
        }

    }

    $json_string2 = file_get_contents("http://live.euroleague.net/api/playbyplay?gamecode=$gameCode&seasoncode=E$season");
    $json2 = json_decode($json_string2);

    $dosadniObjekat = new stdClass();
    $dosadniObjekat->idAs                    = $idAs                   ;;;
    $dosadniObjekat->idFTM                   = $idFTM                  ;;
    $dosadniObjekat->id2FGM1                 = $id2FGM1                ;;
    $dosadniObjekat->id2FGA1                 = $id2FGA1                ;;;
    $dosadniObjekat->id3FGM1                 = $id3FGM1                ;;
    $dosadniObjekat->id3FGA1                 = $id3FGA1                ;;;
    $dosadniObjekat->idBlock                 = $idBlock                ;;
    $dosadniObjekat->idShotRejected          = $idShotRejected         ;;
    $dosadniObjekat->idFoul                  = $idFoul                 ;
    $dosadniObjekat->idFoulDrawn             = $idFoulDrawn            ;
    $dosadniObjekat->OffRebound              = $OffRebound             ;;
    $dosadniObjekat->idSteal                 = $idSteal                ;;
    $dosadniObjekat->idDefRebound            = $idDefRebound           ;;
    $dosadniObjekat->idPoeniProtivnik        = $idPoeniProtivnik       ;;
    $dosadniObjekat->idBlockProtivnik        = $idBlockProtivnik       ;
    $dosadniObjekat->idShotRejectedProtivnik = $idShotRejectedProtivnik;
    $dosadniObjekat->idFoulProtivnik         = $idFoulProtivnik        ;
    $dosadniObjekat->idFoulDrawnProtivnik    = $idFoulDrawnProtivnik   ;
    $dosadniObjekat->idOffReboundProtivnik   = $idOffReboundProtivnik  ;;
    $dosadniObjekat->idStealProtivnik        = $idStealProtivnik       ;;
    $dosadniObjekat->idDefReboundProtivnik   = $idDefReboundProtivnik  ;;


    izracunajPoCetvrtini($json2->FirstQuarter, $teamA, $conn, $season, $first51, $dosadniObjekat);
    izracunajPoCetvrtini($json2->SecondQuarter, $teamA, $conn, $season, $first51, $dosadniObjekat);
    izracunajPoCetvrtini($json2->ThirdQuarter, $teamA, $conn, $season, $first51, $dosadniObjekat);
    izracunajPoCetvrtini($json2->ForthQuarter, $teamA, $conn, $season, $first51, $dosadniObjekat);

    if (count($json2->ExtraTime))
        izracunajPoCetvrtini($json2->ExtraTime, $teamA, $conn, $season, $first51, $dosadniObjekat);

    $sum = 0;
    foreach ($dosadniObjekat->idPoeniProtivnik as $abc)
        $sum+=$abc;
    var_dump($sum);

    return $dosadniObjekat;
}

function napuniNaprednu($conn, $gameCode, $season, $dosadniObjekat){

    foreach ($dosadniObjekat->idPoeniProtivnik as $playerid => $value)
        $conn->query("update playerStats set teamOppPoints = $value where playerId = '$playerid' and gameCode=$gameCode and season=$season");

    foreach ($dosadniObjekat->idFTM as $playerid => $value)
        $conn->query("update playerStats set teamPoints = teamPoints + $value where playerId = '$playerid' and gameCode=$gameCode and season=$season");
    foreach ($dosadniObjekat->id2FGM1 as $playerid => $value)
        $conn->query("update playerStats set teamPoints = teamPoints + 2*$value where playerId = '$playerid' and gameCode=$gameCode and season=$season");
    foreach ($dosadniObjekat->id3FGM1 as $playerid => $value)
        $conn->query("update playerStats set teamPoints = teamPoints + 3*$value where playerId = '$playerid' and gameCode=$gameCode and season=$season");
    foreach ($dosadniObjekat->OffRebound as $playerid => $value)
        $conn->query("update playerStats set teamOffReb = $value where playerId = '$playerid' and gameCode=$gameCode and season=$season");
    foreach ($dosadniObjekat->idOffReboundProtivnik as $playerid => $value)
        $conn->query("update playerStats set teamOppOffReb = $value where playerId = '$playerid' and gameCode=$gameCode and season=$season");
    foreach ($dosadniObjekat->idDefRebound as $playerid => $value)
        $conn->query("update playerStats set teamDefReb = $value where playerId = '$playerid' and gameCode=$gameCode and season=$season");
    foreach ($dosadniObjekat->idDefReboundProtivnik as $playerid => $value)
        $conn->query("update playerStats set teamOppDefReb = $value where playerId = '$playerid' and gameCode=$gameCode and season=$season");
    foreach ($dosadniObjekat->idSteal as $playerid => $value)
        $conn->query("update playerStats set teamSteal = $value where playerId = '$playerid' and gameCode=$gameCode and season=$season");
    foreach ($dosadniObjekat->idStealProtivnik as $playerid => $value)
        $conn->query("update playerStats set teamOppSteal = $value where playerId = '$playerid' and gameCode=$gameCode and season=$season");
    foreach ($dosadniObjekat->idBlock as $playerid => $value)
        $conn->query("update playerStats set teamBlock= $value where playerId = '$playerid' and gameCode=$gameCode and season=$season");
    foreach ($dosadniObjekat->idShotRejected as $playerid => $value)
        $conn->query("update playerStats set teamOppBlock = $value where playerId = '$playerid' and gameCode=$gameCode and season=$season");
    foreach ($dosadniObjekat->idFoul as $playerid => $value)
        $conn->query("update playerStats set teamFoul = $value where playerId = '$playerid' and gameCode=$gameCode and season=$season");
    foreach ($dosadniObjekat->idFoulDrawn as $playerid => $value)
        $conn->query("update playerStats set teamDrawnFoul = $value where playerId = '$playerid' and gameCode=$gameCode and season=$season");
    foreach ($dosadniObjekat->idFoulProtivnik as $playerid => $value)
        $conn->query("update playerStats set teamOppFoul = $value where playerId = '$playerid' and gameCode=$gameCode and season=$season");
    foreach ($dosadniObjekat->idFoulDrawnProtivnik as $playerid => $value)
        $conn->query("update playerStats set teamOppFaulDrawn = $value where playerId = '$playerid' and gameCode=$gameCode and season=$season");
//    foreach ($opasanObjekat[idPoeniProtivnik] as $playerid => $value)
//        $conn->query("update playerStats set teamOppPoints = $value where playerId = '$playerid' and gameCode=$gameCode and season=$season");
//    foreach ($opasanObjekat[idPoeniProtivnik] as $playerid => $value)
//        $conn->query("update playerStats set teamOppPoints = $value where playerId = '$playerid' and gameCode=$gameCode and season=$season");
//    foreach ($opasanObjekat[idPoeniProtivnik] as $playerid => $value)
//        $conn->query("update playerStats set teamOppPoints = $value where playerId = '$playerid' and gameCode=$gameCode and season=$season");

}