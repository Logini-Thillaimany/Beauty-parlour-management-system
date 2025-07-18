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
if(isset($_GET["option"]))
{
    if($_GET["option"]=="booking_details")
    {
        $get_ajax_startdate = $_GET["ajax_startdate"];
        $get_ajax_enddate = $_GET["ajax_enddate"];
      //view table
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Booking Details Report</h4>
					</div>
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
										
									</tr>
								</thead>
								<tbody>
									<?php
									$x=1;
									
									$sql_view_booking="SELECT booking_id,bookdate,customer_id,totalamount,servicedate FROM booking WHERE bookdate>='$get_ajax_startdate' AND bookdate<='$get_ajax_enddate' ORDER BY booking_id DESC";
									$result_view_booking=mysqli_query($con,$sql_view_booking) or die("sql error in sql_view_booking ".mysqli_error($con));
									while($row_view_booking=mysqli_fetch_assoc($result_view_booking))
									{	
										$sql_customer="SELECT name from customer WHERE customer_id='$row_view_booking[customer_id]'";
										$result_customer=mysqli_query($con,$sql_customer) or die("sql error in sql_customer ".mysqli_error($con));
										$row_customer=mysqli_fetch_assoc($result_customer);
										
										echo '<tr>';
											echo '<td>'.$x++.'</td>';
											echo '<td>'.$row_view_booking["booking_id"].'</td>';
											echo '<td>'.$row_view_booking["bookdate"].'</td>';
											echo '<td>'.$row_customer["name"].'</td>';
											echo '<td>'.$row_view_booking["totalamount"].'</td>';
											echo '<td>'.$row_view_booking["servicedate"].'</td>';
											
										echo '</tr>';
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
    }
	if($_GET["option"]=="purchase_details")
    {
        $get_ajax_startdate = $_GET["ajax_startdate"];
        $get_ajax_enddate = $_GET["ajax_enddate"];
      //view table
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Purchase Details Report</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>Purchase ID</th>
										<th>Supplier ID</th>
										<th>Purchase Date</th>
										<th>Total amount</th>
										<th>Pay status</th>
										
									</tr>
								</thead>
								<tbody>
									<?php
									$x=1;
									
									$sql_view_purchase="SELECT purchase_id,supplier_id,date,totalamount,paystatus FROM purchase WHERE date>='$get_ajax_startdate' AND date<='$get_ajax_enddate' ORDER BY date DESC";
									$result_view_purchase=mysqli_query($con,$sql_view_purchase) or die("sql error in sql_view_purchase ".mysqli_error($con));
									while($row_view_purchase=mysqli_fetch_assoc($result_view_purchase))
									{	
										$sql_supplier="SELECT name from supplier WHERE supplier_id='$row_view_purchase[supplier_id]'";
										$result_supplier=mysqli_query($con,$sql_supplier) or die("sql error in sql_supplier ".mysqli_error($con));
										$row_supplier=mysqli_fetch_assoc($result_supplier);
										
										echo '<tr>';
											echo '<td>'.$x++.'</td>';
											echo '<td>'.$row_view_purchase["purchase_id"].'</td>';
											echo '<td>'.$row_supplier["name"].'</td>';
											echo '<td>'.$row_view_purchase["date"].'</td>';
											echo '<td>'.$row_view_purchase["totalamount"].'</td>';
											echo '<td>'.$row_view_purchase["paystatus"].'</td>';
											
										echo '</tr>';
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
    }
	if($_GET["option"]=="staff_leave_details")
    {
        $get_ajax_startdate = $_GET["ajax_startdate"];
        $get_ajax_enddate = $_GET["ajax_enddate"];

      //view table
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Staff Leave Details Report</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>Leave ID</th>
										<th>Staff Name</th>
										<th>Start Date</th>
										<th>End Date</th>
										<th>status</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$x=1;
									
									$sql_view_staff_leave="SELECT leave_id,staff_id,startdate,enddate,status FROM staffleave WHERE startdate>='$get_ajax_startdate' AND startdate<='$get_ajax_enddate'  ORDER BY startdate DESC";
									$result_view_staff_leave=mysqli_query($con,$sql_view_staff_leave) or die("sql error in sql_view_staff_leave ".mysqli_error($con));
									while($row_view_staff_leave=mysqli_fetch_assoc($result_view_staff_leave))
									{	
										$sql_staff="SELECT name from staff WHERE staff_id='$row_view_staff_leave[staff_id]'";
										$result_staff=mysqli_query($con,$sql_staff) or die("sql error in sql_staff ".mysqli_error($con));
										$row_staff=mysqli_fetch_assoc($result_staff);
										
										echo '<tr>';
											echo '<td>'.$x++.'</td>';
											echo '<td>'.$row_view_staff_leave["leave_id"].'</td>';
											echo '<td>'.$row_staff["name"].'</td>';
											echo '<td>'.$row_view_staff_leave["startdate"].'</td>';
											echo '<td>'.$row_view_staff_leave["enddate"].'</td>';
											echo '<td>'.$row_view_staff_leave["status"].'</td>';
											
										echo '</tr>';
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
    }
	if($_GET["option"]=="booking_package_details")
    {
        $get_ajax_startdate = $_GET["ajax_startdate"];
        $get_ajax_enddate = $_GET["ajax_enddate"];
        $get_ajax_packageid = $_GET["ajax_packageid"];
      //view table
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Booking Packages Report</h4>
					</div>
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
										
									</tr>
								</thead>
								<tbody>
									<?php
									$x=1;
									$count=0;
									$sql_view_booking="SELECT booking_id,bookdate,customer_id,totalamount,servicedate FROM booking WHERE bookdate>='$get_ajax_startdate' AND bookdate<='$get_ajax_enddate' ORDER BY booking_id DESC";
									$result_view_booking=mysqli_query($con,$sql_view_booking) or die("sql error in sql_view_booking ".mysqli_error($con));
									while($row_view_booking=mysqli_fetch_assoc($result_view_booking))
									{	
										if($get_ajax_packageid=='All Packages')
										{
											$sql_booking_package="SELECT booking_id,package_id FROM bookingpackage WHERE booking_id='$row_view_booking[booking_id]'";
										
										}
										else
										{
											$sql_booking_package="SELECT booking_id,package_id FROM bookingpackage WHERE booking_id='$row_view_booking[booking_id]'  AND package_id='$get_ajax_packageid'";
										}
																			
										$result_booking_package=mysqli_query($con,$sql_booking_package) or die("sql error in sql_booking_package ".mysqli_error($con));
										while($row_booking_package=mysqli_fetch_assoc($result_booking_package))
										{
											$sql_package="SELECT * FROM package WHERE package_id='$row_booking_package[package_id]'";
											$result_package=mysqli_query($con,$sql_package) or die("sql error in sql_package ".mysqli_error($con));
											$row_package=mysqli_fetch_assoc($result_package);
											
											$sql_customer="SELECT name from customer WHERE customer_id='$row_view_booking[customer_id]'";
											$result_customer=mysqli_query($con,$sql_customer) or die("sql error in sql_customer ".mysqli_error($con));
											$row_customer=mysqli_fetch_assoc($result_customer);
											
											echo '<tr>';
												echo '<td>'.$x++.'</td>';
												echo '<td>'.$row_view_booking["booking_id"].'</td>';
												echo '<td>'.$row_view_booking["bookdate"].'</td>';
												echo '<td>'.$row_customer["name"].'</td>';
												echo '<td>'.$row_view_booking["totalamount"].'</td>';
												echo '<td>'.$row_view_booking["servicedate"].'</td>';
											echo '</tr>';
											$count++;
										}
									}
											echo '<tr>';
												echo '<td>'.$x++.'</td>';
												echo '<td> Total Number of booking </td>';
												if($get_ajax_packageid=='All Packages')
												{
												echo '<td>All Packages</td>';
												}
												else
												{	
													echo '<td>'.$row_package["name"].'</td>';
												}
												echo '<td></td>';
												echo '<td></td>';
												echo '<td>'.$count.'</td>';
											echo '</tr>';
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Booking Packages Report</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>Package Name</th>
										<th>Count</th>
										
									</tr>
								</thead>
								<tbody>
									<?php
									$x=1;
									
									if($get_ajax_packageid=='All Packages')
									{
										$sql_package="SELECT * FROM package";
									}
									else
									{
										$sql_package="SELECT * FROM package WHERE package_id='$get_ajax_packageid'";
									}
									
									$result_package=mysqli_query($con,$sql_package) or die("sql error in sql_package ".mysqli_error($con));
									while($row_package=mysqli_fetch_assoc($result_package))
									{	
										$sql_booking_package="SELECT booking_id,package_id FROM bookingpackage WHERE package_id='$row_package[package_id]' AND booking_id IN (SELECT booking_id FROM booking WHERE bookdate>='$get_ajax_startdate' AND bookdate<='$get_ajax_enddate')";
										$result_booking_package=mysqli_query($con,$sql_booking_package) or die("sql error in sql_booking_package ".mysqli_error($con));
										
											
											echo '<tr>';
												echo '<td>'.$x++.'</td>';
												echo '<td>'.$row_package["name"].'</td>';
												echo '<td>'.mysqli_num_rows($result_booking_package).'</td>';
											echo '</tr>';
									}
											
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
    }
}
?>