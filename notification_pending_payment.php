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
if($system_usertype=="Admin" || $system_usertype=="Clerk")//Notify New bookings to admin and clerk
{
include("connection.php");
?>
<body>
    <div class="card-body">
        <div class="table-responsive">
            <table id="basic-datatables" class="display table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Booking ID</th>
                        <th>Pay date</th>
                        <th>Pay amount</th>
                        <th>status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql_view="SELECT payment_id,booking_id,paydate,payamount,paystatus FROM payment WHERE paystatus='Pending' ORDER BY payment_id DESC";
                    $result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
                    while($row_view=mysqli_fetch_assoc($result_view))
                    {
                        echo '<tr>';
                            echo '<td>'.$row_view["payment_id"].'</td>';
                            echo '<td>'.$row_view["booking_id"].'</td>';
                            echo '<td>'.$row_view["paydate"].'</td>';
                            echo '<td>'.$row_view["payamount"].'</td>';
                            echo '<td>'.$row_view["paystatus"].'</td>';
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