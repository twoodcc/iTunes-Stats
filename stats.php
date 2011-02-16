<?php

require_once('config.inc.php');
require_once('backend/objects.inc.php');
require_once('backend/functions.inc.php');

$database_connection = mysql_connect(
	$database_host
	, $database_user
	, $database_pass
);
if ($database_connection) {
	if (!mysql_select_db($database_name, $database_connection)) {
		die('<p>Error selecting database: '.$database_name);
	}
}
else {
	die('<p>Error connecting to database: '.$database_host);
}

$result = mysql_query("
				SELECT sum(play_count)
				FROM song
			") or die(mysql_error());
			
while($row = mysql_fetch_array($result)){
	echo "Plays: ".number_format($row[0]);
	echo "<br>";
    $plays = $row[0];
}

$day = 1;
$month = 1;
$year = 2010;

$startunix = mktime (0, 0, 0, $month, $day, $year);
$nowunix = time();
$timeunix = $nowunix - $startunix;
$time = floor($timeunix / (24 * 60 * 60));
$goal = 1000000;

$result = mysql_query("
				SELECT count(play_count)
				FROM song
			") or die(mysql_error());
			
while($row = mysql_fetch_array($result)){
	echo "Songs: ".number_format($row[0]);
	echo "<br>";
}
$result = mysql_query("
				SELECT count(name)
				FROM artist
			") or die(mysql_error());
			
while($row = mysql_fetch_array($result)){
	echo "Artists: ".number_format($row[0]);
	echo "<br>";
}

$result = mysql_query("
				SELECT count(name)
				FROM album
			") or die(mysql_error());
			
while($row = mysql_fetch_array($result)){
	echo "Albums: ".number_format($row[0]);
	echo "<br>";
}

$day = 1;
$month = 12;
$year = 2009;

$startunix = mktime (0, 0, 0, $month, $day, $year);
$nowunix = time();
$timeunix = $nowunix - $startunix;
$time = floor($timeunix / (24 * 60 * 60));
$goal = 1000000000;

$left = $goal - $plays;
$perday = $plays / $time;
$til = $left / $perday;

echo "Plays perday: ".number_format($perday, 2)."<br>"; 
echo "Days to 1 bil: ".number_format($til, 2)."<br>";

//convert plays to data
$data = $plays*4;
//$mb = $data/1048576;
$gb = $data/1024;
$tb = $gb/1024;
$pb = $tb/1024;

echo "PB used: ".number_format($pb, 3)."<br>";

echo "<a href='index.php'>Index</a>";
echo "<br>";
echo "<a href='artist.php'>Top Artists</a>";
echo "<br>";
echo "<a href='song.php'>Top Songs</a>";
echo "<br>";
echo "<a href='album.php'>Top Albums</a>";
?>