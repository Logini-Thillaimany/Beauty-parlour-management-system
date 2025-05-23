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
	$sql_insert="INSERT INTO specialtime(date,starttime,endtime,enterby,package_id)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtdate"])."',
									'".mysqli_real_escape_string($con,$_POST["txtstarttime"])."',
									'".mysqli_real_escape_string($con,$_POST["txtendtime"])."',
									'".mysqli_real_escape_string($con,$_POST["txtstaffid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtpackageid"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
	if($result_insert)
	{
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=specialtime.php&option=add";</script>';
	}
}
//insert code end

//update code start
if(isset($_POST["btnsavechanges"]))
{
	$sql_update="UPDATE specialtime SET
							endtime='".mysqli_real_escape_string($con,$_POST["txtendtime"])."',
							enterby='".mysqli_real_escape_string($con,$_POST["txtstaffid"])."'
					  WHERE date='".mysqli_real_escape_string($con,$_POST["txtdate"])."' AND
							starttime='".mysqli_real_escape_string($con,$_POST["txtstarttime"])."'AND
							package_id='".mysqli_real_escape_string($con,$_POST["txtpackageid"])."'";
	$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
	if($result_update)
	{
		echo '<script>alert("Successfully update");
						window.location.href="index.php?page=specialtime.php&option=view";</script>';
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
						<div class="card-title"> Form for Special time allocation</div>
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
											<label for="txtdate">Date</label>
											<input type="date" class="form-control" name="txtdate" id="txtdate" required placeholder="Date"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtstarttime">Start time</label>
											<input type="time" class="form-control" name="txtstarttime" id="txtstarttime" required placeholder="Opening time"/>
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
											<label for="txtendtime">End time</label>
											<input type="time" class="form-control" name="txtendtime" id="txtendtime" required placeholder="Close time"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtstaffid">Enterby</label>
											<select  class="form-control" name="txtstaffid" id="txtstaffid" required placeholder="Enter By">
												<option value="select">Select Enter by </option>
												<?php
												$sql_load="SELECT staff_id, name FROM staff ";
												$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
												while($row_load=mysqli_fetch_assoc($result_load))
												{
													echo'<option value="'.$row_load["staff_id"].'">'.$row_load["name"].'</option>';
												}
												?>
											</select></div>
										<!-- column two end -->
									</div>
								</div>
								<!-- second row end -->
								
								<!-- third row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtpackageid">package id</label>
											<select class="form-control" name="txtpackageid" id="txtpackageid" required placeholder="Package id">
											<option value="select">Select Package </option>
												<?php
												$sql_load_package="SELECT package_id , name FROM package ";
												$result_load_package=mysqli_query($con,$sql_load_package) or die("sql error in sql_load_package".mysqli_error($con));
												while($row_load_package=mysqli_fetch_assoc($result_load_package))
												{
													echo'<option value="'.$row_load_package["package_id"].'">'.$row_load_package["name"].'</option>';
												}
												?>
											</select>
										</div>
										<!-- column one end -->
									</div>
								</div>
								<!-- third row end -->
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=specialtime.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
						<h4 class="card-title">Special time Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=specialtime.php&option=add"><button class="btn btn-primary">Add Special time</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>Date</th>
										<th>Start time</th>
										<th>End time</th>
										<th>Enter by</th>
										<th>Package</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql_view="SELECT date,starttime,endtime,enterby,package_id FROM specialtime";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{	$sql_enterby="SELECT name from staff WHERE staff_id='$row_view[enterby]'";
										$result_enterby=mysqli_query($con,$sql_enterby) or die("sql error in sql_enterby ".mysqli_error($con));
										$row_enterby=mysqli_fetch_assoc($result_enterby);
										
										$sql_package="SELECT name from package WHERE package_id='$row_view[package_id]'";
										$result_package=mysqli_query($con,$sql_package) or die("sql error in sql_package ".mysqli_error($con));
										$row_package=mysqli_fetch_assoc($result_package);
										
										echo '<tr>';
											echo '<td>'.$row_view["date"].'</td>';
											echo '<td>'.$row_view["starttime"].'</td>';
											echo '<td>'.$row_view["endtime"].'</td>';
											echo '<td>'.$row_enterby["name"].'</td>';
											echo '<td>'.$row_package["name"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=specialtime.php&option=edit&pk_date='.$row_view["date"].'&pk_starttime='.$row_view["starttime"].'&pk_package_id='.$row_view["package_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
												echo '<a onclick="return delete_confirm()" href="index.php?page=specialtime.php&option=delete&pk_date='.$row_view["date"].'&pk_starttime='.$row_view["starttime"].'&pk_package_id='.$row_view["package_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
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
		$get_pk_date=$_GET["pk_date"];
		$get_pk_starttime=$_GET["pk_starttime"];
		$get_pk_package_id=$_GET["pk_package_id"];
		
		$sql_fullview="SELECT * FROM specialtime WHERE date='$get_pk_date' AND starttime='$get_pk_starttime' AND package_id='$get_pk_package_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		$sql_enterby="SELECT name from staff WHERE staff_id='$row_fullview[enterby]'";
		$result_enterby=mysqli_query($con,$sql_enterby) or die("sql error in sql_enterby ".mysqli_error($con));
		$row_enterby=mysqli_fetch_assoc($result_enterby);
										
		$sql_package="SELECT name from package WHERE package_id='$row_fullview[package_id]'";
		$result_package=mysqli_query($con,$sql_package) or die("sql error in sql_package ".mysqli_error($con));
		$row_package=mysqli_fetch_assoc($result_package);
										
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Special time Full Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table  class="display table table-striped table-hover">
								<tr><th>Date</th><td><?php echo $row_fullview["date"]; ?></td></tr>
								<tr><th>Start time </th><td><?php echo $row_fullview["starttime"]; ?></td></tr>
								<tr><th>End time </th><td><?php echo $row_fullview["endtime"]; ?></td></tr>
								<tr><th>Enter By</th><td><?php echo $row_enterby["name"]; ?></td></tr>
								<tr><th>Package </th><td><?php echo $row_package["name"]; ?></td></tr>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=specialtime.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
											<a href="index.php?page=specialtime.php&option=edit&pk_date=<?php echo $row_fullview["date"]; ?>&pk_starttime=<?php echo $row_fullview["starttime"]; ?>&pk_package_id=<?php echo $row_fullview["package_id"]; ?>"><button class="btn btn-info">Edit</button></a> 
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
		$get_pk_date=$_GET["pk_date"];
		$get_pk_starttime=$_GET["pk_starttime"];
		$get_pk_package_id=$_GET["pk_package_id"];
		
		$sql_edit="SELECT * FROM specialtime WHERE date='$get_pk_date' AND starttime='$get_pk_starttime' AND package_id='$get_pk_package_id'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Edit Special time allocation</div>
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
											<label for="txtdate">Date</label>
											<input type="date" class="form-control" name="txtdate" id="txtdate" required placeholder="Date" value="<?php echo $row_edit["date"]; ?>"  readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtstarttime">Start time</label>
											<input type="time" class="form-control" name="txtstarttime" id="txtstarttime" required placeholder="Opening time" value="<?php echo $row_edit["starttime"]; ?>"  readonly />
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
											<label for="txtendtime">End time</label>
											<input type="time" class="form-control" name="txtendtime" id="txtendtime" required placeholder="Close time" value="<?php echo $row_edit["endtime"]; ?>" />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtstaffid">Enterby</label>
											<select  class="form-control" name="txtstaffid" id="txtstaffid" required placeholder="Enter By">
												<option value="select"><?php echo $row_edit["enterby"]; ?></option>
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
								<!-- second row end -->
								
								<!-- third row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtpackageid">package id</label>
											<select class="form-control" name="txtpackageid" id="txtpackageid" required placeholder="Package id">
											<option value="select">Select Package </option>
												<?php
												$sql_load_package="SELECT package_id , name FROM package ";
												$result_load_package=mysqli_query($con,$sql_load_package) or die("sql error in sql_load_package".mysqli_error($con));
												while($row_load_package=mysqli_fetch_assoc($result_load_package))
												{
													echo'<option value="'.$row_load_package["package_id"].'">'.$row_load_package["name"].'</option>';
												}
												?>
											</select>
										</div>
										<!-- column one end -->
									</div>
								</div>
								<!-- third row end -->
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=specialtime.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
		$get_pk_date=$_GET["pk_date"];
		$get_pk_starttime=$_GET["pk_starttime"];
		$get_pk_package_id=$_GET["pk_package_id"];
		
		$sql_delete="DELETE FROM specialtime WHERE date='$get_pk_date' AND starttime='$get_pk_starttime' AND package_id='$get_pk_package_id'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete)
		{
		echo '<script>alert("Successfully Deleted");
						window.location.href="index.php?page=specialtime.php&option=view";</script>';
		}	
	}
}
?>
</body>