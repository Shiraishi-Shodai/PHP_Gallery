<?php
//=====================================================================
//Program		db_connect.php
//=====================================================================
mb_internal_encoding("utf-8");
$db_dbms = "sqlite";
$db_host = "./";
$db_name = "gallery.db";
$db_user = null;
$db_pass = null;
if($db_dbms == "sqlite")
	$db_dsn  = "{$db_dbms}:{$db_host}{$db_name}";
else
	$db_dsn  = "{$db_dbms}:host={$db_host};dbname={$db_name}";
//---------------------------------------------------------------------
if(!($conn=new PDO($db_dsn, $db_user, $db_pass))){
	echo "Cannot connect database.\n";
	exit;
}
if($db_dbms == "mysql")
	$conn->exec('SET NAMES utf8');
if($db_dbms == "sqlite"){
	$q1="CREATE TABLE IF NOT EXISTS photo ( "
		."id integer PRIMARY KEY, "
		."title string, "
		."author string, "
		."date integer, "
		."file string, "
		."image blob)";
	$q2="CREATE TABLE IF NOT EXISTS users ( "
		."u_id integer PRIMARY KEY, "
		."u_name string, "
		."u_pass string)";
	$conn->exec($q1);
	$conn->exec($q2);
}
?>