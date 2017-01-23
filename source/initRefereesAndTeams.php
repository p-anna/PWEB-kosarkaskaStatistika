<?php
/**
 * Created by PhpStorm.
 * User: msuko
 * Date: 23/01/2017
 * Time: 00:35
 */

 $link = mysqli_connect('localhost','root','root','mydb');

 if(mysqli_connect_errno())
 {
     printf("SQL CONNECT ERROR: %s\n", mysqli_connect_error());
 }
 $poruka = new stdClass();
 $sql = "SELECT idTeam, teamName FROM Team";
 $result = mysqli_query($link,$sql);
 $json = array();
 for($i=0; $i<mysqli_num_rows($result);$i++)
     array_push($json, mysqli_fetch_object($result));


 $poruka->teams = $json;
 $sql = "SELECT idReferee, refereeName FROM referee";
 $result = mysqli_query($link,$sql);
 $json = array();
 for($i=0; $i<mysqli_num_rows($result);$i++)
    array_push($json, mysqli_fetch_object($result));

 $poruka->referees = $json;

 echo json_encode($poruka);
// }
