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
	$sql_insert="INSERT INTO package(package_id,name,duration,description,subcategory_id)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtpackageid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtname"])."',
									'".mysqli_real_escape_string($con,$_POST["txtduration"])."',
									'".mysqli_real_escape_string($con,$_POST["txtdescription"])."',
									'".mysqli_real_escape_string($con,$_POST["txtsubcategory"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
	if($result_insert)
	{
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=package.php&option=add";</script>';
	}
}
//insert code end

//update code start
if(isset($_POST["btnsavechanges"]))
{
	$sql_update="UPDATE package SET
								name='".mysqli_real_escape_string($con,$_POST["txtname"])."',
								duration='".mysqli_real_escape_string($con,$_POST["txtduration"])."',
								description='".mysqli_real_escape_string($con,$_POST["txtdescription"])."',
								subcategory_id='".mysqli_real_escape_string($con,$_POST["txtsubcategory"])."'
								WHERE package_id='".mysqli_real_escape_string($con,$_POST["txtpackageid"])."'";
	$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
	if($result_update)
	{
		echo '<script>alert("Successfully update");
						window.location.href="index.php?page=package.php&option=view";</script>';
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
						<div class="card-title"> Form for Package Addition</div>
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
											<label for="txtpackageid">Package ID</label>
											<?php
												$sql_generatedid="SELECT package_id FROM package ORDER BY package_id DESC LIMIT 1";
												$result_generatedid=mysqli_query($con,$sql_generatedid) or die("sql error in sql_generatedid ".mysqli_error($con));
												if(mysqli_num_rows($result_generatedid)==1)
												{// for  except from the first submission
													$row_generatedid=mysqli_fetch_assoc($result_generatedid);
													$generatedid=++$row_generatedid["package_id"];
												}
												else
												{//For first time submission
													$generatedid="PKG0001";
												}
											?>
											<input type="text" class="form-control" name="txtpackageid" id="txtpackageid" required placeholder="Package ID" value="<?php echo $generatedid;?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtsubcategory">Sub category</label>
											<select class="form-control" name="txtsubcategory" id="txtsubcategory" required placeholder="Sub category ID" >
												<option value="select">Select Sub category </option>
													<?php
													$sql_load="SELECT subcategory_id, name FROM packagesubcategory ";
													$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
													while($row_load=mysqli_fetch_assoc($result_load))
													{
														echo'<option value="'.$row_load["subcategory_id"].'">'.$row_load["name"].'</option>';
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
											<label for="txtname">Name</label>
											<input type="text" onkeypress="return isTextKey(event)" class="form-control" name="txtname" id="txtname" required placeholder="Name"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtduration">Duration</label>
											<input type="text" class="form-control" name="txtduration" id="txtduration" required placeholder="time duration"/>
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
											<label for="txtdescription">description</label>
											<textarea class="form-control" name="txtdescription" id="txtdescription" required placeholder="Package description"></textarea>
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
												<a href="index.php?page=package.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
						<h4 class="card-title">package Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=package.php&option=add"><button class="btn btn-primary">Add package</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>package ID</th>
										<th>Name</th>
										<th>Duration</th>
										<th>Description</th>
										<th>Subcategory</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql_view="SELECT package_id,name,duration,description,subcategory_id FROM package";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{
										$sql_enterby="SELECT name from packagesubcategory WHERE subcategory_id='$row_view[subcategory_id]'";
										$result_enterby=mysqli_query($con,$sql_enterby) or die("sql error in sql_enterby ".mysqli_error($con));
										$row_enterby=mysqli_fetch_assoc($result_enterby);
										echo '<tr>';
											echo '<td>'.$row_view["package_id"].'</td>';
											echo '<td>'.$row_view["name"].'</td>';
											echo '<td>'.$row_view["duration"].'</td>';
											echo '<td>'.$row_view["description"].'</td>';
											echo '<td>'.$row_enterby["name"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=package.php&option=fullview&pk_package_id='.$row_view["package_id"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
												echo '<a href="index.php?page=package.php&option=edit&pk_package_id='.$row_view["package_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
												echo '<a onclick="return delete_confirm()" href="index.php?page=package.php&option=delete&pk_package_id='.$row_view["package_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
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
		
		$sql_fullview="SELECT * FROM package WHERE package_id='$get_pk_package_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		$sql_enterby="SELECT name from packagesubcategory WHERE subcategory_id='$row_fullview[subcategory_id]'";
		$result_enterby=mysqli_query($con,$sql_enterby) or die("sql error in sql_enterby ".mysqli_error($con));
		$row_enterby=mysqli_fetch_assoc($result_enterby);
										
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">package Full Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="display table table-striped table-hover">
								<tr><th>Package ID</th><td><?php echo $row_fullview["package_id"]; ?></td></tr>
								<tr><th>Name</th><td><?php echo $row_fullview["name"]; ?></td></tr>
								<tr><th>Duration</th><td><?php echo $row_fullview["duration"]; ?></td></tr>
								<tr><th>Description</th><td><?php echo $row_fullview["description"]; ?></td></tr>
								<tr><th>Subcategory</th><td><?php echo $row_enterby["name"]; ?></td></tr>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=package.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
											<a href="index.php?page=package.php&option=edit&pk_package_id=<?php echo $row_fullview["package_id"]; ?>"><button class="btn btn-info">Edit</button></a> 
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
		
		$sql_edit="SELECT * FROM package WHERE package_id='$get_pk_package_id'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit ".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Package Edit</div>
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
											<label for="txtpackageid">Package ID</label>
											<input type="text" class="form-control" name="txtpackageid" id="txtpackageid" required placeholder="Package ID" value="<?php echo $row_edit["package_id"]; ?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtsubcategory">Sub category</label>
											<select class="form-control" name="txtsubcategory" id="txtsubcategory" required placeholder="Sub category ID">
												<option value="select">Select Sub category </option>
													<?php
													$sql_load="SELECT subcategory_id, name FROM packagesubcategory ";
													$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
													while($row_load=mysqli_fetch_assoc($result_load))
													{
														echo'<option value="'.$row_load["subcategory_id"].'">'.$row_load["name"].'</option>';
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
											<label for="txtname">Name</label>
											<input type="text" class="form-control" onkeypress="return isTextKey(event)" name="txtname" id="txtname" required placeholder="Name" value="<?php echo $row_edit["name"]; ?>" />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtduration">Duration</label>
											<input type="text" class="form-control" name="txtduration" id="txtduration" required placeholder="time duration" value="<?php echo $row_edit["duration"]; ?>" \/>
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
											<label for="txtdescription">description</label>
											<textarea class="form-control" name="txtdescription" id="txtdescription" required placeholder="Package description"><?php echo $row_edit["description"]; ?></textarea>
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
												<a href="index.php?page=package.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
		
		$sql_delete="DELETE FROM package WHERE package_id='$get_pk_package_id'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete)
		{
			echo '<script>alert("Successfully Deleted");
						window.location.href="index.php?page=package.php&option=view";</script>';

		}		
	}
}
?>
</body>