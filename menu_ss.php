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
$sql_upcoming_bookings="SELECT booking_id,bookdate,customer_id,totalamount,servicedate FROM booking WHERE status='Accept' AND servicedate >='$today' AND  booking_id IN(SELECT DISTINCT booking_id FROM bookingallocatestaff WHERE staff_id='$system_user_id') ORDER BY booking_id DESC";
$result_upcoming_bookings=mysqli_query($con,$sql_upcoming_bookings) or die("sql error in sql_upcoming_bookings ".mysqli_error($con));
$upcoming_booking_notification=mysqli_num_rows($result_upcoming_bookings);                 

$total_notification+=$upcoming_booking_notification;

// Fetch Upcomming leave notifications
$today = date("Y-m-d");
$sql_upcoming_leave="SELECT leave_id,staff_id,startdate,enddate,status FROM staffleave WHERE staff_id='$system_user_id' AND status='Approved' AND startdate>='$today' ORDER BY startdate DESC";
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
					<!-- <li>
                      <a href="index.php?page=package.php&option=view">
                        <span class="sub-item">package</span>
                      </a>
                    </li>
					<li>
                      <a href="index.php?page=product.php&option=view">
                        <span class="sub-item">Product</span>
                      </a>
                    </li> -->
					<li>
                      <a href="index.php?page=staffleave.php&option=view">
                        <span class="sub-item">Staff leave</span>
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
                    <!--<li>
                      <a href="index.php?page=specialtime.php&option=view">
                        <span class="sub-item">Shop open at special days</span>
                      </a>
                    </li>-->
                    <li>
                      <a href="index.php?page=booking.php&option=view">
                        <span class="sub-item">Booking</span>
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
                      <a href="index.php?page=notification_upcoming_booking.php">
                        <span class="sub-item">Upcoming Bookings (<?php echo $upcoming_booking_notification; ?>)</span>
                      </a>
                    </li>
                     <li>
                      <a href="index.php?page=notification_upcoming_leave.php">
                        <span class="sub-item">Upcomming Leaves (<?php echo $upcoming_leave_notification; ?>)</span>
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