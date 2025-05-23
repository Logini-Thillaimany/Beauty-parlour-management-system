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
if($system_usertype!="Guest")
{
	include("connection.php");
	
	if($system_usertype=="Customer")//Customer profile
	{
		$get_pk_customer_id=$system_user_id;
		
		$sql_fullview="SELECT * FROM customer WHERE customer_id='$get_pk_customer_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Customer Profile</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="display table table-striped table-hover">
								<tr><th>customer ID</th><td><?php echo $row_fullview["customer_id"]; ?></td></tr>
								<tr><th>Name</th><td><?php echo $row_fullview["name"]; ?></td></tr>
								<tr><th>E-mail</th><td><?php echo $row_fullview["email"]; ?></td></tr>
								<tr><th>Date of birth</th><td><?php echo $row_fullview["dob"]; ?></td></tr>
								<tr><th>Address</th><td><?php echo $row_fullview["address"]; ?></td></tr>
								<tr><th>Mobile</th><td>0<?php echo $row_fullview["mobile"]; ?></td></tr>
								<tr><th>Comments</th><td><?php echo $row_fullview["comments"]; ?></td></tr>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php"><button class="btn btn-primary">Go Back</button></a> 
											<a href="index.php?page=profile_edit.php"><button class="btn btn-info">Edit</button></a> 
										</center>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	else//staff profile
	{
		$get_pk_staff_id=$system_user_id;
		
		$sql_fullview="SELECT * FROM staff WHERE staff_id='$get_pk_staff_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Staff Profile</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="display table table-striped table-hover">
								<tr><th>Staff ID</th><td><?php echo $row_fullview["staff_id"]; ?></td></tr>
								<tr><th>Name</th><td><?php echo $row_fullview["name"]; ?></td></tr>
								<tr><th>NIC</th><td><?php echo $row_fullview["nic"]; ?></td></tr>
								<tr><th>Date of birth</th><td><?php echo $row_fullview["dob"]; ?></td></tr>
								<tr><th>Gender</th><td><?php echo $row_fullview["gender"]; ?></td></tr>
								<tr><th>Designation</th><td><?php echo $row_fullview["designation"]; ?></td></tr>
								<tr><th>Address</th><td><?php echo $row_fullview["address"]; ?></td></tr>
								<tr><th>Mobile</th><td>0<?php echo $row_fullview["mobile"]; ?></td></tr>
								<tr><th>Joint Date</th><td><?php echo $row_fullview["jointdate"]; ?></td></tr>
								<tr>								
									<td colspan="2">
										<center>
											<a href="index.php"><button class="btn btn-primary">Go Back</button></a> 
											<a href="index.php?page=profile_edit.php"><button class="btn btn-info">Edit</button></a> 
										</center>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
else
{
	echo '<script> window.location.href="index.php";</script>';
}
?>