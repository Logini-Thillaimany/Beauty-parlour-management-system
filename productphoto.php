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
	$target_dir = "file/product/";
	$target_file = $target_dir . basename($_FILES["txtimage"]["name"]);
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg")
	{
		echo '<script>alert("Sorry, only JPG, JPEG & PNG  files are allowed.");</script>';
	}
	else
	{
		$filename=$_POST["txtproductphotoid"].".".$imageFileType;
		$fileupload=$target_dir . $filename;
		move_uploaded_file($_FILES["txtimage"]["tmp_name"], $fileupload);
		$sql_insert="INSERT INTO productphoto(productphoto_id,photo,product_id)
								VALUES('".mysqli_real_escape_string($con,$_POST["txtproductphotoid"])."',
										'".mysqli_real_escape_string($con,$filename)."',
										'".mysqli_real_escape_string($con,$_POST["txtproductid"])."')";
		$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
		if($result_insert)
		{
			echo '<script>alert("Successfully Insert");
							window.location.href="index.php?page=productphoto.php&option=add&pk_product_id='.$_POST["txtproductid"].'";</script>';
		}
	}
}
//insert code end

//update code start
if(isset($_POST["btnsavechanges"]))
{
	$sql_update="UPDATE productphoto SET
							photo='".mysqli_real_escape_string($con,$_POST["txtimage"])."',
							product_id='".mysqli_real_escape_string($con,$_POST["txtproductid"])."'
							WHERE productphoto_id='".mysqli_real_escape_string($con,$_POST["txtproductphotoid"])."'";
	$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
	if($result_update)
	{
		echo '<script>alert("Successfully Update");
						window.location.href="index.php?page=productphoto.php&option=view";</script>';
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
		$get_pk_product_id=$_GET["pk_product_id"];
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Add product photo</div>
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
											<label for="txtproductphotoid">Product Photo ID</label>
											<?php
												$sql_generatedid="SELECT productphoto_id FROM productphoto ORDER BY productphoto_id DESC LIMIT 1";
												$result_generatedid=mysqli_query($con,$sql_generatedid) or die("sql error in sql_generatedid ".mysqli_error($con));
												if(mysqli_num_rows($result_generatedid)==1)
												{// for  except from the first submission
													$row_generatedid=mysqli_fetch_assoc($result_generatedid);
													$generatedid=++$row_generatedid["productphoto_id"];
												}
												else
												{//For first time submission
													$generatedid="PPR00001";
												}
											?>
											<input type="text" class="form-control" name="txtproductphotoid" id="txtproductphotoid" required placeholder="Product photo ID" value="<?php echo $generatedid;?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtproductid">Product</label>
											<select class="form-control" name="txtproductid" id="txtproductid" required placeholder="Product ID">
												<?php
												$sql_load="SELECT product_id, name FROM product WHERE product_id='$get_pk_product_id'";
												$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
												while($row_load=mysqli_fetch_assoc($result_load))
												{
													echo'<option value="'.$row_load["product_id"].'">'.$row_load["name"].'</option>';
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
											<label for="txtimage">image</label>
											<input type="file" class="form-control"  name="txtimage" id="txtimage" required placeholder="image"/>
											<font color="#B31B1B"> *Only the impage types can be upload (eg: .jpg, .jpeg, .png)</font>
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
												<a href="index.php?page=product.php&option=fullview&pk_product_id=<?php echo $get_pk_product_id; ?>">
													<input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
						<h4 class="card-title">Product photo Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=productphoto.php&option=add"><button class="btn btn-primary">Add Product photo</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>Product photo ID</th>
										<th>photo</th>
										<th>product</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql_view="SELECT productphoto_id,photo,product_id FROM productphoto";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{	$sql_product="SELECT name from product WHERE product_id='$row_view[product_id]'";
										$result_product=mysqli_query($con,$sql_product) or die("sql error in sql_product".mysqli_error($con));
										$row_product=mysqli_fetch_assoc($result_product);
										echo '<tr>';
											echo '<td>'.$row_view["productphoto_id"].'</td>';
											echo '<td>'.$row_view["photo"].'</td>';
											echo '<td>'.$row_product["name"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=productphoto.php&option=fullview&pk_productphoto_id='.$row_view["productphoto_id"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
												echo '<a href="index.php?page=productphoto.php&option=edit&pk_productphoto_id='.$row_view["productphoto_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
												echo '<a onclick="return delete_confirm()" href="index.php?page=productphoto.php&option=delete&pk_productphoto_id='.$row_view["productphoto_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
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
		$get_pk_productphoto_id=$_GET["pk_productphoto_id"];
		
		$sql_fullview="SELECT * FROM productphoto WHERE productphoto_id='$get_pk_productphoto_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		$sql_product="SELECT name from product WHERE product_id='$row_fullview[product_id]'";
		$result_product=mysqli_query($con,$sql_product) or die("sql error in sql_product".mysqli_error($con));
		$row_product=mysqli_fetch_assoc($result_product);
										
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Product photo Full Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="display table table-striped table-hover">
								<tr><th>Product photo ID</th><td><?php echo $row_fullview["productphoto_id"]; ?></td></tr>
								<tr><th>photo</th><td><?php echo $row_fullview["photo"]; ?></td></tr>
								<tr><th>product</th><td><?php echo $row_product["name"]; ?></td></tr>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=productphoto.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
											<a href="index.php?page=productphoto.php&option=edit&pk_productphoto_id=<?php echo $row_fullview["productphoto_id"]; ?>"><button class="btn btn-info">Edit</button></a> 
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
		$get_pk_productphoto_id=$_GET["pk_productphoto_id"];
		
		$sql_edit="SELECT * FROM productphoto WHERE productphoto_id='$get_pk_productphoto_id'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit ".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Edit product photo</div>
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
											<label for="txtproductphotoid">Product Photo ID</label>
											<input type="text" class="form-control" name="txtproductphotoid" id="txtproductphotoid" required placeholder="Product photo ID" value="<?php echo $row_edit["productphoto_id"]; ?>" />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtproductid">Product</label>
											<select class="form-control" name="txtproductid" id="txtproductid" required placeholder="Product ID">
												<option value="select">Select Product</option>
												<?php
												$sql_load="SELECT product_id, name FROM product ";
												$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
												while($row_load=mysqli_fetch_assoc($result_load))
												{
													echo'<option value="'.$row_load["product_id"].'">'.$row_load["name"].'</option>';
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
											<label for="txtimage">image</label>
											<input type="text" class="form-control"  name="txtimage" id="txtimage" required placeholder="image" value="<?php echo $row_edit["photo"]; ?>"/>
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
												<a href="index.php?page=productphoto.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
		$get_pk_productphoto_id=$_GET["pk_productphoto_id"];

		$sql_details="SELECT * FROM productphoto WHERE productphoto_id='$get_pk_productphoto_id'";
		$result_details=mysqli_query($con,$sql_details) or die("sql error in sql_details ".mysqli_error($con));
		$row_details=mysqli_fetch_assoc($result_details);

		unlink("file/product/".$row_details["photo"]);

		
		$sql_delete="DELETE FROM productphoto WHERE productphoto_id='$get_pk_productphoto_id'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete)
		{
					echo '<script>alert("Successfully delete");
					window.location.href="index.php?page=product.php&option=fullview&pk_product_id='.$row_details["product_id"].'";</script>';
		}
		
		
	}
}
?>
</body>