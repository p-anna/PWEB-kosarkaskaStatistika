<?php
include("simple_html_dom.php");

//$html = file_get_html("http://www.euroleague.net/main/results/showgame?gamecode=139&seasoncode=E2016");

function visinaIDanRodjenja($idIgraca, $sezona)
{
    $id = trim(substr($idIgraca, 1));
    $html = file_get_html("http://www.euroleague.net/competition/players/showplayer?pcode=$id&seasoncode=E$sezona");
    $rez = $html->find('div.summary-second');
    foreach ($rez as $v){
        $rez2 = $v->find('span');
        //12 July, 1988
        //4 July, 1444
        $arg2 = substr($rez2[1]->innertext, 6);
        $datum = date_create_from_format("j F, Y", $arg2);
        $s = $datum->format('Y-m-d');
        return array(substr($rez2[0]->innertext, 8), $s, substr($rez2[2]->innertext, 13));
    }
}

//$rez = $html->find('span.local');
//foreach ($rez as $v){
//    //var_dump($v->innertext);
//}


