<?php

$name= $_POST['name'];
$email= $_POST['email'];

//Database connection
 $conn=new mysqli('localhost','root','', 'test');
if ($conn->connect_error) {
    die("Connection failed: ".$conn->connect_error);
}else{
	$stmt = $conn->prepare("insert into registration(name,email)values(?,?)");
	$stmt->bind_param("ss", $name,$email );
	$stmt->execute();
	echo "Registration Successfully";
	$stmt->close();
	$conn->close();

}
?>