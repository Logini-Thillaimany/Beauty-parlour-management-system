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
	$target_dir = "file/package/";
	$target_file = $target_dir . basename($_FILES["txtimage"]["name"]);
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg")
	{
		echo '<script>alert("Sorry, only JPG, JPEG & PNG  files are allowed.");</script>';
	}
	else
	{
		$filename=$_POST["txtphotoid"].".".$imageFileType;
		$fileupload=$target_dir. $filename;
		move_uploaded_file($_FILES["txtimage"]["tmp_name"], $fileupload);
		
		$sql_insert="INSERT INTO packagephoto(photo_id,photo,package_id)
								VALUES('".mysqli_real_escape_string($con,$_POST["txtphotoid"])."',
										'".mysqli_real_escape_string($con,$filename)."',
										'".mysqli_real_escape_string($con,$_POST["txtpackageid"])."')";
		$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
		if($result_insert)
		{
			echo '<script>alert("Successfully Insert");
							window.location.href="index.php?page=packagephoto.php&option=add&pk_package_id='.$_POST["txtpackageid"].'";</script>';
		}
	}
}
//insert code end

//update code start
if(isset($_POST["btnsavechanges"]))
{
	$sql_update="UPDATE packagephoto SET
									photo='".mysqli_real_escape_string($con,$_POST["txtimage"])."',
									package_id='".mysqli_real_escape_string($con,$_POST["txtpackageid"])."'
									WHERE photo_id='".mysqli_real_escape_string($con,$_POST["txtphotoid"])."'";
	$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
	if($result_update)
	{
		echo '<script>alert("Successfully update");
						window.location.href="index.php?page=packagephoto.php&option=view";</script>';
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
		$get_pk_package_id=$_GET["pk_package_id"];
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Add Package Photo </div>
					</div>
					<div class="card-body">
						<div class="row">
							<!-- form start -->
							<form method="POST" action="" enctype="multipart/form-data">
								
								<!-- one row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtpackageid">Package</label>
											<select class="form-control" name="txtpackageid" id="txtpackageid" required placeholder="Package ID">
												<?php
													$sql_load="SELECT package_id, name FROM package WHERE package_id='$get_pk_package_id' ";
													$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
													while($row_load=mysqli_fetch_assoc($result_load))
													{
														echo'<option value="'.$row_load["package_id"].'">'.$row_load["name"].'</option>';
													}
													?>
											</select>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtphotoid">Photo ID</label>
											<?php
												$sql_generatedid="SELECT photo_id FROM packagephoto ORDER BY photo_id DESC LIMIT 1";
												$result_generatedid=mysqli_query($con,$sql_generatedid) or die("sql error in sql_generatedid ".mysqli_error($con));
												if(mysqli_num_rows($result_generatedid)==1)
												{// for  except from the first submission
													$row_generatedid=mysqli_fetch_assoc($result_generatedid);
													$generatedid=++$row_generatedid["photo_id"];
												}
												else
												{//For first time submission
													$generatedid="PPKG00001";
												}
											?>
											<input type="text" class="form-control" name="txtphotoid" id="txtphotoid" required placeholder="Photo ID" value="<?php echo $generatedid;?>" readonly />
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- one row end -->
								
								<!-- two row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											 <label for="txtimage">image</label>
											 <input type="file" class="form-control"  name="txtimage" id="txtimage" required placeholder="image"/>
											 <font color="#B31B1B"> *Only the impage types can be upload (eg: .jpg, .jpeg, .png)</font>
										</div>
										<!-- column one end -->
									</div>
								</div>
								<!-- two row end -->
								
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=package.php&option=fullview&pk_package_id=<?php echo $get_pk_package_id; ?>"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
						<h4 class="card-title">package photo Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=packagephoto.php&option=add"><button class="btn btn-primary">Add package photo</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>Photo ID</th>
										<th>photo</th>
										<th>package</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql_view="SELECT photo_id,photo,package_id FROM packagephoto";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{	$sql_package="SELECT name from package WHERE package_id='$row_view[package_id]'";
										$result_package=mysqli_query($con,$sql_package) or die("sql error in sql_package ".mysqli_error($con));
										$row_package=mysqli_fetch_assoc($result_package);
										
										echo '<tr>';
											echo '<td>'.$row_view["photo_id"].'</td>';
											echo '<td>'.$row_view["photo"].'</td>';
											echo '<td>'.$row_package["name"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=packagephoto.php&option=fullview&pk_photo_id='.$row_view["photo_id"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
												echo '<a href="index.php?page=packagephoto.php&option=edit&pk_photo_id='.$row_view["photo_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
												echo '<a onclick="return delete_confirm()" href="index.php?page=packagephoto.php&option=delete&pk_photo_id='.$row_view["photo_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
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
		$get_pk_photo_id=$_GET["pk_photo_id"];
		
		$sql_fullview="SELECT * FROM packagephoto WHERE photo_id='$get_pk_photo_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		
			$sql_package="SELECT name from package WHERE package_id='$row_fullview[package_id]'";
			$result_package=mysqli_query($con,$sql_package) or die("sql error in sql_package ".mysqli_error($con));
			$row_package=mysqli_fetch_assoc($result_package);
										
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">package photo Full Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="display table table-striped table-hover">
								<tr><th>Photo ID</th><td><?php echo $row_fullview["photo_id"]; ?></td></tr>
								<tr><th>Package</th><td><?php echo $row_package["name"]; ?></td></tr>
								<tr><th>photo</th><td><?php echo $row_fullview["photo"]; ?></td></tr>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=packagephoto.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
											<a href="index.php?page=packagephoto.php&option=edit&pk_photo_id=<?php echo $row_fullview["photo_id"]; ?>"><button class="btn btn-info">Edit</button></a> 
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
		$get_pk_photo_id=$_GET["pk_photo_id"];
		
		$sql_edit="SELECT * FROM packagephoto WHERE photo_id='$get_pk_photo_id'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit ".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Edit Package Photo </div>
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
											<label for="txtpackageid">Package</label>
											<select class="form-control" name="txtpackageid" id="txtpackageid" required placeholder="Package ID">
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
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtphotoid">Photo ID</label>
											<input type="text" class="form-control" name="txtphotoid" id="txtphotoid" required placeholder="Photo ID" value="<?php echo $row_edit["photo_id"]; ?>" readonly />
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- one row end -->
								
								<!-- two row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											 <label for="txtimage">image</label>
											 <input type="txt" class="form-control"  name="txtimage" id="txtimage" required placeholder="image"  value="<?php echo $row_edit["photo"]; ?>" />
										</div>
										<!-- column one end -->
									</div>
								</div>
								<!-- two row end -->
								
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=packagephoto.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
		$get_pk_photo_id=$_GET["pk_photo_id"];
		
		$sql_details="SELECT * FROM packagephoto WHERE photo_id='$get_pk_photo_id'";
		$result_details=mysqli_query($con,$sql_details) or die("sql error in sql_details ".mysqli_error($con));
		$row_details=mysqli_fetch_assoc($result_details);

		unlink("file/package/".$row_details["photo"]);

		$sql_delete="DELETE FROM packagephoto WHERE photo_id='$get_pk_photo_id'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete".mysqli_error($con));
		
		if($result_delete)
	{
		echo '<script>alert("Successfully delete");
						window.location.href="index.php?page=package.php&option=fullview&pk_package_id='.$row_details["package_id"].'";</script>';
	}
	}
}
?>
</body>