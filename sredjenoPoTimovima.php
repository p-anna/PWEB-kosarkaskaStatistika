<?php
function izracunajPoCetvrtini($cetvrtina, $teamA, $conn, $season, $first51, $dosadniObjekat)
{
    $idAs                    = $dosadniObjekat->idAs;
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

        if ($teamA == trim($cetvrtina[$i]->NTEQUIPO)) {
            switch ($potez) {
                case "AS":
                    foreach ($first51 as $playerInFirst5) {
                        $idAs[$playerInFirst5]++;
                    }
                    echo "ODJE SAM, TREBA UPIT ODRADIT\n";
                    $lfsdjl = $cetvrtina[$i]->NTJUGD;
                    var_dump($lfsdjl);
                    var_dump($poslednjiStrelac);
                    var_dump($season);
                    $sql = "insert into assists (player1Id, player2Id, counter, sezona) values ('$lfsdjl', '$poslednjiStrelac', 1, $season) on duplicate key update counter = counter+1";
                    $rz = $conn->query($sql);
                    var_dump($sql);
                    break;
                case "FTM":
                    foreach ($first51 as $playerInFirst5) {
                        $id2FGM1[$playerInFirst5]++;
                        $poslednjiStrelac = $cetvrtina[$i]->NTJUGD;
                    }
                    break;
                case "2FGM":
                    foreach ($first51 as $playerInFirst5) {
                        $id2FGM1[$playerInFirst5]++;
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
                        $idBlock[$playerInFirst5];
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


function dajNaprednu($conn, $teamA, $json, $season, $gameCode)
{
    $idAs = array();
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

    $i = 0;
// za sada to je 0 ili 1 po argumentu

    $teamTotalStats = $json->tts[$i];
    $igraciSvi = $teamTotalStats->trs;
    $n0 = count($igraciSvi);

    for ($i = 0; $i < $n0; $i++) {
        $jedanIgrac = $igraciSvi[$i];
        $idPlayer = $jedanIgrac->jpjugd;

        //dodajem u mapu
        $idAs[$idPlayer] = 0;
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
    $dosadniObjekat->idAs                    = $idAs                   ;
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


    izracunajPoCetvrtini($json2->FirstQuarter, $teamA, $conn, $season, $first51, $dosadniObjekat);
    izracunajPoCetvrtini($json2->SecondQuarter, $teamA, $conn, $season, $first51, $dosadniObjekat);
    izracunajPoCetvrtini($json2->ThirdQuarter, $teamA, $conn, $season, $first51, $dosadniObjekat);
    izracunajPoCetvrtini($json2->ForthQuarter, $teamA, $conn, $season, $first51, $dosadniObjekat);

    if (count($json2->ExtraTime))
        izracunajPoCetvrtini($json2->ExtraTime, $teamA, $conn, $season, $first51, $dosadniObjekat);

    var_dump($dosadniObjekat->idDefRebound);
}

