<?php

/*
Report Name: Top Rated Artists
Description: Shows a list of your top rated artists (by song rating).
Category: Artist
Report URL: http://www.alexking.org/software/itunes_stats/
Version: 1.0
Author: Alex King
Author URL: http://www.alexking.org/
*/

$result = mysql_query("
	SELECT 
	  a.name AS artist_name
	, FORMAT(ROUND(AVG(s.rating), 2), 0) AS rating
    , ROUND(AVG(s.rating), 2) AS rating2
	, count(s.id) AS songs
	FROM song s
	JOIN artist a
	ON s.artist = a.id
	WHERE s.rating > 0
	GROUP BY a.id
	ORDER BY rating2 DESC
") or die(mysql_error());
$grid = new grid;
$grid->columns = array(
	'Artist' => 'artist_name'
	,'# of Songs' => 'songs'
	,'Rating' => 'rating'
);
while ($data = mysql_fetch_object($result)) {
	$grid->items[] = $data;
}
$title = 'Top Rated Artists';

?>