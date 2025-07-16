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
date_default_timezone_set("Asia/Colombo");
if($system_usertype=="Customer" || $system_usertype=="Guest")
{
    include("index_guest.php");
}
else
{
    include("index_management.php");
}
?>