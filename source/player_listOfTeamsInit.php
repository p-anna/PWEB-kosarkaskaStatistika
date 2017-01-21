<?php
/**
 * Created by PhpStorm.
 * User: msuko
 * Date: 15/01/2017
 * Time: 19:54
 */
error_reporting(0);
$method = $_SERVER['REQUEST_METHOD'];
$url_params = explode("/",$_SERVER['PATH_INFO']);

//echo '["jdflaskjf" : "fkjshdlkjf"]';

 $link = mysqli_connect('localhost','root','root','mydb');
 mysqli_set_charset($link,'utf8');

 if(mysqli_connect_errno())
 {
     printf("SQL CONNECT ERROR: %s\n", mysqli_connect_error());
 }

 $table = $url_params[1];

 //Implementirati metodu koja dohvata sve reci iz leksikona
// if($method == 'GET' && $table == 'players') //&& count($url_params) == 4)
// {
     file_put_contents("log.txt", "4 parametra");
     $sql = "SELECT idTeam, teamName FROM Team";
     $result = mysqli_query($link,$sql);
     $json = array();
     for($i=0; $i<mysqli_num_rows($result);$i++)
         array_push($json, mysqli_fetch_object($result));
     echo json_encode($json);
// }
