<?php 

// iTunes Stats
//
// Copyright (c) 2005-2006 Alex King. All rights reserved.
// http://alexking.org/projects/itunes-stats
//
// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
// **********************************************************************

// requires PHP 5+ (XML libraries) and MySQL 4+ (case insensitive SELECTs)

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

if (!empty($_GET['action'])) {
	switch ($_GET['action']) {
		case 'load':
			//$result = mysql_query("TRUNCATE artist"); 
			//$result = mysql_query("TRUNCATE album"); 
			//$result = mysql_query("TRUNCATE song"); 
	
			if (!file_exists('uploads/Library.xml')) {
				die('<p>Export your iTunes music library as <strong>Library.xml</strong> and place it in this directory.</p>');
			}
			
			$data = simplexml_load_file('uploads/Library.xml');
			
			if (!$data) {
				die('<p>Error loading iTunes data.');
			}
			
			$properties = array(
				'Name'
				, 'Artist'
				, 'Album'
				, 'Rating'
                , 'Time'
                , 'Play Date'
                , 'Year'
                , 'Size'
				, 'Play Count'
			);
			
			$count = 0;
			
			foreach ($data->dict->dict->dict as $tune) {
				$song = new song;
				$next = false;
				foreach ($tune as $property) {
					if ($next != false) {
// this is such an ugly hack, but otherwise the data was still in a simpleXMLObject 
// and I didn't know how to get it out - somebody help me out here
						ob_start();
						print($property);
						$song->$next = ob_get_contents();
						ob_end_clean();
					}
					if (in_array($property, $properties)) {
						$next = strtolower(str_replace(' ', '_', $property));
					}
					else {
						$next = false;
					}
				}
				if ($song->insert()) {
					$count++;
				}
			}
			header("Location: index.php");
			die();
			break;
	}
}
else if (isset($_GET['report']) && file_exists('reports/'.$_GET['report'].'.inc.php')) {
	include('reports/'.$_GET['report'].'.inc.php');
}
else {
	$title = 'Home';
	$reports = get_reports();
}
?>
<html>
	<head>
		<title>iTunes Stats - <?php print($title); ?></title>
		<link rel="stylesheet" type="text/css" href="css/default.css" />
	</head>
	<body>
		<h1>iTunes Stats <?php if (!empty($your_name)) { print(' for '.$config_your_name); } ?></h1>
		<div id="menu">
			<ul>
				<li><a href="index.php">Home</a></li>
			</ul>
		</div>
		<div id="content">

<?php
if (isset($grid)) {
	print('
		<h2>'.$title.'</h2>
	');
	$grid->display();
}
else {
?>
<h2>(Re)Load Data</h2>
<ol>
	<li><a href="index.php?action=load">Import iTunes data to MySQL</a></li>
	<li><a href="upload.html">Upload iTunes data</a></li>
	<li><a href="stats.php">iTunes Stats</a></li>
</ol>
<h2>Reports</h2>
<dl class="reports">
<?php
	foreach ($reports as $report) {
		if (!is_array($report)) {
			$report->print_dl_item();
		}
	}
?>
</dl>
<?php
	foreach ($reports as $title => $reports) {
		if (is_array($report)) {
			print_reports_category($title, $reports);
		}
	}
}
?>

		</div>
		<p id="footer">Copyright &copy; 2011 <a href="http://tville.thruhere.net/">twoodcc</a>. All rights reserved. Version 1.6 beta.</p>
	</body>
</html>