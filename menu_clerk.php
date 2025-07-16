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

// Fetch new booking notifications
$sql_newBooking="SELECT booking_id,bookdate,customer_id,totalamount,servicedate FROM booking WHERE status='Pending' ORDER BY booking_id DESC";
$result_newBooking=mysqli_query($con,$sql_newBooking) or die("sql error in sql_newBooking ".mysqli_error($con));
$newbooking_notification=mysqli_num_rows($result_newBooking);

$total_notification+=$newbooking_notification;

// Fetch pending payment notifications
$sql_pending_payments="SELECT payment_id,booking_id,paydate,payamount,paystatus FROM payment WHERE paystatus='Pending' ORDER BY payment_id DESC";
$result_pending_payments=mysqli_query($con,$sql_pending_payments) or die("sql error in sql_pending_payments ".mysqli_error($con));
$pending_payments_notification=mysqli_num_rows($result_pending_payments);

$total_notification+=$pending_payments_notification;

// Fetch upcoming bookings notifications
$today = date("Y-m-d"); 
$sql_upcoming_bookings="SELECT booking_id,bookdate,customer_id,totalamount,servicedate FROM booking WHERE status='Accept'AND servicedate >'$today' ORDER BY booking_id DESC";
$result_upcoming_bookings=mysqli_query($con,$sql_upcoming_bookings) or die("sql error in sql_upcoming_bookings ".mysqli_error($con));
$upcoming_booking_notification=mysqli_num_rows($result_upcoming_bookings);                 

$total_notification+=$upcoming_booking_notification;

// Fetch finished bookings notifications
$finished_booking_notification=0;
$sql_finished_bookings="SELECT booking_id,servicedate FROM booking WHERE status='Finish'AND servicedate <='$today'  ORDER BY booking_id DESC";
$result_finished_bookings=mysqli_query($con,$sql_finished_bookings) or die("sql error in sql_finished_bookings ".mysqli_error($con));
while($row_view=mysqli_fetch_assoc($result_finished_bookings))
{
  $sql_maxtime="SELECT MAX(endtime) AS maxtime FROM bookingpackage WHERE booking_id='$row_view[booking_id]' ";
  $result_maxtime=mysqli_query($con,$sql_maxtime) or die("sql error in sql_maxtime ".mysqli_error($con));
  $row_maxtime=mysqli_fetch_assoc($result_maxtime);
  $endtime=date("H:i:s", strtotime($row_maxtime["maxtime"]));
  $timenow=date("H:i:s");
  $servicedate = $row_view["servicedate"];
  // Check if the service date is earlier or  if service date is today and the end time is before or equal to the current time
  if($servicedate < $today || ($servicedate == $today && $endtime < $timenow))
  {
    $finished_booking_notification++;
  }
}

$total_notification+=$finished_booking_notification;               

// Fetch upcoming leave notifications
$today = date("Y-m-d");
$sql_upcoming_leave="SELECT leave_id,staff_id,startdate,enddate,status FROM staffleave WHERE status='Approved' AND startdate>=$today ORDER BY startdate DESC";
$result_upcoming_leave=mysqli_query($con,$sql_upcoming_leave) or die("sql error in sql_upcoming_leave ".mysqli_error($con));
$upcoming_leave_notification=mysqli_num_rows($result_upcoming_leave);

$total_notification+=$upcoming_leave_notification;

?>
<ul class="nav nav-secondary">
              <li class="nav-item">
                <a  href="index.php">
                  <i class="fas fa-home"></i>
                  <p>Home</p>
                </a>
              </li>
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Components</h4>
              </li>
              <li class="nav-item active">
                <a data-bs-toggle="collapse" href="#base" class="collapsed" aria-expanded="true">
                  <i class="fas fa-address-card"></i>
                  <p>Management</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="base">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="index.php?page=staff.php&option=view">
                        <span class="sub-item">Staff</span>
                      </a>
                    </li>
					<li>
                      <a href="index.php?page=advertisement.php&option=view">
                        <span class="sub-item">Advertisement</span>
                      </a>
                    </li>
          <li>
                      <a href="index.php?page=packagecategory.php&option=view">
                        <span class="sub-item">Package catoegory</span>
                      </a>
                    </li>
					<li>
                      <a href="index.php?page=package.php&option=view">
                        <span class="sub-item">package</span>
                      </a>
                    </li>
					<li>
                      <a href="index.php?page=product.php&option=view">
                        <span class="sub-item">Product</span>
                      </a>
                    </li>
					<li>
                      <a href="index.php?page=staffleave.php&option=view">
                        <span class="sub-item">Staff leave</span>
                      </a>
                    </li>
				 </ul>
                </div>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#sidebarLayouts">
                  <i class="fas fa-cart-arrow-down"></i>
                  <p>Purchase</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="sidebarLayouts">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="index.php?page=supplier.php&option=view">
                        <span class="sub-item">Supplier</span>
                      </a>
                    </li>
                    <li>
                      <a href="index.php?page=purchase.php&option=view">
                        <span class="sub-item">purchase</span>
                      </a>
                    </li>
                    <li>
                      <a href="index.php?page=stock.php&option=view">
                        <span class="sub-item">stock</span>
                      </a>
                    </li>
                    <li>
                      <a href="index.php?page=supplytoservice.php&option=view">
                        <span class="sub-item">supply to service </span>
                      </a>
                    </li>
                  
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#forms">
                  <i class="fas fa-pen-square"></i>
                  <p>Booking</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="forms">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="index.php?page=specialtime.php&option=view">
                        <span class="sub-item">Shop open at special days</span>
                      </a>
                    </li>
                    <li>
                      <a href="index.php?page=customer.php&option=view">
                        <span class="sub-item">Customer</span>
                      </a>
                    </li>
                    <li>
                      <a href="index.php?page=booking.php&option=view">
                        <span class="sub-item">Booking</span>
                      </a>
                    </li>
                    <li>
                      <a href="index.php?page=bookingsales.php&option=view">
                        <span class="sub-item">Sale Product</span>
                      </a>
                    </li>
                  
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#tables">
                  <i class="fas fa-bell"></i>
                  <p>Notification (<?php echo $total_notification; ?>)</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="tables">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="index.php?page=notification_new_booking.php">
                        <span class="sub-item">New Bookings (<?php echo $newbooking_notification; ?>)</span>
                      </a>
                    </li> 
                    <li>
                      <a href="index.php?page=notification_pending_payment.php">
                        <span class="sub-item">Pending Payments (<?php echo $pending_payments_notification; ?>)</span>
                      </a>
                    </li> 
                    <li>
                      <a href="index.php?page=notification_upcoming_booking.php">
                        <span class="sub-item">Upcoming Bookings(<?php echo $upcoming_booking_notification; ?>)</span>
                      </a>
                    </li> 
                    <li>
                      <a href="index.php?page=notification_finished_booking.php">
                        <span class="sub-item">Finished Bookings(<?php echo $finished_booking_notification; ?>)</span>
                      </a>
                    </li> 
                     <li>
                      <a href="index.php?page=notification_upcoming_leave.php">
                        <span class="sub-item">Upcomming Leaves(<?php echo $upcoming_leave_notification; ?>)</span>
                      </a>
                    </li> 
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#maps">
                  <i class="fas fa-file"></i>
                  <p>Report</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="maps">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="index.php?page=report.php&option=booking_details">
                        <span class="sub-item">Booking Report</span>
                      </a>
                    </li>
                    <li>
                      <a href="index.php?page=report.php&option=purchase_details">
                        <span class="sub-item">Purchase Report</span>
                      </a>
                    </li>
                    <li>
                      <a href="index.php?page=report.php&option=staff_leave_details">
                        <span class="sub-item">Staff Leave Report</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#charts">
                  <i class="far fa-user-circle"></i>
                  <p>Profile</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="charts">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="index.php?page=message.php&option=view">
                        <span class="sub-item">message</span>
                      </a>
                    </li>
                    <li>
                      <a href="index.php?page=profile.php">
                        <span class="sub-item">Profile</span>
                      </a>
                    </li>
                    <li>
                      <a href="index.php?page=changepassword.php">
                        <span class="sub-item">Change password</span>
                      </a>
                    </li>
					
                  
                  </ul>
                </div>
              </li>
              
            </ul>