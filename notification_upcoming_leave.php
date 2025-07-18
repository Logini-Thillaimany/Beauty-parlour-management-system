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
if($system_usertype=="Admin" || $system_usertype=="Clerk" || $system_usertype=="MakeupArtist" || $system_usertype=="SaloonService")//Notify New bookings to admin and clerk
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
                        <th>Leave ID</th>
                        <th>Staff</th>
                        <th>Startdate</th>
                        <th>Enddate</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $X=1;
                    $today = date("Y-m-d");
                    if( $system_usertype=="MakeupArtist" || $system_usertype=="SaloonService")
                    {
                        $sql_view="SELECT leave_id,staff_id,startdate,enddate,status FROM staffleave WHERE staff_id='$system_user_id' AND status='Approved' AND startdate>='$today' ORDER BY startdate DESC";
                    }
                    else 
                    {
                        $sql_view="SELECT leave_id,staff_id,startdate,enddate,status FROM staffleave WHERE status='Approved' AND startdate>='$today' ORDER BY startdate DESC";
                    }
                    $result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
                    while($row_view=mysqli_fetch_assoc($result_view))
                    {	
                        $sql_staff="SELECT name from staff WHERE staff_id='$row_view[staff_id]'";
                        $result_staff=mysqli_query($con,$sql_staff) or die("sql error in sql_staff ".mysqli_error($con));
                        $row_staff=mysqli_fetch_assoc($result_staff);
                        
                        echo '<tr>';
                            echo '<td>'.$X++.'</td>';
                            echo '<td>'.$row_view["leave_id"].'</td>';
                            echo '<td>'.$row_staff["name"].'</td>';
                            echo '<td>'.$row_view["startdate"].'</td>';
                            echo '<td>'.$row_view["enddate"].'</td>';
                            echo '<td>'.$row_view["status"].'</td>';
                            echo '<td>';
                                echo '<a href="index.php?page=staffleave.php&option=fullview&pk_leave_id='.$row_view["leave_id"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
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