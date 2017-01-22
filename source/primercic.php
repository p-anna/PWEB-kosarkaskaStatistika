<?php
error_reporting(0);
$method = $_SERVER['REQUEST_METHOD'];
$url_params = explode("/",$_SERVER['PATH_INFO']);

$link = mysqli_connect('localhost','root','root','mydb');
mysqli_set_charset($link,'utf8');

if(mysqli_connect_errno())
{
    printf("SQL CONNECT ERROR: %s\n", mysqli_connect_error());
}

$table = $url_params[1];

//Implementirati metodu koja dohvata sve reci iz leksikona
if($method == 'GET' && $table == 'players' && count($url_params) == 4)
{
    file_put_contents("log.txt", "4 parametra");
    $sql = "SELECT * FROM Player";
}
//Metoda koja dohvata samo jednu rec iz leksikona vraca objekat koji sadrzi rec i njen skor ili false ako se trazena rec ne nalazi u leksikonu
if($method == 'GET' && $table == 'players' && count($url_params) == 3)
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


mysqli_close($link);