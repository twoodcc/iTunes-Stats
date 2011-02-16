<?php

/*
Report Name: Most Played Albums
Description: Shows a list of your albums in order of the most listened to songs.
Category: Album
Report URL: http://tville.thruhere.net
Version: 1.2
Author: Tim Wood
Author URL: http://tville.thruhere.net
*/

$title = 'Most Played Albums';

$result = mysql_query("
	SELECT 
	  b.name AS album_name
	, a.name AS artist_name
	, FORMAT(SUM(s.play_count), 0) as play_count
	, count(s.id) AS songs
    , SUM(s.play_count) as play_count2
	FROM song s
	JOIN album b
	ON s.album = b.id
	JOIN artist a
	ON s.artist = a.id
	GROUP BY b.id
	ORDER BY play_count2 DESC
    LIMIT 500
") or die(mysql_error());
$grid = new grid;
$grid->columns = array(
	'Album' => 'album_name'
	,'Artist' => 'artist_name'
	,'# of Songs' => 'songs'
	,'Plays' => 'play_count'
);
while ($data = mysql_fetch_object($result)) {
	$grid->items[] = $data;
}

?>