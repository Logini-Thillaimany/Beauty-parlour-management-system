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
	$sql_insert="INSERT INTO staff(staff_id,name,nic,dob,gender,mobile,address,designation,jointdate)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtstaffid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtname"])."',
									'".mysqli_real_escape_string($con,$_POST["txtnic"])."',
									'".mysqli_real_escape_string($con,$_POST["txtdob"])."',
									'".mysqli_real_escape_string($con,$_POST["txtgender"])."',
									'".mysqli_real_escape_string($con,$_POST["txtmobile"])."',
									'".mysqli_real_escape_string($con,$_POST["txtaddress"])."',
									'".mysqli_real_escape_string($con,$_POST["txtdesignation"])."',
									'".mysqli_real_escape_string($con,$_POST["txtjoindate"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
	
	//insert into login
	$password=md5($_POST["txtnic"]);
	$sql_insert_login="INSERT INTO login(user_id,username,password,usertype,attempt,code,status)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtstaffid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtnic"])."',
									'".mysqli_real_escape_string($con,$password)."',
									'".mysqli_real_escape_string($con,$_POST["txtdesignation"])."',
									'".mysqli_real_escape_string($con,0)."',
									'".mysqli_real_escape_string($con,0)."',
									'".mysqli_real_escape_string($con,"Active")."')";
	$result_insert_login=mysqli_query($con,$sql_insert_login) or die("sql error in sql_insert_login ".mysqli_error($con));
	if($result_insert)
	{
		echo '<script>alert("Successfully Insert");
			  window.location.href="index.php?page=staff.php&option=add";</script>';
	}
}
//insert code end

//update code start
if(isset($_POST["btnsavechanges"]))
{
	$sql_update="UPDATE staff SET
								name='".mysqli_real_escape_string($con,$_POST["txtname"])."',
								nic='".mysqli_real_escape_string($con,$_POST["txtnic"])."',
								dob='".mysqli_real_escape_string($con,$_POST["txtdob"])."',
								gender='".mysqli_real_escape_string($con,$_POST["txtgender"])."',
								mobile='".mysqli_real_escape_string($con,$_POST["txtmobile"])."',
								address='".mysqli_real_escape_string($con,$_POST["txtaddress"])."',
								designation='".mysqli_real_escape_string($con,$_POST["txtdesignation"])."',
								jointdate='".mysqli_real_escape_string($con,$_POST["txtjoindate"])."'
								WHERE staff_id='".mysqli_real_escape_string($con,$_POST["txtstaffid"])."'";
	$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
	if($result_update)
	{
		echo '<script>alert("Successfully Update");
			  window.location.href="index.php?page=staff.php&option=view";</script>';
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
						<div class="card-title">Form for Staff Addition</div>
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
											<label for="email2">Staff ID</label>
											<?php
												$sql_generatedid="SELECT staff_id FROM staff ORDER BY staff_id DESC LIMIT 1";
												$result_generatedid=mysqli_query($con,$sql_generatedid) or die("sql error in sql_generatedid ".mysqli_error($con));
												if(mysqli_num_rows($result_generatedid)==1)
												{// for  except from the first submission
													$row_generatedid=mysqli_fetch_assoc($result_generatedid);
													$generatedid=++$row_generatedid["staff_id"];
												}
												else
												{//For first time submission
													$generatedid="ST001";
												}
											?>
											<input type="text" class="form-control" name="txtstaffid" id="txtstaffid" required placeholder="Type your Staff ID" value="<?php echo $generatedid;?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="email2">Name</label>
											<input type="text" onkeypress="return isTextKey(event)" class="form-control" name="txtname" id="txtname" required placeholder="Type your Staff Name"/>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- one row end -->
								
								<!-- one row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="email2">NIC</label>
											<input type="text" class="form-control" onblur="nicnumber()" name="txtnic" id="txtnic" required placeholder="Type your NIC"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="email2">DOB</label>
											<input type="date" class="form-control" name="txtdob" id="txtdob" required placeholder="Type your DOB"/>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- one row end -->
								
								<!-- one row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="email2">Gender</label>
											<input type="text" class="form-control" name="txtgender" id="txtgender" required placeholder="Type your Gender"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="email2">Mobile</label>
											<input type="text" onkeypress="return isNumberKey(event)"  onblur="phonenumber('txtmobile')" class="form-control" name="txtmobile" id="txtmobile" required placeholder="Type your Mobile"/>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- one row end -->
								
								<!-- one row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="email2">Designation</label>
											<select class="form-control" name="txtdesignation" id="txtdesignation" required placeholder="Type your Designation">
												<option value="select">Select</option>
												<option value="Admin">Admin</option>
												<option value="Clerk">Clerk</option>
												<option value="MakeupArtist">MakeupArtist</option>
												<option value="SaloonService">SaloonService</option>
											</select>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="email2">Join Date</label>
											<input type="date" class="form-control" name="txtjoindate" id="txtjoindate" required placeholder="Type your Join Date"/>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- one row end -->
								
								<!-- one row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="email2">Address</label>
											<textarea class="form-control" name="txtaddress" id="txtaddress" required placeholder="Type your Address"></textarea>
										</div>
										<!-- column one end -->
									</div>
								</div>
								<!-- one row end -->
								
								
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=staff.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
						<h4 class="card-title">Staff Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=staff.php&option=add"><button class="btn btn-primary">Add Staff</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>Staff ID</th>
										<th>Name</th>
										<th>NIC</th>
										<th>Designation</th>
										<th>Mobile</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql_view="SELECT staff_id,name,nic,designation,mobile FROM staff";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{
										echo '<tr>';
											echo '<td>'.$row_view["staff_id"].'</td>';
											echo '<td>'.$row_view["name"].'</td>';
											echo '<td>'.$row_view["nic"].'</td>';
											echo '<td>'.$row_view["designation"].'</td>';
											echo '<td>'.$row_view["mobile"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=staff.php&option=fullview&pk_staff_id='.$row_view["staff_id"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
												echo '<a href="index.php?page=staff.php&option=edit&pk_staff_id='.$row_view["staff_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
												echo '<a onclick="return delete_confirm()" href="index.php?page=staff.php&option=delete&pk_staff_id='.$row_view["staff_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
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
		$get_pk_staff_id=$_GET["pk_staff_id"];
		
		$sql_fullview="SELECT * FROM staff WHERE staff_id='$get_pk_staff_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Staff Full Details</h4>
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
								<tr><th>Mobile</th><td><?php echo $row_fullview["mobile"]; ?></td></tr>
								<tr><th>Joint Date</th><td><?php echo $row_fullview["jointdate"]; ?></td></tr>
								<tr>								
									<td colspan="2">
										<center>
											<a href="index.php?page=staff.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
											<a href="index.php?page=staff.php&option=edit&pk_staff_id=<?php echo $row_fullview["staff_id"]; ?>"><button class="btn btn-info">Edit</button></a> 
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
		$get_pk_staff_id=$_GET["pk_staff_id"];
		
		$sql_edit="SELECT * FROM staff WHERE staff_id='$get_pk_staff_id'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit ".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title">Form for Staff Edit</div>
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
											<label for="email2">Staff ID</label>
											<input type="text" class="form-control" name="txtstaffid" id="txtstaffid" required placeholder="Type your Staff ID" value="<?php echo $row_edit["staff_id"]; ?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="email2">Name</label>
											<input type="text" onkeypress="return isTextKey(event)" class="form-control" name="txtname" id="txtname" required placeholder="Type your Staff Name" value="<?php echo $row_edit["name"]; ?>" />
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- one row end -->
								
								<!-- one row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="email2">NIC</label>
											<input type="text"  onblur="nicnumber()"  class="form-control" name="txtnic" id="txtnic" required placeholder="Type your NIC" value="<?php echo $row_edit["nic"]; ?>" />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="email2">DOB</label>
											<input type="date" class="form-control" name="txtdob" id="txtdob" required placeholder="Type your DOB" value="<?php echo $row_edit["dob"]; ?>" />
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- one row end -->
								
								<!-- one row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="email2">Gender</label>
											<input type="text" class="form-control" name="txtgender" id="txtgender" required placeholder="Type your Gender" value="<?php echo $row_edit["gender"]; ?>" />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="email2">Mobile</label>
											<input type="text"  onkeypress="return isNumberKey(event)" onblur="phonenumber('txtmobile')"  class="form-control" name="txtmobile" id="txtmobile" required placeholder="Type your Mobile" value="0<?php echo $row_edit["mobile"]; ?>" />
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- one row end -->
								
								<!-- one row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="email2">Designation</label>
											<input type="text" class="form-control" name="txtdesignation" id="txtdesignation" required placeholder="Type your Designation" value="<?php echo $row_edit["designation"]; ?>" />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="email2">Join Date</label>
											<input type="date" class="form-control" name="txtjoindate" id="txtjoindate" required placeholder="Type your Join Date" value="<?php echo $row_edit["jointdate"]; ?>" />
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- one row end -->
								
								<!-- one row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="email2">Address</label>
											<textarea class="form-control" name="txtaddress" id="txtaddress" required placeholder="Type your Address"><?php echo $row_edit["address"]; ?></textarea>
										</div>
										<!-- column one end -->
									</div>
								</div>
								<!-- one row end -->
								
								
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=staff.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
												<input type="reset" class="btn btn-danger" name="btncancel" id="btncancel"  value="Cancel"/>
												<input type="submit" class="btn btn-success" name="btnsavechanges" id="btnsavechanges"  value="Save Changes"/>
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
		$get_pk_staff_id=$_GET["pk_staff_id"];
		
		$sql_delete="DELETE FROM staff WHERE staff_id='$get_pk_staff_id'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete)
		{
			echo '<script>alert("Successfully Deleted");
				  window.location.href="index.php?page=staff.php&option=view";</script>';
		}
	}
}
?>
</body>