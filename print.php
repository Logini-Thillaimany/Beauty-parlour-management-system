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
include("connection.php");
?>
<html>
	<head>
		<title>Lathu Bridals</title>
	 <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />

		<script language="javascript">
		document.onmousedown=disableclick;
		status="Right Click Disabled";
		function disableclick(event)
		{
		  if(event.button==2)
		   {
			 alert(status);
			 return false;    
		   }
		}
		</script>
		<script type="text/javascript">
		// for print button
			function printpage() {
				//Get the print button and put it into a variable
				var printButton = document.getElementById("printpagebutton");
				//Set the print button visibility to 'hidden' 
				printButton.style.visibility = 'hidden';
				//Print the page content
				window.print()
				//Set the print button to 'visible' again 
				//[Delete this line if you want it to stay hidden after printing]
				printButton.style.visibility = 'visible';
			}
		</script>
	</head>
	<body oncontextmenu="return false">
		<table width="1200" border="0" align="center">
			<tr>  <!-- banner start -->
				<td><center><img src="file/letter_head.png" height="220" width="1200"></center></td>
			</tr>
			<tr>
				<td>
					<input id="printpagebutton" type="button" class="btn btn-primary" value="Print Report" onclick="printpage()"/>  <!-- print button, but it was not visible in printed paper  -->
					<br><br>
					<?php

						if(isset($_GET['print'])) // if get print
						{
							$filename=$_GET['print'];
							include($filename);
						}
					?>
				</td>
			</tr>
		</table>
	</body>
</html>
