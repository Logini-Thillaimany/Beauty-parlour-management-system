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
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Responsive Bootstrap4 Shop Template, Created by Imran Hossain from https://imransdesign.com/">

	<!-- title -->
	<title>Lathu bridals</title>

	<!-- favicon -->
	<link rel="shortcut icon" type="image/png" href="assets/img/kaiadmin/logo_light.jpg">
	<!-- google font -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
	<!-- fontawesome -->
	<link rel="stylesheet" href="guest/assets/css/all.min.css">
	<!-- bootstrap -->
	<link rel="stylesheet" href="guest/assets/bootstrap/css/bootstrap.min.css">
	<!-- owl carousel -->
	<link rel="stylesheet" href="guest/assets/css/owl.carousel.css">
	<!-- magnific popup -->
	<link rel="stylesheet" href="guest/assets/css/magnific-popup.css">
	<!-- animate css -->
	<link rel="stylesheet" href="guest/assets/css/animate.css">
	<!-- mean menu css -->
	<link rel="stylesheet" href="guest/assets/css/meanmenu.min.css">
	<!-- main style -->
	<link rel="stylesheet" href="guest/assets/css/main.css">
	<!-- responsive -->
	<link rel="stylesheet" href="guest/assets/css/responsive.css">

</head>
<script>
  //this is for Delete confirm
	function delete_confirm()
	{
	  var response_delete=confirm("Are you sure do you want to delete this record?");
	  if(response_delete)
	  {
		return true;  
	  }  
	  else
	  {
		  return false;
	  }
	}
	</script>
	<script>
  //this is for reactivate confirm
	function reactivate_confirm()
	{
	  var response_reactivate=confirm("Are you sure do you want to reactivate this record?");
	  if(response_reactivate)
	  {
		return true;  
	  }  
	  else
	  {
		  return false;
	  }
	}
	</script>

  <script>
  //this is for accept confirm
	function accept_confirm()
	{
	  var response_accept=confirm("Are you sure do you want to accept this record?");
	  if(response_accept)
	  {
		return true;  
	  }  
	  else
	  {
		  return false;
	  }
	}
	</script>
	<script>
  //this is for reject confirm
	function reject_confirm()
	{
	  var response_reject=confirm("Are you sure do you want to reject this record?");
	  if(response_reject)
	  {
		return true;  
	  }  
	  else
	  {
		  return false;
	  }
	}
</script>
  
<script>
	  //this is for text validation
	function isTextKey(evt) // only text to allow the input field
	{
		var charCode = (evt.which) ? evt.which : event.keyCode;
		if (((charCode >64 && charCode < 91)||(charCode >96 && charCode < 123)||charCode ==08 || charCode ==127||charCode ==32||charCode ==46)&&(!(evt.ctrlKey&&(charCode==118||charCode==86))))
			return true;

			return false;
	}
</script>
	<script>
	 //this is for number validation
	function isNumberKey(evt) // only numbers to allow the input field
	{
		var charCode = (evt.which) ? evt.which : event.keyCode;
		if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
			return false;

			return true;
	}
	</script>
	<script>
	//mobile number validation
	function phonenumber(mobile_text_box_name) // Mobile No 
	{
		var phoneno = /^\d{10}$/;
		if(document.getElementById(mobile_text_box_name).value=="")
		{
		}
		else
		{
			if( document.getElementById(mobile_text_box_name).value.match(phoneno))
			{
				hand(mobile_text_box_name);
			}
			else
			{
				alert("Enter 10 digit Mobile Number");
				document.getElementById(mobile_text_box_name).value="";
				//document.getElementById(mobile_text_box_name).focus()=true;		
				return false;
			}
		}	 
	}
	</script>
	<script>
	function hand(mobile_text_box_name)
	{
		var str = document.getElementById(mobile_text_box_name).value;
		var res = str.substring(0, 2);
		if(res=="07")
		{
			return true;
		}
		else
		{
			alert("Enter 10 digit of Mobile Number start with 07xxxxxxxx");
			document.getElementById(mobile_text_box_name).value="";
		//	document.getElementById(mobile_text_box_name).focus()=true;			
			return false;
		}	
	}
	</script>
	<script>
	//check email validation format
	function emailvalidation()
	{
		var email=document.getElementById("txtemail").value;
		var emailformat=/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
		if (email.match(emailformat))
		{
			
		}
		else if(email.length==0)
		{
			
		}
		else
		{
			alert("Email Address is Invalid");
			document.getElementById("txtemail").value="";
			document.getElementById("txtemail").focus()=true;
		}		
	}
	</script>
	<script>
	//nic format validation
function nicnumber()
{
	var nic=document.getElementById("txtnic").value;
	if(nic.length==10)
	{
		var nicformat1=/^[0-9]{9}[a-zA-Z0-9]{1}$/;
		if(nic.match(nicformat1))
		{
			var nicformat2=/^[0-9]{9}[vVxX]{1}$/;
			if(nic.match(nicformat2))
			{
				calculatedob(nic);
			}
			else
			{
				alert("last character must be V/v/X/x");
				document.getElementById("txtnic").value="";
				document.getElementById("txtnic").focus();
				document.getElementById("txtdob").value="";
				document.getElementById("txtgender").value="";
			}
		}
		else
		{
			alert("First 9 characters must be numbers");
			document.getElementById("txtnic").value="";	
			document.getElementById("txtnic").focus();
			document.getElementById("txtdob").value="";
			document.getElementById("txtgender").value="";
		}	
	}
	else if(nic.length==12)
	{	
		var nicformat3=/^[0-9]{12}$/;
		if(nic.match(nicformat3))
		{
			calculatedob(nic);
		}
		else
		{
			alert("All 12 characters must be number");
			document.getElementById("txtnic").value="";
			document.getElementById("txtnic").focus();
			document.getElementById("txtdob").value="";
			document.getElementById("txtgender").value="";
		}
	}
	else if(nic.length==0)
	{
	    document.getElementById("txtdob").value="";
		document.getElementById("txtgender").value="";
	}
	else
	{
		alert("NIC No must be 10 or 12 Characters");
		document.getElementById("txtnic").value="";
		document.getElementById("txtnic").focus();	
		document.getElementById("txtdob").value="";
		document.getElementById("txtgender").value="";
	}
}
</script>
<script>
//send nic to ajaxpage for get dob
function calculatedob(nic)
{
var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() 
{
  if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
  {
    document.getElementById("txtdob").value = xmlhttp.responseText.trim();
    if(nic.length==10)
    {
      var bday_num = nic.substring(2, 5);
    }
    else
    {
      var bday_num = nic.substring(4, 7);
    }
    if(bday_num>500)
    {
      var gender="Female";
    }
    else
    {
      var gender="Male";
    }
    document.getElementById("txtgender").value = gender;
    assign_minDate();
  }
};
xmlhttp.open("GET", "ajaxpage.php?frompage=dob&dobcal=" + nic, true);
xmlhttp.send();
}
</script>
<body>
	
	<!--PreLoader-->
    <div class="loader">
        <div class="loader-inner">
            <div class="circle"></div>
        </div>
    </div>
    <!--PreLoader Ends-->
	
	<!-- header -->
	<div class="top-header-area" id="sticker">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-sm-12 text-center">
					<div class="main-menu-wrap">
						<!-- logo -->
						<div class="site-logo">
							<a href="index.html">
								<img src="assets/img/kaiadmin/logo.jpg" alt="">
							</a>
						</div>
						<!-- logo -->

						<!-- menu start -->
						<nav class="main-menu">
							<?php
							if($system_usertype=="Customer")
							{
								include("menu_customer.php");
							}
							else if($system_usertype=="Guest")
							{
								include("menu_guest.php");
							}
							?>
						</nav>
						<a class="mobile-show search-bar-icon" href="#"><i class="fas fa-search"></i></a>
						<div class="mobile-menu"></div>
						<!-- menu end -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end header -->
	
	<!-- search area -->
	<div class="search-area">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<span class="close-btn"><i class="fas fa-window-close"></i></span>
					<div class="search-bar">
						<div class="search-bar-tablecell">
							<h3>Search For:</h3>
							<input type="text" placeholder="Keywords">
							<button type="submit">Search <i class="fas fa-search"></i></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end search area -->
	<?php
	if(isset($_GET["page"]))
	{
		?>
		<!-- breadcrumb-section -->
		<div class="breadcrumb-section ">
			<div class="container">
				<!--div class="row">
					<div class="col-lg-8 offset-lg-2 text-center">
					</div>
				</div-->
			</div>
		</div>
		<!-- end breadcrumb section -->
		<?php
	}
	else
	{
	?>
	<!-- hero area -->
	<div class="hero-area hero-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-9 offset-lg-2 text-center">
					<div class="hero-text">
						<div class="hero-text-tablecell">
							<p class="subtitle">Elevate your style, embrace your beauty.</p>
							<h1>Lathu Bridals</h1>
							<div class="hero-btns">
								<a href="shop.html" class="boxed-btn">Saloon Services</a>
								<a href="shop.html" class="boxed-btn"> Makeup </a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end hero area -->
	<?php
		}

		if(isset($_GET["page"]))
		{
			echo '<div class="list-section pt-80 pb-80">
				<div class="container">';
					include($_GET["page"]);
				echo '</div>
			</div>';
		}
		else
		{
			include("body_guest.php");
		}
	?>
	

	<!-- footer -->
	<div class="footer-area">
		<div class="container">
			<div class="row">
				<div class="col-lg-3 col-md-6">
					<div class="footer-box about-widget">
						<h2 class="widget-title">About us</h2>
						<p>our team is committed to providing a warm, friendly, and relaxing environment 
						   where each customer receives personalized care.
						  Our goal is not only to enhance outer beauty but also to boost confidence and inner joy.</p>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="footer-box get-in-touch">
						<h2 class="widget-title">Get in Touch</h2>
						<ul>
							<li>20,Vaddu East,Vaddukoddai,</br> Jaffna, Sri Lanka.</li>
							<li>lathubridal@gmail.com</li>
							<li>+94750691654</li>
						</ul>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="footer-box pages">
						<h2 class="widget-title">Pages</h2>
						<ul>
							<li><a href="index_guest.php">Home</a></li>
							<li><a href="booking.php">Bookings</a></li>
							<li><a href="about.php">About</a></li>
							<li><a href="contact.html">Contact</a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="footer-box subscribe">
						<h2 class="widget-title">Subscribe</h2>
						<p>Subscribe to our mailing list to get the latest updates.</p>
						<form action="index.html">
							<input type="email" placeholder="Email">
							<button type="submit"><i class="fas fa-paper-plane"></i></button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end footer -->
	
	<!-- copyright -->
	<div class="copyright">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-md-12">
					<p>Copyrights &copy; 2025 - <a href="#">Lathu Bridals</a>,  All Rights Reserved.<br> </p>
				</div>
				<div class="col-lg-6 text-right col-md-12">
					<div class="social-icons">
						<ul>
							<li><a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
							<li><a href="#" target="_blank"><i class="fab fa-twitter"></i></a></li>
							<li><a href="#" target="_blank"><i class="fab fa-instagram"></i></a></li>
							<li><a href="#" target="_blank"><i class="fab fa-linkedin"></i></a></li>
							<li><a href="#" target="_blank"><i class="fab fa-dribbble"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end copyright -->
	
	<!-- jquery -->
	<script src="guest/assets/js/jquery-1.11.3.min.js"></script>
	<!-- bootstrap -->
	<script src="guest/assets/bootstrap/js/bootstrap.min.js"></script>
	<!-- count down -->
	<script src="guest/assets/js/jquery.countdown.js"></script>
	<!-- isotope -->
	<script src="guest/assets/js/jquery.isotope-3.0.6.min.js"></script>
	<!-- waypoints -->
	<script src="guest/assets/js/waypoints.js"></script>
	<!-- owl carousel -->
	<script src="guest/assets/js/owl.carousel.min.js"></script>
	<!-- magnific popup -->
	<script src="guest/assets/js/jquery.magnific-popup.min.js"></script>
	<!-- mean menu -->
	<script src="guest/assets/js/jquery.meanmenu.min.js"></script>
	<!-- sticker js -->
	<script src="guest/assets/js/sticker.js"></script>
	<!-- main js -->
	<script src="guest/assets/js/main.js"></script>

</body>
</html>