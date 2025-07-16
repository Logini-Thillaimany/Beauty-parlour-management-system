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
if($system_usertype!="Guest")//Notify Upcoming bookings to all users except Guest
{
include("connection.php");
?>
<body>
    <div class="card-body">
        <div class="table-responsive">
            <table id="basic-datatables" class="display table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Booking ID</th>
                        <th>Book date</th>
                        <th>Customer </th>
                        <th>Total amount</th>
                        <th>Service date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $x=1;
                    $today = date("Y-m-d");
                    // Fetch upcoming bookings based on user type
                    if($system_usertype=="Customer")
                    {
                        $sql_view="SELECT booking_id,bookdate,customer_id,totalamount,servicedate FROM booking WHERE status='Accept' AND servicedate >'$today' AND customer_id='$system_user_id' ORDER BY booking_id DESC";
                    }
                    else if($system_usertype=="Admin" || $system_usertype=="Clerk")
                    {
                        $sql_view="SELECT booking_id,bookdate,customer_id,totalamount,servicedate FROM booking WHERE status='Accept'AND servicedate >'$today' ORDER BY booking_id DESC";
                    }
                    else
                    {
                        $sql_view="SELECT booking_id,bookdate,customer_id,totalamount,servicedate FROM booking WHERE status='Accept' AND servicedate >'$today' AND  booking_id IN(SELECT DISTINCT booking_id FROM bookingallocatestaff WHERE staff_id='$system_user_id') ORDER BY booking_id DESC";
                    }

                    $result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
                    while($row_view=mysqli_fetch_assoc($result_view))
                    {	
                        $sql_customer="SELECT name from customer WHERE customer_id='$row_view[customer_id]'";
                        $result_customer=mysqli_query($con,$sql_customer) or die("sql error in sql_customer ".mysqli_error($con));
                        $row_customer=mysqli_fetch_assoc($result_customer);
                        
                        echo '<tr>';
                            echo '<td>'.$x++.'</td>';
                            echo '<td>'.$row_view["booking_id"].'</td>';
                            echo '<td>'.$row_view["bookdate"].'</td>';
                            echo '<td>'.$row_customer["name"].'</td>';
                            echo '<td>'.$row_view["totalamount"].'</td>';
                            echo '<td>'.$row_view["servicedate"].'</td>';
                            echo '<td>';
                                echo '<a href="index.php?page=booking.php&option=fullview&pk_booking_id='.$row_view["booking_id"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
                            echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
<?php 
}
else{// other users redirect to index page
	echo '<script>window.location.href="index.php";</script>';
}
?>