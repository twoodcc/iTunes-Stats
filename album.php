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
	SELECT 
	  b.name AS album_name
	, a.name AS artist_name
	, SUM(s.play_count) as play_count
	, count(s.id) AS songs, 
b.id as id
	FROM song s
	JOIN album b
	ON s.album = b.id
	JOIN artist a
	ON s.artist = a.id
	GROUP BY b.id
	ORDER BY play_count DESC
LIMIT 500
") or die(mysql_error());

echo "<table border='1'>
		<tr>
		<td><b>#</b></td>
		<td><b>Album</b></td>
		<td><b>Artist</b></td>
		<td><b>Plays</b></td>
		</tr>";
		$count=1;
while($row = mysql_fetch_array($result)){
	echo "<tr>";
    $id = $row['id'];
    $name = $row['album_name'];
	echo "<td>".$count."</td>";
	//echo "<td>".$row['album_name']."</td>";
    echo "<td>" ."<a href=hit2.php?id=" . $id . ">$name</a>". "</td>" ;
	echo "<td>".$row['artist_name']."</td>";
	echo "<td>".number_format($row['play_count'])."</td>";
	echo "</tr>";
	$count++;
	}

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
echo "<a href='index.php'>Index</a>";
echo "<br>";
echo "<a href='artist.php'>Top Artists</a>";
echo "<br>";
echo "<a href='song.php'>Top Songs</a>";
echo "<br>";
echo "<a href='album.php'>Top Albums</a>";
?>