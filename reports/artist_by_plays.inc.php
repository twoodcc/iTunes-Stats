<?php

/*
Report Name: Most Played Artists
Description: Shows a list of your artists in order of the most listened to songs.
Category: Artist
Report URL: http://www.alexking.org/software/itunes_stats/
Version: 1.2
Author: Tim Wood
Author URL: http://twitter.com/twood3/
*/

$title = 'Most Played Artists';

$result = mysql_query("
	SELECT 
	a.name AS artist_name
	, FORMAT(SUM(s.play_count), 0) as play_count
    , SUM(s.play_count) as play_count2
	, count(s.id) AS songs
	FROM song s
	JOIN artist a
	ON s.artist = a.id
	GROUP BY a.id
	ORDER BY play_count2 DESC
") or die(mysql_error());
$grid = new grid;
$grid->columns = array(
	'Artist' => 'artist_name'
	,'# of Songs' => 'songs'
	,'Plays' => 'play_count'
);
while ($data = mysql_fetch_object($result)) {
	$grid->items[] = $data;
}

?>