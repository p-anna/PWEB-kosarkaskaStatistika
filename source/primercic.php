<?php
error_reporting(0);
$method = $_SERVER['REQUEST_METHOD'];
$url_params = explode("/",$_SERVER['PATH_INFO']);

$link = mysqli_connect('localhost','root','','sentiments'); /* ovde treba da se povezete na nasu bazu */
mysqli_set_charset($link,'utf8');

if(mysqli_connect_errno())
{
    printf("SQL CONNECT ERROR: %s\n", mysqli_connect_error());
}

$table = $url_params[1];

//Implementirati metodu koja dohvata sve reci iz leksikona
if($method == 'GET' && $table == 'players' && count($url_params) == 4) // 1 + 3 opadajuce liste
{
    $sql = "SELECT * FROM lexicon";
}
//Metoda koja dohvata samo jednu rec iz leksikona vraca objekat koji sadrzi rec i njen skor ili false ako se trazena rec ne nalazi u leksikonu
if($method == 'GET' && $table == 'teams' && count($url_params) == 1) // samo imena tima da vratis
{
    $word = $url_params[2];
    $sql = "SELECT * FROM lexicon WHERE word='$word'";
}


$result = mysqli_query($link, $sql);

if(!$result)
{
    printf("SQL ERROR: %s\n",mysqli_error($link));
}

if($method == 'GET')
{
    echo '[';
    for($i=0; $i<mysqli_num_rows($result);$i++)
        echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
    echo ']';
}



mysqli_close($link);