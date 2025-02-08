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
	$sql_insert="INSERT INTO packagesubcategory(subcategory_id,name,category_id,image)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtsubcategoryid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtname"])."',
									'".mysqli_real_escape_string($con,$_POST["txtcategoryid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtimage"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
	if($result_insert)
	{
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=packagesubcategory.php&option=add";</script>';
	}
}
//insert code end

//update code start
if(isset($_POST["btnsavechanges"]))
{
	$sql_update="UPDATE packagesubcategory SET
									name='".mysqli_real_escape_string($con,$_POST["txtname"])."',
									category_id='".mysqli_real_escape_string($con,$_POST["txtcategoryid"])."',
									image='".mysqli_real_escape_string($con,$_POST["txtimage"])."'
									WHERE subcategory_id='".mysqli_real_escape_string($con,$_POST["txtsubcategoryid"])."'";
	$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
	if($result_update)
	{
		echo '<script>alert("Successfully update");
						window.location.href="index.php?page=packagesubcategory.php&option=view";</script>';
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
						<div class="card-title"> Form for package subcategory Addition</div>
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
											<label for="txtcategoryid">Category</label>
											<select class="form-control" name="txtcategoryid" id="txtcategoryid" required placeholder="Category ID" >
												<option value="select">Select Category </option>
													<?php
													$sql_load="SELECT category_id, name FROM packagecategory ";
													$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
													while($row_load=mysqli_fetch_assoc($result_load))
													{
														echo'<option value="'.$row_load["category_id"].'">'.$row_load["name"].'</option>';
													}
													?>
											</select>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtsubcategoryid">Sub category ID</label>
											<?php
												$sql_generatedid="SELECT subcategory_id FROM packagesubcategory ORDER BY subcategory_id DESC LIMIT 1";
												$result_generatedid=mysqli_query($con,$sql_generatedid) or die("sql error in sql_generatedid ".mysqli_error($con));
												if(mysqli_num_rows($result_generatedid)==1)
												{// for  except from the first submission
													$row_generatedid=mysqli_fetch_assoc($result_generatedid);
													$generatedid=++$row_generatedid["subcategory_id"];
												}
												else
												{//For first time submission
													$generatedid="SCA01";
												}
											?>
											<input type="text" class="form-control" name="txtsubcategoryid" id="txtsubcategoryid" required placeholder="subcategory" value="<?php echo $generatedid;?>" readonly/>
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
											<input type="text" onkeypress="return isTextKey(event)" class="form-control" name="txtname" id="txtname" required placeholder=" Name"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											 <label for="txtimage">image</label>
											 <input type="text" class="form-control"  name="txtimage" id="txtimage" required placeholder="image"/>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- second row end -->
								
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=packagesubcategory.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
						<h4 class="card-title">Package sub category Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=packagesubcategory.php&option=add"><button class="btn btn-primary">Add Package sub category</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>Sub Category ID</th>
										<th>Name</th>
										<th>Category </th>
										<th>Image</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql_view="SELECT subcategory_id,name,category_id,image FROM packagesubcategory";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{
										$sql_category="SELECT name from packagecategory WHERE category_id='$row_view[category_id]'";
										$result_category=mysqli_query($con,$sql_category) or die("sql error in sql_category ".mysqli_error($con));
										$row_category=mysqli_fetch_assoc($result_category);
										echo '<tr>';
											echo '<td>'.$row_view["subcategory_id"].'</td>';
											echo '<td>'.$row_view["name"].'</td>';
											echo '<td>'.$row_category["name"].'</td>';
											echo '<td>'.$row_view["image"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=packagesubcategory.php&option=fullview&pk_subcategory_id='.$row_view["subcategory_id"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
												echo '<a href="index.php?page=packagesubcategory.php&option=edit&pk_subcategory_id='.$row_view["subcategory_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
												echo '<a onclick="return delete_confirm()" href="index.php?page=packagesubcategory.php&option=delete&pk_subcategory_id='.$row_view["subcategory_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
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
		$get_pk_subcategory_id=$_GET["pk_subcategory_id"];
		
		$sql_fullview="SELECT * FROM packagesubcategory WHERE subcategory_id='$get_pk_subcategory_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		$sql_category="SELECT name from packagecategory WHERE category_id='$row_fullview[category_id]'";
		$result_category=mysqli_query($con,$sql_category) or die("sql error in sql_category ".mysqli_error($con));
		$row_category=mysqli_fetch_assoc($result_category);
										
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Package sub category Full Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table  class="display table table-striped table-hover">
								<tr><th>Category ID</th><td><?php echo $row_category["name"]; ?></td></tr>
								<tr><th>Sub Category ID</th><td><?php echo $row_fullview["subcategory_id"]; ?></td></tr>
								<tr><th>Name</th><td><?php echo $row_fullview["name"]; ?></td></tr>
								<tr><th>Image</th><td><?php echo $row_fullview["image"]; ?></td></tr>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=packagesubcategory.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
											<a href="index.php?page=packagesubcategory.php&option=edit&pk_subcategory_id=<?php echo $row_fullview["subcategory_id"]; ?>"><button class="btn btn-info">Edit</button></a> 
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
		$get_pk_subcategory_id=$_GET["pk_subcategory_id"];
		
		$sql_edit="SELECT * FROM packagesubcategory WHERE subcategory_id='$get_pk_subcategory_id'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit ".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for package subcategory Edit</div>
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
											<label for="txtcategoryid">Category </label>
											<select class="form-control" name="txtcategoryid" id="txtcategoryid" required placeholder="Category ID" >
												<option value="select">Select Category </option>
													<?php
													$sql_load="SELECT category_id, name FROM packagecategory ";
													$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
													while($row_load=mysqli_fetch_assoc($result_load))
													{
														echo'<option value="'.$row_load["category_id"].'">'.$row_load["name"].'</option>';
													}
													?>
											</select>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtsubcategoryid">Sub category ID</label>
											<input type="text" class="form-control" name="txtsubcategoryid" id="txtsubcategoryid" required placeholder="subcategory" value="<?php echo $row_edit["subcategory_id"]; ?>" readonly />
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
											<input type="text" onkeypress="return isTextKey(event)" class="form-control" name="txtname" id="txtname" required placeholder=" Name" value="<?php echo $row_edit["name"]; ?>"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											 <label for="txtimage">image</label>
											 <input type="text" class="form-control"  name="txtimage" id="txtimage" required placeholder="image" value="<?php echo $row_edit["image"]; ?>"/>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- second row end -->
								
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=packagesubcategory.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
		$get_pk_subcategory_id=$_GET["pk_subcategory_id"];
		
		$sql_delete="DELETE FROM packagesubcategory WHERE subcategory_id='$get_pk_subcategory_id'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete)
		{
			echo '<script>alert("Successfully Deleted");
						window.location.href="index.php?page=packagesubcategory.php&option=view";</script>';

		}		
	}
}
?>
</body>