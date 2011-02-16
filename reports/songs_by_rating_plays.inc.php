<?php

/*
Report Name: 500 Top Rated and Most Played Songs
Description: Shows a list of your songs in order of "most listened to" weighted by rating.
Category: Song
Report URL: http://www.alexking.org/software/itunes_stats/
Version: 1.0
Author: Alex King
Author URL: http://www.alexking.org/
*/

$title = '500 Top Rated and Most Played Songs';

$result = mysql_query("
	SELECT 
	  a.name AS artist_name
	, b.name AS album_name
	, s.name AS song_name
	, FORMAT(s.play_count, 0) AS play_count
	, s.rating AS rating
	, FORMAT(s.rating * s.play_count, 0) AS rank
    , s.rating * s.play_count AS rank2
	FROM song s
	JOIN artist a
	ON s.artist = a.id
	JOIN album b
	ON s.album = b.id
	ORDER BY rank2 DESC
	LIMIT 500
") or die(mysql_error());
$grid = new grid;
$grid->columns = array(
	'Song' => 'song_name'
	,'Artist' => 'artist_name'
	,'Album' => 'album_name'
	,'Plays' => 'play_count'
	,'Rating' => 'rating'
	,'Rank' => 'rank'
);
while ($data = mysql_fetch_object($result)) {
	$grid->items[] = $data;
}

?>