<?php
session_start();

$filename = 'dbinfo.php';
$projectName = 'v.1';
$base_url = 'http://localhost/iteraCode/';

function loggedin() {
  return isset($_SESSION['username']);
}

function connectdb() {
    include('dbinfo.php');
    mysql_connect($host,$user,$password);
    mysql_select_db($database) or die('Error connecting to database.');
}

function randomAlphaNum($length){
  $rangeMin = pow(36, $length-1);
  $rangeMax = pow(36, $length)-1;
  $base10Rand = mt_rand($rangeMin, $rangeMax);
  $newRand = base_convert($base10Rand, 10, 36);
  return $newRand;
}

function getName(){
	return $projectName = "Grader Online ITERA";
  /*connectdb();
  $query="SELECT name FROM prefs";
  $result = mysql_query($query);
  $row = mysql_fetch_array($result);
  return $row['name'];*/
}

function treat($text) {
	$s1 = str_replace("\n\r", "\n", $text);
	return str_replace("\r", "", $s1);
}

function getScore() {
	$query = "SELECT sum(`score`) FROM `solve` WHERE `username`='" .$_SESSION['username'] ."'";
	$res = mysql_query($query);
	return mysql_fetch_row($res)[0];
}

function getLangEvent($id_ev) {
  $query = "SELECT * FROM `event` WHERE `ev_id`=".$id_ev."";
  $res = mysql_query($query);
  return mysql_fetch_array($res, MYSQL_ASSOC);
}

function debug($var){
  echo "<pre>";
  var_dump($var);
  echo "</pre>";
}

function getTime($tanggal = NULL){
  if ($tanggal == NULL) {
    $tanggal = date("d-m-Y H:i");
  }
  // list($day, $month, $year, $hour, $minute) = split('[- :]', $tanggal);
  // return mktime($hour, $minute,0, $month, $day, $year);
  return $tanggal;
}





























