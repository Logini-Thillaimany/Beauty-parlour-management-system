<?php
$con=mysqli_connect("localhost","root","","parlourmanagement");//hostname,username,password,DB
if(!$con)
{
	die("Server connection error!!!".mysqli_connect_error());
}
?>