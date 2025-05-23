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
	for($x=1;$x<$totalLoop;$x++)
	{
		if(isset($_POST["txtproductid_".$x]))
		{
			$sql_insert="INSERT INTO packageproduct(package_id,product_id,status)
								VALUES('".mysqli_real_escape_string($con,$_POST["txtpackageid"])."',
										'".mysqli_real_escape_string($con,$_POST["txtproductid_".$x])."',
										'".mysqli_real_escape_string($con,"Active")."')";
			$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
		}
	}
	if($result_insert)
	{
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=package.php&option=fullview&pk_package_id='.$_POST["txtpackageid"].'";</script>';
	}
}
//insert code end

//update code start
if(isset($_POST["btnsavechanges"]))
{
	$sql_update="UPDATE packageproduct SET
							status='".mysqli_real_escape_string($con,$_POST["txtstatus"])."'
							WHERE package_id='".mysqli_real_escape_string($con,$_POST["txtpackageid"])."' AND
							product_id='".mysqli_real_escape_string($con,$_POST["txtproductid"])."'";
	$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
	if($result_update)
	{
		echo '<script>alert("Successfully Update");
						window.location.href="index.php?page=packageproduct.php&option=view";</script>';
	}
}
//update code end
?>
<body>
<script>
	function check_checkbox()
	{
		var totalLoop=document.getElementById("txtloop").value;
		for(var x=1;x<totalLoop; x++)
		{
			if(document.getElementById("txtproductid_"+x).checked==true)
			{
				return true;
			}
		}
		alert("Please select Atleat one product!");
		return false;
	}
	</script>
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
						<div class="card-title"> Form  to Add products for packages </div>
					</div>
					<div class="card-body">
						<div class="row">
							<!-- form start -->
							<form method="POST" action="" onsubmit="return check_checkbox()">
								
								<!-- one row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtpackageid">Package</label>
											<SELECT class="form-control" name="txtpackageid" id="txtpackageid" required placeholder="Package ID">
												<?php
												$sql_load_package="SELECT package_id, name FROM package  WHERE package_id='$get_pk_package_id'";
												$result_load_package=mysqli_query($con,$sql_load_package) or die("sql error in sql_load_package".mysqli_error($con));
												while($row_load_package=mysqli_fetch_assoc($result_load_package))
												{
													echo'<option value="'.$row_load_package["package_id"].'">'.$row_load_package["name"].'</option>';
												}
												?>
											</select>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- one row end -->

								<hr>Product Details<br>
								<div class="row">
									<div class="col-md-12 col-lg-12">
										<table  class="table">
									<?php
										$sql_view="SELECT product_id,name FROM product  WHERE product_id IN (SELECT product_id FROM productprice WHERE enddate IS NULL)";
										$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
										$totalproduct=mysqli_num_rows($result_view);
										$x=0;
										$y=1;
										echo '<tr>';
										while($row_view=mysqli_fetch_assoc($result_view))
										{
											$sql_check="SELECT product_id FROM packageproduct WHERE package_id= '$get_pk_package_id' AND product_id='$row_view[product_id]'";
											$result_check=mysqli_query($con,$sql_check) or die("sql error in sql_check ".mysqli_error($con));
											if(mysqli_num_rows($result_check)==0)
											{
												echo '<td>';
													echo '<input type="checkbox" name="txtproductid_'.$y.'" id="txtproductid_'.$y.'" value="'.$row_view["product_id"].'"> '.$row_view["name"];
												echo '</td>';

												$x++;
												$y++;
												if($x==$totalproduct){
													if($x%4==0){
														echo'</tr>';
													}
													else if($x%4==1){
														echo'<td></td><td></td><td></td></tr>';
													}
													else if($x%4==2){
														echo'<td></td><td></td></tr>';
													}
													else if($x%4==3){
														echo'<td></td></tr>';
													}
												}
												else
												{
													if($x%4==0){
														echo'</tr><tr>';
													}
												}
											}

										}
										echo '<input type="hidden" name="txtloop" id="txtloop" value="'.$y.'">';
										if($y==1){
											$submit_button="Disabled";
										}
										else{
											$submit_button="";										
										}
										?>
										</table>
									</div>
								</div>
								
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=package.php&option=fullview&pk_package_id=<?php echo $get_pk_package_id; ?>"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
												<input type="reset" class="btn btn-danger" name="btnclear" id="btnclear"  value="Clear"/>
												<input type="submit" class="btn btn-success" <?php echo $submit_button; ?> name="btnsave" id="btnsave"  value="Save"/>
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
						<h4 class="card-title">package product Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=packageproduct.php&option=add"><button class="btn btn-primary">Add package product</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>package</th>
										<th>product</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql_view="SELECT package_id,product_id,status FROM packageproduct";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{	
										$sql_enterby_package="SELECT name from package WHERE package_id='$row_view[package_id]'";
										$result_enterby_package=mysqli_query($con,$sql_enterby_package) or die("sql error in sql_enterby_package ".mysqli_error($con));
										$row_enterby_package=mysqli_fetch_assoc($result_enterby_package);
										
										$sql_enterby_product="SELECT name from product WHERE product_id='$row_view[product_id]'";
										$result_enterby_product=mysqli_query($con,$sql_enterby_product) or die("sql error in sql_enterby_product ".mysqli_error($con));
										$row_enterby_product=mysqli_fetch_assoc($result_enterby_product);
										
										echo '<tr>';
											echo '<td>'.$row_enterby_package["name"].'</td>';
											echo '<td>'.$row_enterby_product["name"].'</td>';
											echo '<td>'.$row_view["status"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=packageproduct.php&option=edit&pk_package_id='.$row_view["package_id"].'&pk_product_id='.$row_view["product_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
												echo '<a onclick="return delete_confirm()" href="index.php?page=packageproduct.php&option=delete&pk_package_id='.$row_view["package_id"].'&pk_product_id='.$row_view["product_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
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
		$get_pk_product_id=$_GET["pk_product_id"];
		
		$sql_fullview="SELECT * FROM packageproduct WHERE package_id='$get_pk_package_id' AND product_id='$get_pk_product_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		
		$sql_enterby_package="SELECT name from package WHERE package_id='$row_fullview[package_id]'";
		$result_enterby_package=mysqli_query($con,$sql_enterby_package) or die("sql error in sql_enterby_package ".mysqli_error($con));
		$row_enterby_package=mysqli_fetch_assoc($result_enterby_package);
										
		$sql_enterby_product="SELECT name from product WHERE product_id='$row_fullview[product_id]'";
		$result_enterby_product=mysqli_query($con,$sql_enterby_product) or die("sql error in sql_enterby_product ".mysqli_error($con));
		$row_enterby_product=mysqli_fetch_assoc($result_enterby_product);
										
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">package product Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table  class="display table table-striped table-hover">
								<tr><th>Package</th><td><?php echo $row_enterby_package["name"]; ?></td></tr>
								<tr><th>Product</th><td><?php echo $row_enterby_product["name"]; ?></td></tr>
								<tr><th>status</th><td><?php echo $row_fullview["status"]; ?></td></tr>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=packageproduct.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
											<a href="index.php?page=packageproduct.php&option=edit&pk_package_id=<?php echo $row_fullview["package_id"]; ?>&pk_product_id=<?php echo $row_fullview["product_id"]; ?>"><button class="btn btn-info">Edit</button></a> 
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
		$get_pk_product_id=$_GET["pk_product_id"];
		
		$sql_edit="SELECT * FROM packageproduct WHERE package_id='$get_pk_package_id' AND product_id='$get_pk_product_id'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit ".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form  to Edit products for packages </div>
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
											<SELECT class="form-control" name="txtpackageid" id="txtpackageid" required placeholder="Package ID">
												<option value="select">Select Package</option>
												<?php
												$sql_load_package="SELECT package_id, name FROM package ";
												$result_load_package=mysqli_query($con,$sql_load_package) or die("sql error in sql_load_package".mysqli_error($con));
												while($row_load_package=mysqli_fetch_assoc($result_load_package))
												{
													echo'<option value="'.$row_load_package["package_id"].'">'.$row_load_package["name"].'</option>';
												}
												?>
											</select>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtproductid">Product ID</label>
											<select class="form-control" name="txtproductid" id="txtproductid" required placeholder="Product ID">
												<option value="select">Select Product</option>
												<?php
												$sql_load_product="SELECT product_id, name FROM product ";
												$result_load_product=mysqli_query($con,$sql_load_product) or die("sql error in sql_load_product".mysqli_error($con));
												while($row_load_product=mysqli_fetch_assoc($result_load_product))
												{
													echo'<option value="'.$row_load_product["product_id"].'">'.$row_load_product["name"].'</option>';
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
											<label for="txtstatus">Status</label>
											<input type="text" class="form-control" name="txtstatus" id="txtstatus" required placeholder="Status" value="<?php echo $row_edit["status"]; ?>"/>
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
												<a href="index.php?page=packageproduct.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
		$get_pk_product_id=$_GET["pk_product_id"];
		
		$sql_delete="UPDATE  packageproduct SET status='Deleted' WHERE package_id='$get_pk_package_id' AND product_id='$get_pk_product_id'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete)
		{
			echo '<script>alert("Successfully Deleted");
						window.location.href="index.php?page=package.php&option=fullview&pk_package_id='.$get_pk_package_id.'";</script>';
		}		
	}
	else if($_GET["option"]=="reactivate")
	{
		//delete code
		$get_pk_package_id=$_GET["pk_package_id"];
		$get_pk_product_id=$_GET["pk_product_id"];
		
		$sql_delete="UPDATE  packageproduct SET status='Active' WHERE package_id='$get_pk_package_id' AND product_id='$get_pk_product_id'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete)
		{
			echo '<script>alert("Successfully Re-activate");
						window.location.href="index.php?page=package.php&option=fullview&pk_package_id='.$get_pk_package_id.'";</script>';
		}		
	}
}
?>
</body>