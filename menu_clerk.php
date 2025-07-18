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
$sql_upcoming_bookings="SELECT booking_id,bookdate,customer_id,totalamount,servicedate FROM booking WHERE status='Accept'AND servicedate >='$today' ORDER BY booking_id DESC";
$result_upcoming_bookings=mysqli_query($con,$sql_upcoming_bookings) or die("sql error in sql_upcoming_bookings ".mysqli_error($con));
$upcoming_booking_notification=mysqli_num_rows($result_upcoming_bookings);                 

$total_notification+=$upcoming_booking_notification;

// Fetch finished bookings notifications
$finished_booking_notification=0;
$sql_finished_bookings="SELECT booking_id,servicedate FROM booking WHERE status='Accept' AND servicedate <='$today'  ORDER BY booking_id DESC";
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

// Fetch balance payment notifications
$today = date("Y-m-d");
$balance_payment_notification=0;

// Fetch balance payments based on user type 
$sql_balance_payment_booking="SELECT booking_id,bookdate,customer_id,totalamount,servicedate FROM booking WHERE ( status='Accept' OR status='Finish' ) ORDER BY booking_id DESC";
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

// Fetch upcoming leave notifications
$today = date("Y-m-d");
$sql_upcoming_leave="SELECT leave_id,staff_id,startdate,enddate,status FROM staffleave WHERE status='Approved' AND startdate>='$today' ORDER BY startdate DESC";
$result_upcoming_leave=mysqli_query($con,$sql_upcoming_leave) or die("sql error in sql_upcoming_leave ".mysqli_error($con));
$upcoming_leave_notification=mysqli_num_rows($result_upcoming_leave);

$total_notification+=$upcoming_leave_notification;

//low stock notification
$low_stock_notification=0;
$sql_view="SELECT product_id,name,minimumstock,expiretype FROM product WHERE expiretype='No'";
$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
while($row_view=mysqli_fetch_assoc($result_view))
{
  $purchasebutton=0;
  
  $sql_stock="SELECT quantity from stockne WHERE product_id='$row_view[product_id]'";
  $result_stock=mysqli_query($con,$sql_stock) or die("sql error in sql_stock ".mysqli_error($con));
  if(mysqli_num_rows($result_stock)>0)
  {
    $row_stock=mysqli_fetch_assoc($result_stock);
    $sql_checkprice="SELECT startdate from productprice WHERE product_id='$row_view[product_id]' AND enddate IS NULL";
    $result_checkprice=mysqli_query($con,$sql_checkprice) or die("sql error in sql_checkprice ".mysqli_error($con));
    if(mysqli_num_rows($result_checkprice)>0)
    {
      if($row_view["minimumstock"]>=$row_stock["quantity"])
      {
        $purchasebutton=1;
      }
    }											
  }
  else
  {
    $sql_checkprice="SELECT startdate from productprice WHERE product_id='$row_view[product_id]' AND enddate IS NULL";
    $result_checkprice=mysqli_query($con,$sql_checkprice) or die("sql error in sql_checkprice ".mysqli_error($con));
    if(mysqli_num_rows($result_checkprice)>0)
    {
      
      $purchasebutton=1;
    }
  }
  if($purchasebutton==1)
  {
    $low_stock_notification++;
  }
}
$sql_view="SELECT product_id,name,minimumstock,expiretype FROM product WHERE expiretype='Yes'";
$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
while($row_view=mysqli_fetch_assoc($result_view))
{
  $purchasebutton=0;
  $priceAvailable=0;
  $product_variable=' <font color="red">(Product not in use)</font>';

  $sql_checkprice="SELECT startdate from productprice WHERE product_id='$row_view[product_id]' AND enddate IS NULL";
  $result_checkprice=mysqli_query($con,$sql_checkprice) or die("sql error in sql_checkprice ".mysqli_error($con));
  if(mysqli_num_rows($result_checkprice)>0)
  {
    $priceAvailable=1;
    $product_variable="";
  }

  $sql_stock="SELECT * from stock WHERE product_id='$row_view[product_id]' AND quantity>0";
  $result_stock=mysqli_query($con,$sql_stock) or die("sql error in sql_stock ".mysqli_error($con));
  if(mysqli_num_rows($result_stock)>0)
  {
    $noTime=mysqli_num_rows($result_stock);
    $y=1;
    $total_quantity=0;
    while($row_stock=mysqli_fetch_assoc($result_stock))
    {
      $today=date("Y-m-d");
      $sql_expire="SELECT expiredate from purchaseproduct WHERE purchase_id='$row_stock[purchase_id]' AND product_id='$row_view[product_id]' AND expiredate>'$today'";
      $result_expire=mysqli_query($con,$sql_expire) or die("sql error in sql_expire ".mysqli_error($con));
      if(mysqli_num_rows($result_expire)>0)
      {
        $total_quantity=$total_quantity+$row_stock["quantity"];
        $this_quantity=$row_stock["quantity"];
      }
      else
      {
        $this_quantity=$row_stock["quantity"].' <font color="red">Expired</font>';
      }
    }	
    if($row_view["minimumstock"]>=$total_quantity)
    {
      $low_stock_notification++;
    }
    
  }
  else
  {
    $low_stock_notification++;
  }
  }
$total_notification+=$low_stock_notification;

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
                      <a href="index.php?page=stockne.php&option=view">
                        <span class="sub-item">Non Expired stock</span>
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
                    <!--<li>
                      <a href="index.php?page=specialtime.php&option=view">
                        <span class="sub-item">Shop open at special days</span>
                      </a>
                    </li>-->
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
                        <span class="sub-item">Upcoming Bookings (<?php echo $upcoming_booking_notification; ?>)</span>
                      </a>
                    </li> 
                    <li>
                      <a href="index.php?page=notification_finished_booking.php">
                        <span class="sub-item">Finished Bookings (<?php echo $finished_booking_notification; ?>)</span>
                      </a>
                    </li> 
                    <li>
                      <a href="index.php?page=notification_balance_payment.php">
                        <span class="sub-item">Incomplete Payment (<?php echo $balance_payment_notification; ?>)</span>
                      </a>
                    </li> 
                     <li>
                      <a href="index.php?page=notification_upcoming_leave.php">
                        <span class="sub-item">Upcomming Leaves (<?php echo $upcoming_leave_notification; ?>)</span>
                      </a>
                    </li> 
                    <li>
                      <a href="index.php?page=notification_low_stock.php">
                        <span class="sub-item">Low Stock (<?php echo $low_stock_notification; ?>)</span>
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