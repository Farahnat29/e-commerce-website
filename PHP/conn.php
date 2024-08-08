<?php
$server="localhost";
$username="root";
$password="";
$db="journifly";
//create conn
$conn=new mysqli($server,$username,$password,$db);

if($conn==TRUE){
	echo "connection done"."<br>";

}else{
	die("connection failed: ".mysqli_connect_error($conn));
}


?>