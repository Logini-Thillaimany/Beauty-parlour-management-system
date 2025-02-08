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

if(isset($_GET["frompage"]))
{
	if($_GET["frompage"]=="dob")
	{
		$selnic = $_GET["dobcal"];
		if(strlen($selnic)==10)
		{
			$bdayyear=substr($selnic, 0,2);
			$bdayyear=$bdayyear+1900;
			$bdaynum=substr($selnic, 2,3);
		}
		else if(strlen($selnic)==12)
		{
			$bdayyear=substr($selnic, 0,4);
			$bdaynum=substr($selnic, 4,3);
		}
		
		$bdaynum1=0;
		if($bdaynum>500)
		{
			$bdaynum1=$bdaynum-500;
			
		}
		else
		{
			$bdaynum1=$bdaynum;
		}
		
		$bdaydate;
		
		$month=array(31,29,31,30,31,30,31,31,30,31,30,31);
		$day_cal=0;//add total days of months
		$bdaymonth=0;
		$bdayday=0;
		for($x=0;$x<count($month);$x++)
		{
			$day_cal=$day_cal+$month[$x];
			if($day_cal>=$bdaynum1)
			{
				$bdayday=$bdaynum1-(($day_cal)-($month[$x]));
				$bdaymonth=++$x;
				break;
			}
		}
		$bdaydate=$bdayyear."-".$bdaymonth."-".$bdayday;
		$bdaydate=date("Y-m-d", strtotime($bdaydate));
		echo $bdaydate;
	}
}
?>
