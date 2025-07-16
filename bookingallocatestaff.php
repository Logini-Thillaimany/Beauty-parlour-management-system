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
	$totalLoop=$_POST["txtloop"];
	$submit=0;
	for($x=1;$x<$totalLoop;$x++)
	{
		if(isset($_POST["txtpackageid_".$x]))
		{
			$sql_insert="INSERT INTO bookingallocatestaff(booking_id,package_id,staff_id)
									VALUES('".mysqli_real_escape_string($con,$_POST["txtbookingid"])."',
											'".mysqli_real_escape_string($con,$_POST["txtpackageid_".$x])."',
											'".mysqli_real_escape_string($con,$_POST["txtstaffid_".$x])."')";
			$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
			$submit++;
		}
	}
	if($submit>0)
	{
		echo '<script>alert("Successfully Insert");
				window.location.href="index.php?page=booking.php&option=fullview&pk_booking_id='.$_POST["txtbookingid"].'";</script>';
	}
}
//insert code end
?>
<body>
<script>
	function activeStaff(y)
	{
		//var totalLoop=document.getElementById("txtloop").value;
			if(document.getElementById("txtpackageid_"+y).checked==true)
			{
				document.getElementById("txtstaffid_"+y).required=true;
			}
			else
			{
				document.getElementById("txtstaffid_"+y).required=false;
			}
	}
</script>
<script>
	function check_checkbox()
	{
		var totalLoop=document.getElementById("txtloop").value;
		for(var x=1;x<totalLoop; x++)
		{
			if(document.getElementById("txtpackageid_"+x).checked==true)
			{
				return true;
			}
		}
		alert("Please select Atleat one package with allocating staff !");
		return false;
	}
</script>
<?php
if(isset($_GET["option"]))
{
	if($_GET["option"]=="add")
	{
		//add form
		$get_pk_booking_id=$_GET["pk_booking_id"];

		$sql_fullview="SELECT booktype,servicedate FROM booking WHERE booking_id='$get_pk_booking_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
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
							<form method="POST" action="" onsubmit="return check_checkbox()">
								
								<!-- one row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-3">
										</div>
										<div class="col-md-6 col-lg-6">									
											<label for="txtbookingid">Booking ID</label>
											<input type="text" name="txtbookingid" id="txtbookingid" required placeholder="Booking ID" value="<?php  echo $get_pk_booking_id; ?>" class="form-control" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-3 ">
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- one row end -->
								 
								<!-- second row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-3">
										</div>
										<div class="col-md-6 col-lg-6">									
											<table  class="table">
											<?php 
											$sql_view="SELECT * FROM bookingpackage WHERE booking_id='$get_pk_booking_id'";
											$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
											$x=0;
											$y=1;
											while($row_view=mysqli_fetch_assoc($result_view))
											{
												$sql_check="SELECT booking_id,package_id,staff_id FROM bookingallocatestaff WHERE booking_id='$get_pk_booking_id' AND package_id='$row_view[package_id]'";
												$result_check=mysqli_query($con,$sql_check) or die("sql error in sql_check ".mysqli_error($con));
												if(mysqli_num_rows($result_check)==0)
												{
													$sql_package="SELECT name from package WHERE package_id='$row_view[package_id]'";
													$result_package=mysqli_query($con,$sql_package) or die("sql error in sql_package ".mysqli_error($con));
													$row_package=mysqli_fetch_assoc($result_package);


													echo '<tr>';
													echo '<td>';
														echo '<input type="checkbox" name="txtpackageid_'.$y.'" id="txtpackageid_'.$y.'" onClick="activeStaff('.$y.')" value="'.$row_view["package_id"].'"> '.$row_package["name"];
													echo '</td>';
													echo '<td>';

												
														echo  '<select class="form-control" name="txtstaffid_'.$y.'" id="txtstaffid_'.$y.'"  placeholder="Staff" >';
														echo '<option value="" disabled selected>Select staff </option>';
															if($row_fullview["booktype"]=="CA01")
															{
																$designation_staff="MakeupArtist";
															}
															else if($row_fullview["booktype"]=="CA02")
															{
																$designation_staff="SaloonService";
															}
															$sql_load="SELECT staff_id, name FROM staff WHERE designation='$designation_staff' AND staff_id  IN (SELECT user_id FROM login WHERE status='Active')";
															$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
															while($row_load=mysqli_fetch_assoc($result_load))
															{
																$allowed_staff=0; //default value
																$sql_check_leave="SELECT * FROM staffleave WHERE staff_id='$row_load[staff_id]' AND startdate<='$row_fullview[servicedate]' AND enddate>='$row_fullview[servicedate]' AND status='Approved'";
																$result_check_leave=mysqli_query($con,$sql_check_leave) or die("sql error in sql_check_leave ".mysqli_error($con));
																if(mysqli_num_rows($result_check_leave)==0)
																{
																	//if staff is not on leave
																	$sql_check_other_allocation="SELECT * FROM bookingpackage WHERE booking_id IN (SELECT booking_id FROM booking WHERE servicedate='$row_fullview[servicedate]' AND booking_id!='$get_pk_booking_id')";
																	$result_check_other_allocation=mysqli_query($con,$sql_check_other_allocation) or die("sql error in sql_check_other_allocation ".mysqli_error($con));
																	if(mysqli_num_rows($result_check_other_allocation)==0)
																	{
																		//if staff is not allocated for other booking on that date
																		$allowed_staff=1; //allow to select this staff
																	}
																	else
																	{
																		$any_possile=0;
																		while($row_check_other_allocation=mysqli_fetch_assoc($result_check_other_allocation))
																		{
																			if(($row_view["starttime"]<$row_check_other_allocation["starttime"] && $row_view["endtime"]<$row_check_other_allocation["starttime"]) || ($row_view["starttime"]>$row_check_other_allocation["endtime"] && $row_view["endtime"]>$row_check_other_allocation["endtime"]))
																			{

																			}
																			else
																			{
																				$sql_check_staff_allocation="SELECT * FROM bookingallocatestaff WHERE staff_id='$row_load[staff_id]' AND booking_id='$row_check_other_allocation[booking_id]' AND package_id='$row_check_other_allocation[package_id]'";
																				$result_check_staff_allocation=mysqli_query($con,$sql_check_staff_allocation) or die("sql error in sql_check_staff_allocation ".mysqli_error($con));
																				if(mysqli_num_rows($result_check_staff_allocation)>0)
																				{
																					$any_possile++;
																				}
																			}
																		}
																		if($any_possile==0)
																		{
																			$allowed_staff=1; //allow to select this staff
																		}
																	}
																	
																}
																else
																{
																	$row_check_leave=mysqli_fetch_assoc($result_check_leave);
																	if(($row_check_leave["stardate"]==$row_fullview["servicedate"] && $row_check_leave["starttime"]>=$row_view["endtime"]) || $row_check_leave["enddate"]==$row_fullview["servicedate"] && $row_check_leave["endtime"]<=$row_view["starttime"])
																	{
																		$sql_check_other_allocation="SELECT * FROM bookingpackage WHERE booking_id IN (SELECT booking_id FROM booking WHERE servicedate='$row_fullview[servicedate]' AND booking_id!='$get_pk_booking_id')";
																		$result_check_other_allocation=mysqli_query($con,$sql_check_other_allocation) or die("sql error in sql_check_other_allocation ".mysqli_error($con));
																		if(mysqli_num_rows($result_check_other_allocation)==0)
																		{
																			//if staff is not allocated for other booking on that date
																			$allowed_staff=1; //allow to select this staff
																		}
																		else
																		{
																			$any_possile=0;
																			while($row_check_other_allocation=mysqli_fetch_assoc($result_check_other_allocation))
																			{
																				if(($row_view["starttime"]<$row_check_other_allocation["starttime"] && $row_view["endtime"]<$row_check_other_allocation["starttime"]) || ($row_view["starttime"]>$row_check_other_allocation["endtime"] && $row_view["endtime"]>$row_check_other_allocation["endtime"]))
																				{

																				}
																				else
																				{
																					$sql_check_staff_allocation="SELECT * FROM bookingallocatestaff WHERE staff_id='$row_load[staff_id]' AND booking_id='$row_check_other_allocation[booking_id]' AND package_id='$row_check_other_allocation[package_id]'";
																					$result_check_staff_allocation=mysqli_query($con,$sql_check_staff_allocation) or die("sql error in sql_check_staff_allocation ".mysqli_error($con));
																					if(mysqli_num_rows($result_check_staff_allocation)>0)
																					{
																						$any_possile++;
																					}
																				}
																			}
																			if($any_possile==0)
																			{
																				$allowed_staff=1; //allow to select this staff
																			}
																		}
																	}
																}
																if($allowed_staff==1)
																{
																	echo'<option value="'.$row_load["staff_id"].'">'.$row_load["name"].'</option>';
																}
															}
														echo '</select>';
													echo '</td>';
													$x++;
													$y++;
													echo'</tr>';
												}
											}
											echo '<input type="hidden" name="txtloop" id="txtloop" value="'.$y.'">';
											if($y>1)
											{
												$submit_disabled=""; //enable submit button
											}
											else
											{
												$submit_disabled="disabled"; //disable submit button
											}
											?>
											</table>
										</div>
										<div class="col-md-3">
										</div>
										<!-- column end -->
									</div>
								</div>
								<!-- second row end -->
								
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=booking.php&option=fullview&pk_booking_id=<?php echo $get_pk_booking_id; ?>"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
												<input type="reset" class="btn btn-danger" name="btnclear" id="btnclear"  value="Clear"/>
												<input type="submit" <?php echo $submit_disabled; ?> class="btn btn-success" name="btnsave" id="btnsave"  value="Save"/>
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
				  window.location.href="index.php?page=booking.php&option=fullview&pk_booking_id='.$get_pk_booking_id.'";</script>';
		}
	}
}
?>
</body>