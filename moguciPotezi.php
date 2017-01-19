<?php
/**
 * Created by PhpStorm.
 * User: tosa
 * Date: 19.1.17.
 * Time: 17.45
 */


function moguciPoteziSvi($json2, $cetvrtina){
    global $poteza;
    if($cetvrtina == 1){
        $niz = $json2->FirstQuarter;
        $m  = count($niz);
        for($i=0; $i<$m ; $i++){
            $poteza[$niz[$i]->NTTIPO] = $niz[$i]->CSDESCWEB;
        }
    }
    if($cetvrtina == 2){
        $niz = $json2->SecondQuarter;
        $m  = count($niz);
        for($i=0; $i<$m ; $i++){
            $poteza[$niz[$i]->NTTIPO] = $niz[$i]->CSDESCWEB;
        }
    }
    if($cetvrtina == 3){
        $niz = $json2->ThirdQuarter;
        $m  = count($niz);
        for($i=0; $i<$m ; $i++){
            $poteza[$niz[$i]->NTTIPO] = $niz[$i]->CSDESCWEB;
        }
    }
    if($cetvrtina == 4){
        $niz= $json2->ForthQuarter;
        $m  = count($niz);
        for($i=0; $i<$m ; $i++){
            $poteza[$niz[$i]->NTTIPO] = $niz[$i]->CSDESCWEB;
        }
    }
    if($cetvrtina == 5){
        $niz = $json2->ExtraTime;
        $m  = count($niz);
        if($m==0)
            echo "Nema produzetaka\n";
        else{
            echo "Obradjeni i produzeci \n";
            for($i=0; $i<$m ; $i++){
                $poteza[$niz[$i]->NTTIPO] = $niz[$i]->CSDESCWEB;
            }
        }

    }
}


function obradiSvePozive($gameCode, $season)
{
    $json_string2 = file_get_contents("http://live.euroleague.net/api/playbyplay?gamecode=$gameCode&seasoncode=E$season");
    $json2 = json_decode($json_string2);

    moguciPoteziSvi($json2, 1);
    moguciPoteziSvi($json2, 2);
    moguciPoteziSvi($json2, 3);
    moguciPoteziSvi($json2, 4);
    moguciPoteziSvi($json2, 5);
}

$poteza = array();

for($i = 1; $i < 25; $i++) {
    obradiSvePozive($i, 2016);
}

print_r($poteza);

