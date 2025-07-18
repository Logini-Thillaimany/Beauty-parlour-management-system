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

// Fetch balance payment notifications
$today = date("Y-m-d");
$balance_payment_notification=0;

// Fetch balance payments based on user type 
$sql_balance_payment_booking="SELECT booking_id,bookdate,customer_id,totalamount,servicedate FROM booking WHERE ( status='Accept' OR status='Finish' ) AND customer_id='$system_user_id' ORDER BY booking_id DESC";
$result_balance_payment_booking=mysqli_query($con,$sql_balance_payment_booking) or die("sql error in sql_balance_payment_booking ".mysqli_error($con));
while($row_balance_payment_booking=mysqli_fetch_assoc($result_balance_payment_booking))
{	
    $sql_paied_amount= "SELECT SUM(payamount) AS total_pay FROM payment WHERE booking_id='$row_balance_payment_booking[booking_id]' AND paystatus='Paid'";
    $result_paied_amount=mysqli_query($con,$sql_paied_amount) or die("sql error in sql_paied_amount ".mysqli_error($con));	
    $row_paied_amount=mysqli_fetch_assoc($result_paied_amount);

    $Total_amount=$row_balance_payment_booking["totalamount"];
    $Paied_amount=$row_paied_amount["total_pay"];
    $balance=$Total_amount- $Paied_amount;
    if($balance>0 )
    {
        $balance_payment_notification++;
    }
}
$total_notification+=$balance_payment_notification;

?>
<ul>
	<li class="current-list-item"><a href="index.php">Home</a>
	</li>
	<li><a href="#">Parlor</a>
		<ul class="sub-menu">
			<?php
			$sql_category="SELECT * FROM packagecategory WHERE status='Active'";
			$result_category=mysqli_query($con,$sql_category) or die("sql error in sql_category ".mysqli_error($con));
			while($row_category=mysqli_fetch_assoc($result_category))
			{
				echo '<li><a href="index.php?page=body_subcategory.php&category_id='.$row_category["category_id"].'">'.$row_category["name"].'</a></li>';
			}
			?>
		</ul>
	</li>
	<li><a href="index.php?page=booking.php&option=view">Booking</a>
		<ul class="sub-menu">
			<li><a href="index.php?page=booking.php&option=view">Book</a></li>
		</ul>
	</li>
	<li><a href="#">Notification (<?php echo $total_notification; ?>)</a>
		<ul class="sub-menu">
			<li>
				<a href="index.php?page=notification_upcoming_booking.php">
					Upcoming Bookings(<?php echo $upcoming_booking_notification; ?>)
				</a>
			</li>
			<li>
				<a href="index.php?page=notification_balance_payment.php">
					Incomplete Payment(<?php echo $balance_payment_notification; ?>)
				</a>
			</li> 
		</ul>
	</li>
	<li>
		<a href="index.php?page=about.php">About</a>
	</li>
	<li>
		<a href="index.php?page=contact.php">Contact</a>
	</li>
	<li>
		<div class="header-icons">
			<!---<a class="shopping-cart" href="cart.html"><i class="fas fa-shopping-cart"></i></a>
			<a class="mobile-hide search-bar-icon" href="#"><i class="fas fa-search"></i></a>--->
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