<?php
function izracunajPoCetvrtini($cetvrtina, $teamA){

    global $IdNameA;
    global $idAs;
    global $id2FGM1;
    global $id2FGA1;
    global $id3FGM1;
    global $id3FGA1;
    global $idBlock;
    global $idShotRejected;
    global $idFoul;
    global $idFoulDrawn;
    global $OffRebound;
    global $idSteal;
    global $idDefRebound;

//prosirujemo prvih pet na aktuelnih 5
    global $first51;

    $n = count($cetvrtina);

    for ($i = 0; $i < $n; $i++) {
        $potez = $cetvrtina[$i]->NTTIPO;
        if ($teamA == ($cetvrtina[$i]->NTEQUIPO)) {
            switch ($potez) {
                case "AS":
                    foreach ($first51 as $playerInFirst5) {
                        $idAs[$playerInFirst5]++;
                    }
                    break;
                case "2FGM":
                    foreach ($first51 as $playerInFirst5) {
                        $id2FGM1[$playerInFirst5]++;
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
        }
    }
}

//obradiNaprednuStatistiku($boxscore, $gameCode, $season, $header->cA, 0, $conn);
//obradiNaprednuStatistiku($boxscore, $gameCode, $season, $header->cB, 1, $conn);
// mora da se zameni

$gameCode = 1;
$season = 2016;


$json_string2 = file_get_contents("http://live.euroleague.net/api/boxscore?gamecode=$gameCode&seasoncode=E$season");
$json = json_decode($json_string2);


$IdNameA = array();
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

//prosirujemo prvih pet na aktuelnih 5
$first51 = array();

$i = 0;
// za sada to je 0 ili 1 po argumentu

$teamTotalStats = $json->tts[$i];
$igraciSvi = $teamTotalStats->trs;
$n0  = count($igraciSvi);

for($i=0; $i<$n0 ; $i++){
    $jedanIgrac = $igraciSvi[$i];
    $idPlayer = $jedanIgrac->jpjugd;
    $namePlayer = $jedanIgrac->acname;

    //dodajem u mapu
    $IdNameA[$idPlayer] = $namePlayer;
    $idAs[$idPlayer] = 0;
    $id2FGM1[$idPlayer] = 0;
    $id2FGA1[$idPlayer] = 0;
    $id3FGA1[$idPlayer] = 0;
    $id3FGM1[$idPlayer] = 0;
    $idBlock[$idPlayer] = 0;
    $idShotRejected[$idPlayer] = 0;
    $idFoul[$idPlayer] = 0;
    $idFoulDrawn[$idPlayer] = 0;
    $OffRebound[$idPlayer]=0;
    $idSteal[$idPlayer]=0;
    $idDefRebound[$idPlayer]=0;

    if(($jedanIgrac->jpstarter)==1){
        array_push($first51, $idPlayer);
    }

}
print_r($IdNameA);


$json_string2 = file_get_contents("http://live.euroleague.net/api/playbyplay?gamecode=$gameCode&seasoncode=E$season");
$json2 = json_decode($json_string2);

$teamA = $json2->ca;

izracunajPoCetvrtini($json2->FirstQuarter, $teamA);
izracunajPoCetvrtini($json2->SecondQuarter , $teamA);
izracunajPoCetvrtini($json2->ThirdQuarter , $teamA);
izracunajPoCetvrtini($json2->ForthQuarter, $teamA);

if(count($json2->ExtraTime))
    izracunajPoCetvrtini($json2->ExtraTime, $teamA);

print_r($idFoulDrawn);
print_r($idFoul);