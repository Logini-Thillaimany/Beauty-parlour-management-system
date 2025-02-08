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
	$sql_insert="INSERT INTO staffpackage(staff_id,package_id,status)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtstaffid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtpackageid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtstatus"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
	if($result_insert)
	{
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=staffpackage.php&option=add";</script>';
	}
}
//insert code end

//update code start
if(isset($_POST["btnsavechanges"]))
{
	$sql_update="UPDATE staffpackage SET
							status='".mysqli_real_escape_string($con,$_POST["txtstatus"])."'
							WHERE staff_id='".mysqli_real_escape_string($con,$_POST["txtstaffid"])."' AND
							package_id='".mysqli_real_escape_string($con,$_POST["txtpackageid"])."'";
	$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
	if($result_update)
	{
		echo '<script>alert("Successfully update");
						window.location.href="index.php?page=staffpackage.php&option=view";</script>';
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
						<div class="card-title"> Form to  Allocate staff  for package</div>
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
											<label for="txtstaffid">Staff ID</label>
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
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtpackageid">Package ID</label>
											<select class="form-control" name="txtpackageid" id="txtpackageid" required placeholder="Package ID" >
												<option value="select">Select Package </option>
													<?php
													$sql_load="SELECT package_id, name FROM package ";
													$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
													while($row_load=mysqli_fetch_assoc($result_load))
													{
														echo'<option value="'.$row_load["package_id"].'">'.$row_load["name"].'</option>';
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
											<label for="txtstatus">status</label>
											<input type="text" class="form-control" name="txtstatus" id="txtstatus" required placeholder="Approved/deny"/>
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
												<a href="index.php?page=staffpackage.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
						<h4 class="card-title">Staff package Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=staffpackage.php&option=add"><button class="btn btn-primary">Add Staff staffpackage</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>package_id
										<th>Staff ID</th>
										<th>package ID</th>
										<th>status</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql_view="SELECT staff_id,package_id,status FROM staffpackage";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{	$sql_staff="SELECT name from staff WHERE staff_id='$row_view[staff_id]'";
										$result_staff=mysqli_query($con,$sql_staff) or die("sql error in sql_staff ".mysqli_error($con));
										$row_staff=mysqli_fetch_assoc($result_staff);
										
										$sql_package="SELECT name from package WHERE package_id='$row_view[package_id]'";
										$result_package=mysqli_query($con,$sql_package) or die("sql error in sql_package ".mysqli_error($con));
										$row_package=mysqli_fetch_assoc($result_package);
										
		
		
										echo '<tr>';
											echo '<td>'.$row_staff["name"].'</td>';
											echo '<td>'.$row_package["name"].'</td>';
											echo '<td>'.$row_view["status"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=staffpackage.php&option=edit&pk_staff_id='.$row_view["staff_id"].'&pk_package_id='.$row_view["package_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
												echo '<a onclick="return delete_confirm()" href="index.php?page=staffpackage.php&option=delete&pk_staff_id='.$row_view["staff_id"].'&pk_package_id='.$row_view["package_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
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
		$get_pk_package_id=$_GET["pk_package_id"];
		$get_pk_staff_id=$_GET["pk_staff_id"];
		
		$sql_fullview="SELECT * FROM staffpackage WHERE package_id='$get_pk_package_id' AND staff_id='$get_pk_staff_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		$sql_staff="SELECT name from staff WHERE staff_id='$row_fullview[staff_id]'";
		$result_staff=mysqli_query($con,$sql_staff) or die("sql error in sql_staff ".mysqli_error($con));
		$row_staff=mysqli_fetch_assoc($result_staff);
		
		$sql_package="SELECT name from package WHERE package_id='$row_fullview[package_id]'";
		$result_package=mysqli_query($con,$sql_package) or die("sql error in sql_package ".mysqli_error($con));
		$row_package=mysqli_fetch_assoc($result_package);
										
		
										
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Staff package Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table  class="display table table-striped table-hover">
								<tr><th>Package ID</th><td><?php echo $row_package["name"]; ?></td></tr>
								<tr><th>Staff ID</th><td><?php echo $row_staff["name"]; ?></td></tr>
								<tr><th>Status</th><td><?php echo $row_fullview["status"]; ?></td></tr>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=staffpackage.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
											<a href="index.php?page=staffpackage.php&option=edit&pk_staff_id=<?php echo $row_fullview["staff_id"]; ?>&pk_package_id=<?php echo $row_fullview["package_id"]; ?>"><button class="btn btn-info">Edit</button></a> 
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
		$get_pk_package_id=$_GET["pk_package_id"];
		$get_pk_staff_id=$_GET["pk_staff_id"];
		
		$sql_edit="SELECT * FROM staffpackage WHERE package_id='$get_pk_package_id' AND staff_id='$get_pk_staff_id'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit ".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form to  EDIT staff  for package</div>
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
											<label for="txtstaffid">Staff ID</label>
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
											</select></div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtpackageid">Package ID</label>
											<select class="form-control" name="txtpackageid" id="txtpackageid" required placeholder="Package ID" value="<?php echo $row_edit["package_id"]; ?>" readonly >
												<option value="select">Select Package </option>
													<?php
													$sql_load="SELECT package_id, name FROM package ";
													$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
													while($row_load=mysqli_fetch_assoc($result_load))
													{
														echo'<option value="'.$row_load["package_id"].'">'.$row_load["name"].'</option>';
													}
													?>
											</select></div>
										<!-- column two end -->
									</div>
								</div>
								<!-- one row end -->
								
								<!-- second row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtstatus">status</label>
											<input type="text" class="form-control" name="txtstatus" id="txtstatus" required placeholder="Approved/deny"  value="<?php echo $row_edit["status"]; ?>"  />
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
												<a href="index.php?page=staffpackage.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
		$get_pk_package_id=$_GET["pk_package_id"];
		$get_pk_staff_id=$_GET["pk_staff_id"];
		
		$sql_delete="DELETE  FROM staffpackage WHERE package_id='$get_pk_package_id' AND staff_id='$get_pk_staff_id'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete)
		{
				echo '<script>alert("Successfully Deleted");
						window.location.href="index.php?page=staffpackage.php&option=view";</script>';
		}	
	}
}
?>
</body>