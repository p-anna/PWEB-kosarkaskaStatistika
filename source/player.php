<?php
/**
 * Created by PhpStorm.
 * User: msuko
 * Date: 23/01/2017
 * Time: 00:01
 */

$id = $_GET['idPlayer'];

    //switch($argc)

$conn = new mysqli("localhost", "root", "root", "mydb");
$sql = "select * from Player where trim(idPlayer)=trim('$id')";
$result = $conn->query($sql);

$poruka = new stdClass();
$json = mysqli_fetch_assoc($result);
$poruka->info = $json;

$poruka->stats = array();

echo json_encode($poruka);