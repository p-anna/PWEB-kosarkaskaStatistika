<?php
/**
 * Created by PhpStorm.
 * User: msuko
 * Date: 21/01/2017
 * Time: 20:57
 */

//$url_params = explode("/",$_SERVER['PATH_INFO']);
//
//echo var_dump($_SERVER['PATH_INFO']);
//echo var_dump($_GET);
//
//echo var_dump($_GET['pajser'] === null);
//
//echo var_dump($_GET['tata']);

$conn = new mysqli("localhost","root", "root", "mydb");

$rez = $conn->query("select count(*) as 'GP', avg(MIN2) as 'min', avg(pts) as 'ppg' from playerStats group by playerId");
$i = 0;
for ($i = 0; $i < $rez->num_rows; $i++){
    var_dump($rez->fetch_assoc());
}