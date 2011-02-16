
<html>
<head>
	<title>Uploadling...</title>
</head>
<body>
<h1>Uploadling File...</h1>

</body>
</html>

<?php

$target_path = "uploads/";

$target_path = $target_path . basename( $_FILES['userfile']['name']); 

if(move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path)) {
    echo "The file ".  basename( $_FILES['userfile']['name']). 
    " has been uploaded";
} else{
    echo "There was an error uploading the file, please try again!";
}
echo "<br>";
echo "<a href='index.php'>Index</a>";

?>