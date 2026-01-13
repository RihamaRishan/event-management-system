<?Php

$servername = "localhost";
$username ="root";
$password ="";
$database ="eventease_db";

//Create connection
$conn = mysqli_connect($servername,
$username, $password,$database);

//Check connection
if (!$conn){
die ("Xonnection failed:"  .
mysqli_connect_error());
}
?>
