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

//insert code start
if(isset($_POST["btnsave"]))
{
	$sql_insert="INSERT INTO bookingallocatestaff(booking_id,package_id,staff_id)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtbookingid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtpackageid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtstaffid"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
	if($result_insert)
	{
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=bookingallocatestaff.php&option=add";</script>';
	}
}
//insert code end
?>
<body>
<?php
if(isset($_GET["option"]))
{
	if($_GET["option"]=="add")
	{
		//add form
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form  allocate staff  for booking </div>
					</div>
					<div class="card-body">
						<div class="row">
							<!-- form start -->
							<form method="POST" action="">
								
								<!-- one row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtbookingid">Booking ID</label>
											<select class="form-control" name="txtbookingid" id="txtbookingid" required placeholder="Booking ID" >
												<option value="select">Select Booking </option>
												<?php
												$sql_load_booking="SELECT booking_id FROM booking ";
												$result_load_booking=mysqli_query($con,$sql_load_booking) or die("sql error in sql_load_booking".mysqli_error($con));
												while($row_load_booking=mysqli_fetch_assoc($result_load_booking))
												{
													echo'<option value="'.$row_load_booking["booking_id"].'">'.$row_load_booking["booking_id"].'</option>';
												}
												?>
											</select>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtpackageid">Package</label>
											<select class="form-control" name="txtpackageid" id="txtpackageid" required placeholder="Package ID" >
												<option value="select">Select Package </option>
													<?php
													$sql_load_package="SELECT package_id, name FROM package ";
													$result_load_package=mysqli_query($con,$sql_load_package) or die("sql error in sql_load_package".mysqli_error($con));
													while($row_load_package=mysqli_fetch_assoc($result_load_package))
													{
														echo'<option value="'.$row_load_package["package_id"].'">'.$row_load_package["name"].'</option>';
													}
													?>
											</select>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- one row end -->
								
								<!-- second row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtstaffid">Staff</label>
											<select class="form-control" name="txtstaffid" id="txtstaffid" required placeholder="Staff" >
												<option value="select">Select staff </option>
												<?php
												$sql_load="SELECT staff_id, name FROM staff ";
												$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
												while($row_load=mysqli_fetch_assoc($result_load))
												{
													echo'<option value="'.$row_load["staff_id"].'">'.$row_load["name"].'</option>';
												}
												?>
											</select>
										</div>
										<!-- column one end -->
									</div>
								</div>
								<!-- second row end -->
								
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=bookingallocatestaff.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
												<input type="reset" class="btn btn-danger" name="btnclear" id="btnclear"  value="Clear"/>
												<input type="submit" class="btn btn-success" name="btnsave" id="btnsave"  value="Save"/>
											</center>
										</div>
									</div>
								</div>
								<!-- button end -->
								
							</form>
							<!-- form end -->
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	else if($_GET["option"]=="view")
	{
		//view table
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">booking allocate staff Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=bookingallocatestaff.php&option=add"><button class="btn btn-primary">Add allocate staff for booking</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>Booking ID</th>
										<th>Package</th>
										<th>Staff</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql_view="SELECT booking_id,package_id,staff_id FROM bookingallocatestaff";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{	$sql_package="SELECT name from package WHERE package_id='$row_view[package_id]'";
										$result_package=mysqli_query($con,$sql_package) or die("sql error in sql_package ".mysqli_error($con));
										$row_package=mysqli_fetch_assoc($result_package);
										
										$sql_staff="SELECT name from staff WHERE staff_id='$row_view[staff_id]'";
										$result_staff=mysqli_query($con,$sql_staff) or die("sql error in sql_staff ".mysqli_error($con));
										$row_staff=mysqli_fetch_assoc($result_staff);
										
										
										echo '<tr>';
											echo '<td>'.$row_view["booking_id"].'</td>';
											echo '<td>'.$row_package["name"].'</td>';
											echo '<td>'.$row_staff["name"].'</td>';
											echo '<td>';
												echo '<a onclick="return delete_confirm()" href="index.php?page=bookingallocatestaff.php&option=delete&pk_booking_id='.$row_view["booking_id"].'&pk_package_id='.$row_view["package_id"].'&pk_staff_id='.$row_view["staff_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
											echo '</td>';
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
	else if($_GET["option"]=="fullview")
	{
		//NO fullview table
		// only 3 attributes and all of them are compostite key 
	}
	else if($_GET["option"]=="edit")
	{
		// no edit form
		// only 3 attributes and all of them are compostite key 
	}
	else if($_GET["option"]=="delete")
	{
		//delete code
		$get_pk_booking_id=$_GET["pk_booking_id"];
		$get_pk_package_id=$_GET["pk_package_id"];
		$get_pk_staff_id=$_GET["pk_staff_id"];
		
		$sql_delete=" DELETE FROM bookingallocatestaff WHERE booking_id='$get_pk_booking_id' AND package_id='$get_pk_package_id' AND staff_id='$get_pk_staff_id'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete)
		{
			echo '<script>alert("Successfully Deleted");
				  window.location.href="index.php?page=bookingallocatestaff.php&option=view";</script>';
		}
	}
}
?>
</body>