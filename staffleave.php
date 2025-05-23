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
	$sql_insert="INSERT INTO staffleave(leave_id,staff_id,startdate,starttime,enddate,endtime,reason,applydate,status)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtleaveid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtstaffid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtstartdate"])."',
									'".mysqli_real_escape_string($con,$_POST["txtstarttime"])."',
									'".mysqli_real_escape_string($con,$_POST["txtenddate"])."',
									'".mysqli_real_escape_string($con,$_POST["txtendtime"])."',
									'".mysqli_real_escape_string($con,$_POST["txtreason"])."',
									'".mysqli_real_escape_string($con,$_POST["txtapplydate"])."',
									'".mysqli_real_escape_string($con,$_POST["txtstatus"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
	if($result_insert)
	{
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=staffleave.php&option=add";</script>';
	}
}
//insert code end

//update code start
if(isset($_POST["btnsavechanges"]))
{
	$sql_update="UPDATE staffleave SET
							staff_id='".mysqli_real_escape_string($con,$_POST["txtstaffid"])."',
							startdate='".mysqli_real_escape_string($con,$_POST["txtstartdate"])."',
							starttime='".mysqli_real_escape_string($con,$_POST["txtstarttime"])."',
							enddate='".mysqli_real_escape_string($con,$_POST["txtenddate"])."',
							endtime='".mysqli_real_escape_string($con,$_POST["txtendtime"])."',
							reason='".mysqli_real_escape_string($con,$_POST["txtreason"])."',
							applydate='".mysqli_real_escape_string($con,$_POST["txtapplydate"])."',
							status='".mysqli_real_escape_string($con,$_POST["txtstatus"])."'
							WHERE leave_id='".mysqli_real_escape_string($con,$_POST["txtleaveid"])."'";
	$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
	if($result_update)
	{
		echo '<script>alert("Successfully updated");
						window.location.href="index.php?page=staffleave.php&option=view";</script>';
	}
}
//update code end
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
						<div class="card-title"> Form for staff Apply leave</div>
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
											<label for="txtleaveid">leave ID</label>
											<?php
												$sql_generatedid="SELECT leave_id FROM staffleave ORDER BY leave_id DESC LIMIT 1";
												$result_generatedid=mysqli_query($con,$sql_generatedid) or die("sql error in sql_generatedid ".mysqli_error($con));
												if(mysqli_num_rows($result_generatedid)==1)
												{// for  except from the first submission
													$row_generatedid=mysqli_fetch_assoc($result_generatedid);
													$generatedid=++$row_generatedid["leave_id"];
												}
												else
												{//For first time submission
													$generatedid="L000000001";
												}
											?>
											<input type="text" class="form-control" name="txtleaveid" id="txtleaveid" required placeholder="Leave ID" value="<?php echo $generatedid;?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtstaffid">staff</label>
											<select class="form-control" name="txtstaffid" id="txtstaffid" required placeholder="Staff">
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
										<!-- column two end -->
									</div>
								</div>
								<!-- one row end -->
								
								<!-- second row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtstartdate">Start date</label>
											<input type="date" class="form-control" name="txtstartdate" id="txtstartdate" required placeholder="start date"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtstarttime">Start time</label>
											<input type="time" class="form-control" name="txtstarttime" id="txtstarttime" required placeholder="Start time"/>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- second row end -->
								
								<!-- third row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtenddate">End date</label>
											<input type="date" class="form-control" name="txtenddate" id="txtenddate" required placeholder="End date"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtendtime">End time</label>
											<input type="time" class="form-control" name="txtendtime" id="txtendtime" required placeholder="End time"/>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- second row end -->
								
								<!-- third row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtreason">Reason</label>
											<input type="text" class="form-control" name="txtreason" id="txtreason" required placeholder=" Reasons for Leave apply"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtapplydate">Apply date</label>
											<input type="date" class="form-control" name="txtapplydate" id="txtapplydate" required placeholder="Apply date"/>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- third row end -->
								
								<!-- fourth row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtstatus">status</label>
											<input type="text" class="form-control" name="txtstatus" id="txtstatus" required placeholder="Approved/deny"/>
										</div>
										<!-- column one end -->
									</div>
								</div>
								<!-- fourth row end -->
								
								
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=staffleave.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
						<h4 class="card-title">Staff Leave Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=staffleave.php&option=add"><button class="btn btn-primary">Add Staff Leave</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
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
									$sql_view="SELECT leave_id,staff_id,startdate,enddate,status FROM staffleave";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{	
										$sql_staff="SELECT name from staff WHERE staff_id='$row_view[staff_id]'";
										$result_staff=mysqli_query($con,$sql_staff) or die("sql error in sql_staff ".mysqli_error($con));
										$row_staff=mysqli_fetch_assoc($result_staff);
										
										echo '<tr>';
											echo '<td>'.$row_view["leave_id"].'</td>';
											echo '<td>'.$row_staff["name"].'</td>';
											echo '<td>'.$row_view["startdate"].'</td>';
											echo '<td>'.$row_view["enddate"].'</td>';
											echo '<td>'.$row_view["status"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=staffleave.php&option=fullview&pk_leave_id='.$row_view["leave_id"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
												echo '<a href="index.php?page=staffleave.php&option=edit&pk_leave_id='.$row_view["leave_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
												echo '<a onclick="return delete_confirm()" href="index.php?page=staffleave.php&option=delete&pk_leave_id='.$row_view["leave_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
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
		//fullview table
		$get_pk_leave_id=$_GET["pk_leave_id"];
		
		$sql_fullview="SELECT * FROM staffleave WHERE leave_id='$get_pk_leave_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		$sql_staff="SELECT name from staff WHERE staff_id='$row_fullview[staff_id]'";
		$result_staff=mysqli_query($con,$sql_staff) or die("sql error in sql_staff ".mysqli_error($con));
		$row_staff=mysqli_fetch_assoc($result_staff);
										
										
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Staff Leave Full Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=staffleave.php&option=add"><button class="btn btn-primary">Add Staff Leave</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<tr><th>Leave ID</th><td><?php echo $row_fullview["leave_id"]; ?></td></tr>
								<tr><th>Staff </th><td><?php echo $row_staff["name"]; ?></td></tr>
								<tr><th>Start Date</th><td><?php echo $row_fullview["startdate"]; ?></td></tr>
								<tr><th>End Date</th><td><?php echo $row_fullview["enddate"]; ?></td></tr>
								<tr><th>Start time</th><td><?php echo $row_fullview["starttime"]; ?></td></tr>
								<tr><th>End Time</th><td><?php echo $row_fullview["enddate"]; ?></td></tr>
								<tr><th>Reason</th><td><?php echo $row_fullview["reason"]; ?></td></tr>
								<tr><th>Appl Date</th><td><?php echo $row_fullview["applydate"]; ?></td></tr>
								<tr><th>status</th><td><?php echo $row_fullview["status"]; ?></td></tr>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=staffleave.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
											<a href="index.php?page=staffleave.php&option=edit&pk_leave_id=<?php echo $row_fullview["leave_id"]; ?>"><button class="btn btn-info">Edit</button></a> 
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
	else if($_GET["option"]=="edit")
	{
		//edit form
		$get_pk_leave_id=$_GET["pk_leave_id"];
		
		$sql_edit="SELECT * FROM staffleave WHERE leave_id='$get_pk_leave_id'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit ".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Edit staff Apply leave</div>
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
											<label for="txtleaveid">leave ID</label>
											<input type="text" class="form-control" name="txtleaveid" id="txtleaveid" required placeholder="Leave ID" value="<?php echo $row_edit["leave_id"]; ?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtstaffid">staff ID</label>
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
										<!-- column two end -->
									</div>
								</div>
								<!-- one row end -->
								
								<!-- second row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtstartdate">Start date</label>
											<input type="date" class="form-control" name="txtstartdate" id="txtstartdate" required placeholder="start date" value="<?php echo $row_edit["startdate"]; ?>"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtstarttime">Start time</label>
											<input type="time" class="form-control" name="txtstarttime" id="txtstarttime" required placeholder="Start time" value="<?php echo $row_edit["starttime"]; ?>"/>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- second row end -->
								
								<!-- third row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtenddate">End date</label>
											<input type="date" class="form-control" name="txtenddate" id="txtenddate" required placeholder="End date" value="<?php echo $row_edit["enddate"]; ?>"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtendtime">End time</label>
											<input type="time" class="form-control" name="txtendtime" id="txtendtime" required placeholder="End time" value="<?php echo $row_edit["endtime"]; ?>"/>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- second row end -->
								
								<!-- third row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtreason">Reason</label>
											<input type="text" class="form-control" name="txtreason" id="txtreason" required placeholder=" Reasons for Leave apply" value="<?php echo $row_edit["reason"]; ?>"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtapplydate">Apply date</label>
											<input type="date" class="form-control" name="txtapplydate" id="txtapplydate" required placeholder="Apply date" value="<?php echo $row_edit["applydate"]; ?>"/>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- third row end -->
								
								<!-- fourth row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtstatus">status</label>
											<input type="text" class="form-control" name="txtstatus" id="txtstatus" required placeholder="Approved/deny" value="<?php echo $row_edit["status"]; ?>"/>
										</div>
										<!-- column one end -->
									</div>
								</div>
								<!-- fourth row end -->
								
								
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=staffleave.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
												<input type="reset" class="btn btn-danger" name="btncancel" id="btncancel"  value="Cancel"/>
												<input type="submit" class="btn btn-success" name="btnsavechanges" id="btnsavechanges"  value="Save changes"/>
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
	else if($_GET["option"]=="delete")
	{
		//delete code
		$get_pk_leave_id=$_GET["pk_leave_id"];
		
		$sql_delete="DELETE FROM staffleave WHERE leave_id='$get_pk_leave_id'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete)
		{
		echo '<script>alert("Successfully Deleted");
						window.location.href="index.php?page=staffleave.php&option=view";</script>';
		}
	}
}
?>
</body>