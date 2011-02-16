<?php

/*
Report Name: 500 Most Played Songs
Description: Shows a list of your songs in order of "most listened to".
Category: Song
Report URL: http://www.alexking.org/software/itunes_stats/
Version: 1.2
Author: Tim Wood
Author URL: http://twitter.com/twood3/
*/

$title = '500 Most Played Songs';

$result = mysql_query("
	SELECT 
	  a.name AS artist_name
	, b.name AS album_name
	, s.name as song_name
	, FORMAT(s.play_count, 0) as play_count
    , s.play_count as play_count2
	FROM song s
	JOIN artist a
	ON s.artist = a.id
	JOIN album b
	ON s.album = b.id
	ORDER BY play_count2 DESC
	LIMIT 500
") or die(mysql_error());
$grid = new grid;
$grid->columns = array(
	'Song' => 'song_name'
	,'Artist' => 'artist_name'
	,'Album' => 'album_name'
	,'Plays' => 'play_count'
);
while ($data = mysql_fetch_object($result)) {
	$grid->items[] = $data;
}

?>