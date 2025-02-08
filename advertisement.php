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
	$sql_insert="INSERT INTO advertisement(add_id,name,image,enterby,status,date)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtadvertisementid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtname"])."',
									'".mysqli_real_escape_string($con,$_POST["txtimage"])."',
									'".mysqli_real_escape_string($con,$_POST["txtstaffid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtstatus"])."',
									'".mysqli_real_escape_string($con,$_POST["txtdate"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
	if($result_insert)
	{
		echo '<script>alert("Successfully Insert");
					  window.location.href="index.php?page=advertisement.php&option=add";
			  </script>';
	}
}
//insert code end

//update code start
if(isset($_POST["btnsavechanges"]))
{
	$sql_update="UPDATE advertisement SET
								name='".mysqli_real_escape_string($con,$_POST["txtname"])."',
								image='".mysqli_real_escape_string($con,$_POST["txtimage"])."',
								enterby='".mysqli_real_escape_string($con,$_POST["txtstaffid"])."',
								status='".mysqli_real_escape_string($con,$_POST["txtstatus"])."',
								date='".mysqli_real_escape_string($con,$_POST["txtdate"])."'
							WHERE add_id='".mysqli_real_escape_string($con,$_POST["txtadvertisementid"])."'";
	$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
	if($result_update)
	{
		echo '<script>alert("Successfully update");
					  window.location.href="index.php?page=advertisement.php&option=view";
			  </script>';
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
						<div class="card-title"> Form for Advertisement Addition</div>
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
											<label for="txtadvertisementid">Advertisement ID</label>
											<?php
												$sql_generatedid="SELECT add_id FROM advertisement ORDER BY add_id DESC LIMIT 1";
												$result_generatedid=mysqli_query($con,$sql_generatedid) or die("sql error in sql_generatedid ".mysqli_error($con));
												if(mysqli_num_rows($result_generatedid)==1)
												{// for  except from the first submission
													$row_generatedid=mysqli_fetch_assoc($result_generatedid);
													$generatedid=++$row_generatedid["add_id"];
												}
												else
												{//For first time submission
													$generatedid="AD00001";
												}
											?>
											<input type="text" class="form-control" name="txtadvertisementid" id="txtadvertisementid" required placeholder="Advertisement ID" value="<?php echo $generatedid;?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtname">Name</label>
											<input type="text" onkeypress="return isTextKey(event)" class="form-control" name="txtname" id="txtname" required placeholder="Advertisement Name"/>
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
											 <label for="txtimage">Advertisement image</label>
											 <input type="text" class="form-control"  name="txtimage" id="txtimage" required placeholder="Advertisement image" />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtdate">Date</label>
											<input type="date" class="form-control" name="txtdate" id="txtdate" required placeholder="Date"/>
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
											<label for="txtstaffid">Enter by</label>
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
											</select>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtstatus">Status</label>
											<input type="text" class="form-control" name="txtstatus" id="txtstatus" required placeholder="status">
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- third row end -->
								
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=advertisement.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
						<h4 class="card-title">Advertisement Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=advertisement.php&option=add"><button class="btn btn-primary">Add Advertisement</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>Advertisement ID</th>
										<th>Name</th>
										<th>image</th>
										<th>Enter by</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql_view="SELECT add_id,name,image,enterby,status FROM advertisement";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{
										$sql_enterby="SELECT name from staff WHERE staff_id='$row_view[enterby]'";
										$result_enterby=mysqli_query($con,$sql_enterby) or die("sql error in sql_enterby ".mysqli_error($con));
										$row_enterby=mysqli_fetch_assoc($result_enterby);
										echo '<tr>';
											echo '<td>'.$row_view["add_id"].'</td>';
											echo '<td>'.$row_view["name"].'</td>';
											echo '<td>'.$row_view["image"].'</td>';
											echo '<td>'.$row_enterby["name"].'</td>';
											echo '<td>'.$row_view["status"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=advertisement.php&option=fullview&pk_add_id='.$row_view["add_id"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
												echo '<a href="index.php?page=advertisement.php&option=edit&pk_add_id='.$row_view["add_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
												echo '<a onclick="return delete_confirm()" href="index.php?page=advertisement.php&option=delete&pk_add_id='.$row_view["add_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
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
		$get_pk_add_id=$_GET["pk_add_id"];
		
		$sql_fullview="SELECT * FROM advertisement WHERE add_id='$get_pk_add_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		$sql_enterby="SELECT name from staff WHERE staff_id='$row_fullview[enterby]'";
		$result_enterby=mysqli_query($con,$sql_enterby) or die("sql error in sql_enterby ".mysqli_error($con));
		$row_enterby=mysqli_fetch_assoc($result_enterby);
										
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Advertisement Full Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="display table table-striped table-hover">
								<tr><th>Advertisement ID</th><td><?php echo $row_fullview["add_id"]; ?></td></tr>
								<tr><th>Name</th><td><?php echo $row_fullview["name"]; ?></td></tr>
								<tr><th>image</th><td><?php echo $row_fullview["image"]; ?></td></tr>
								<tr><th>Enter by</th><td><?php echo $row_enterby["name"]; ?></td></tr>
								<tr><th>Status</th><td><?php echo $row_fullview["status"]; ?></td></tr>
								<tr><th>Date</th><td><?php echo $row_fullview["date"]; ?></td></tr>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=advertisement.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
											<a href="index.php?page=advertisement.php&option=edit&pk_add_id=<?php echo $row_fullview["add_id"]; ?>"><button class="btn btn-info">Edit</button></a> 
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
		$get_pk_add_id=$_GET["pk_add_id"];
		
		$sql_edit="SELECT * FROM advertisement WHERE add_id='$get_pk_add_id'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit ".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Advertisement Edit</div>
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
											<label for="txtadvertisementid">Advertisement ID</label>
											<input type="text" class="form-control" name="txtadvertisementid" id="txtadvertisementid" required placeholder="Advertisement ID" value="<?php echo $row_edit["add_id"]; ?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtname">Name</label>
											<input type="text" onkeypress="return isTextKey(event)" class="form-control" name="txtname" id="txtname" required placeholder="Advertisement Name" value="<?php echo $row_edit["name"]; ?>"/>
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
											 <label for="txtimage">Advertisement image</label>
											 <input type="text" class="form-control"  name="txtimage" id="txtimage" required placeholder="Advertisement image" value="<?php echo $row_edit["image"]; ?>" />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtdate">Date</label>
											<input type="date" class="form-control" name="txtdate" id="txtdate" required placeholder="Date" value="<?php echo $row_edit["date"]; ?>" />
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
											<label for="txtstaffid">Enter by</label>
											<select  class="form-control" name="txtstaffid" id="txtstaffid" required placeholder="Enter By">
												<option value="select">Select Enter by </option>
												<?php
												$sql_loadedit="SELECT staff_id, name FROM staff ";
												$result_loadedit=mysqli_query($con,$sql_loadedit) or die("sql error in sql_loadedit".mysqli_error($con));
												while($row_loadedit=mysqli_fetch_assoc($result_loadedit))
												{
													echo'<option value="'.$row_loadedit["staff_id"].'">'.$row_loadedit["name"].'</option>';
												}
												?>
											</select>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtstatus">Status</label>
											<input type="text" class="form-control" name="txtstatus" id="txtstatus" required placeholder="status" value="<?php echo $row_edit["status"]; ?>">
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- third row end -->
								
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=advertisement.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
												<input type="reset" class="btn btn-danger" name="btnclear" id="btnclear"  value="Clear"/>
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
		$get_pk_add_id=$_GET["pk_add_id"];
		
		$sql_delete="DELETE FROM advertisement WHERE add_id='$get_pk_add_id'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete)
		{
				echo '<script>alert("Successfully Deleted");
				window.location.href="index.php?page=advertisement.php&option=view";</script>';
		}
	}
}
?>
</body>