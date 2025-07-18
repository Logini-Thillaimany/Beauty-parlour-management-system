<?php
if(!isset($_SESSION))
{
	session_start();
}
if(isset($_SESSION["login_usertype"]))
{//someone who login to the system
	$system_usertype=$_SESSION["login_usertype"];
	$system_user_id=$_SESSION["login_user_id"];
	$system_username=$_SESSION["login_username"];
}
else
{//guest or public
	$system_usertype="Guest";
}
include("connection.php");

if($system_usertype=="Guest")
{
    $_SESSION["online_guest_book"]=$_GET["package_id"];
    echo '<script>window.location.href="index.php?page=login.php";</script>';
}
else
{
    echo '<script>window.location.href="index.php?page=bookingpackage.php&option=add";</script>';
}
?>