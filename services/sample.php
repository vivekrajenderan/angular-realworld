<?php 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: application/json');
$array=array('name'=>'vivek','email'=>'vivek@gmail.com');

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "angular4";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT id, firstname, lastname FROM users";
$result = $conn->query($sql);
$userlist=array();
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	$userlist[]=$row;
    	       
    }
} else {
    echo "0 results";
}
$conn->close();

$request = json_encode($userlist);
echo $request;
/*
echo "<pre>".print_r($_POST);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: application/json');
$post_request = file_get_contents('php://input');
$request = json_decode($post_request, true);
echo "<pre>".print_r($request);
*/
?>
