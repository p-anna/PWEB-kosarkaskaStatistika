<?php
error_reporting(0);
$method = $_SERVER['REQUEST_METHOD'];
$url = $_SERVER['REQUEST_URI'];
$query_str = parse_url($url, PHP_URL_QUERY);
parse_str($query_str, $url_params);
file_put_contents("log.txt", $url_params);

$link = mysqli_connect('localhost','root','root','mydb');
mysqli_set_charset($link,'utf8');

if(mysqli_connect_errno())
{
    printf("SQL CONNECT ERROR: %s\n", mysqli_connect_error());
}

$table = $url_params[1];

//Implementirati metodu koja dohvata sve reci iz leksikona
if($method == 'GET' && count($url_params) == 5)
{
    $sql = "SELECT * FROM Player";
    $result = mysqli_query($link,$sql);
    $json = array();
    for($i=0; $i<mysqli_num_rows($result);$i++)
        array_push($json, mysqli_fetch_object($result));
    echo json_encode($json);
}

$table = $url_params["screen"];
if($method == 'GET' && $table == "players" && count($url_params) == 2)
{
    $id = $url_params["id"];
    file_put_contents("log.txt", $id);
    $sql = "SELECT * FROM player WHERE idPlayer = '$id'";
    $result = mysqli_query($link,$sql);

    $json = array();
    for($i=0; $i<mysqli_num_rows($result);$i++)
        array_push($json, mysqli_fetch_object($result));
    echo json_encode($json);
}

//Metoda koja dohvata samo jednu rec iz leksikona vraca objekat koji sadrzi rec i njen skor ili false ako se trazena rec ne nalazi u leksikonu
if($method == 'GET' && count($url_params) == 3)
{
    file_put_contents("log.txt", "3 parametra");
    $word = $url_params[2];
    $sql = "SELECT * FROM Player";
}
//Implementirati metodu koja upisuje zadatu rec i njenu sentiment ocenu u leksikon
elseif($method == 'POST' && $table == 'words')
{
    $word = $url_params[2];
    $score = $url_params[3];
    //$sql = "INSERT INTO  VALUES('$word','$score')";
}
//Implementirati metodu koja upisuje dokument opisan naslovom, sadrzajem i sentiment ocenom u bazu
elseif($method == 'POST' && $table == 'documents')
{
    $title = $url_params[2];
    $content = $url_params[3];
    $score = $url_params[4];
    //$sql = "INSERT INTO documents(id,title,content,sentment) VALUES(null,'$title','$content','$score')";
}

$sql = "select idPlayer, playerName, height, nationality from Player";
$result = mysqli_query($link,$sql);

if(!$result)
{
    printf("SQL ERROR: %s\n", mysqli_error($link));
}

if($method == 'GET')
{
//    echo '[';
//    for($i=0; $i<mysqli_num_rows($result);$i++)
//        echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
//    echo ']';

    echo "[]";
    exit(0);
      $poruka = new stdClass();
      $json = array();
      for($i=0; $i<mysqli_num_rows($result);$i++)
          array_push($json, mysqli_fetch_object($result));
      $poruka->players = $json;
    $textHedera= '[
                        {"name" : "NAME", "propertyName" : "playerName"},
                        {"name" : "Height", "propertyName" : "height"}
                   ]';
    //$poruka->players[0]->playerName = '<pre><a href="#/player/{{player.idPlayer}}"></a>'. $poruka->players[0]->playerName . '</a></pre>';
    $poruka->headers = json_decode($textHedera);
    echo json_encode($poruka);
}
elseif($method == 'POST')
{
    echo "Data successfully inserted!";
}


//if($method == 'GET')
//{
////    echo '[';
////    for($i=0; $i<mysqli_num_rows($result);$i++)
////        echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
////    echo ']';
//        file_put_contents("log.txt", $url_params[1]);
//      $json = array();
//      for($i=0; $i<mysqli_num_rows($result);$i++)
//          array_push($json, mysqli_fetch_object($result));
//      echo json_encode($json);
//
//}
//elseif($method == 'POST')
//{
//    echo "Data successfully inserted!";
//}
//

mysqli_close($link);