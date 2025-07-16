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
// Initialize total notification count
$total_notification=0;

// Fetch upcoming bookings notifications
$today = date("Y-m-d"); 
$sql_upcoming_bookings="SELECT booking_id,bookdate,customer_id,totalamount,servicedate FROM booking WHERE status='Accept' AND servicedate >='$today' AND customer_id='$system_user_id' ORDER BY booking_id DESC";
$result_upcoming_bookings=mysqli_query($con,$sql_upcoming_bookings) or die("sql error in sql_upcoming_bookings ".mysqli_error($con));
$upcoming_booking_notification=mysqli_num_rows($result_upcoming_bookings);                 

$total_notification+=$upcoming_booking_notification;

?>
<ul>
	<li class="current-list-item"><a href="index.php">Home</a>
	</li>
	<li><a href="#">Parlor</a>
		<ul class="sub-menu">
			<li><a href="404.html">Saloon Service</a></li>
			<li><a href="about.php">Makeup</a></li>
		</ul>
	</li>
	<li><a href="shop.html">Booking</a>
		<ul class="sub-menu">
			<li><a href="shop.html">Shop</a></li>
			<li><a href="checkout.html">Check Out</a></li>
			<li><a href="single-product.html">Single Product</a></li>
			<li><a href="cart.html">Cart</a></li>
		</ul>
	</li>
	<li><a>Notification (<?php echo $total_notification; ?>)</a>
		<ul class="sub-menu">
			<li>
				<a href="index.php?page=notification_upcoming_booking.php">
					<span class="sub-item">Upcoming Bookings(<?php echo $upcoming_booking_notification; ?>)</span>
				</a>
			</li>
			
		</ul>
	</li>
	<li>
		<div class="header-icons">
			<!---<a class="shopping-cart" href="cart.html"><i class="fas fa-shopping-cart"></i></a>--->
			<a class="mobile-hide search-bar-icon" href="#"><i class="fas fa-search"></i></a>
		</div>
	</li>
	<li><a href="#">Profile</a>
		<ul class="sub-menu">
			<li><a href="index.php?page=message.php&option=view">Message</a></li>
			<li><a href="index.php?page=profile.php">Profile</a></li>
			<li><a href="index.php?page=changepassword.php">Change Passowrd</a></li>
			<li><a href="logout.php">Logout</a></li>
		</ul>
	</li>
</ul>