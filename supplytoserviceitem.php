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
if($system_usertype=="Admin" || $system_usertype=="Clerk")
{//allow these users to access this page 
include("connection.php");
//insert code start
if(isset($_POST["btnsave"]))
{
	$sql_insert="INSERT INTO suppltoserviceitem(supply_id,product_id,quantity)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtsupplyid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtproductid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtquantity"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
	if($result_insert)
	{
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=supplytoserviceitem.php&option=add";</script>';
	}
}
//insert code end

//update code start
if(isset($_POST["btnsavechanges"]))
{
	$sql_update="UPDATE suppltoserviceitem SET
							quantity='".mysqli_real_escape_string($con,$_POST["txtquantity"])."'
					  WHERE supply_id='".mysqli_real_escape_string($con,$_POST["txtsupplyid"])."' AND
							product_id='".mysqli_real_escape_string($con,$_POST["txtproductid"])."'";
	$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
	if($result_update)
	{
		echo '<script>alert("Successfully Update");
						window.location.href="index.php?page=supplytoserviceitem.php&option=view";</script>';
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
						<div class="card-title"> Form for Supply to Service item Addition</div>
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
											<label for="txtsupplyid">Supply ID</label>
											<?php
											if(isset($_SESSION["session_supply"]))
											{
												$generatedid=$_SESSION["session_supply"];
											}
											else{
											
												$sql_generatedid="SELECT supply_id FROM supplytoservice  ORDER BY supply_id  DESC LIMIT 1 ";
												$result_generatedid=mysqli_query($con,$sql_generatedid) or die("sql error in sql_generatedid".mysqli_error($con));
												if(mysqli_num_rows($result_generatedid)==1)
												{// for  except from the first submission
													$row_generatedid=mysqli_fetch_assoc($result_generatedid);
													$generatedid=++$row_generatedid["supply_id"];
												}
												else
												{//For first time submission
													$generatedid="SUB0000001";
												}
											}
											?>
											<input type="text" class="form-control" name="txtsupplyid" id="txtsupplyid" required placeholder="Supply ID" value="<?php echo $generatedid;?>" readonly />

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
											<label for="txtquantity">Quantity</label>
											<input type="number" onkeypress="return isNumberKey(event)" class="form-control" name="txtquantity" id="txtquantity" required placeholder="NO of Product"/>
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
												<a href="index.php?page=supplytoserviceitem.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
		//supply service product details
		if(isset($_SESSION["session_supply"])){
			?>
			<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Supply to Service item Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=supplytoserviceitem.php&option=add"><button class="btn btn-primary">Add Supply to Service item</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>supply ID</th>
										<th>product ID</th>
										<th>quantity</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql_view="SELECT supply_id,product_id,quantity FROM suppltoserviceitem";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{ 	
										$sql_product="SELECT name from product WHERE product_id='$row_view[product_id]'";
										$result_product=mysqli_query($con,$sql_product) or die("sql error in sql_product ".mysqli_error($con));
										$row_product=mysqli_fetch_assoc($result_product);
										
										echo '<tr>';
											echo '<td>'.$row_view["supply_id"].'</td>';
											echo '<td>'.$row_product["name"].'</td>';
											echo '<td>'.$row_view["quantity"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=supplytoserviceitem.php&option=edit&pk_supply_id='.$row_view["supply_id"].'&pk_product_id='.$row_view["product_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
												echo '<a onclick="return delete_confirm()" href="index.php?page=supplytoserviceitem.php&option=delete&pk_supply_id='.$row_view["supply_id"].'&pk_product_id='.$row_view["product_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
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
	}
	else if($_GET["option"]=="view")
	{
		//view table
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Supply to Service item Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=supplytoserviceitem.php&option=add"><button class="btn btn-primary">Add Supply to Service item</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>supply ID</th>
										<th>product ID</th>
										<th>quantity</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql_view="SELECT supply_id,product_id,quantity FROM suppltoserviceitem";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{ 	
										$sql_product="SELECT name from product WHERE product_id='$row_view[product_id]'";
										$result_product=mysqli_query($con,$sql_product) or die("sql error in sql_product ".mysqli_error($con));
										$row_product=mysqli_fetch_assoc($result_product);
										
										echo '<tr>';
											echo '<td>'.$row_view["supply_id"].'</td>';
											echo '<td>'.$row_product["name"].'</td>';
											echo '<td>'.$row_view["quantity"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=supplytoserviceitem.php&option=edit&pk_supply_id='.$row_view["supply_id"].'&pk_product_id='.$row_view["product_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
												echo '<a onclick="return delete_confirm()" href="index.php?page=supplytoserviceitem.php&option=delete&pk_supply_id='.$row_view["supply_id"].'&pk_product_id='.$row_view["product_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
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
		$get_pk_supply_id=$_GET["pk_supply_id"];
		$get_pk_product_id=$_GET["pk_product_id"];
		
		$sql_fullview="SELECT * FROM suppltoserviceitem WHERE supply_id='$get_pk_supply_id' AND product_id='$get_pk_product_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		$sql_product="SELECT name from product WHERE product_id='$row_fullview[product_id]'";
		$result_product=mysqli_query($con,$sql_product) or die("sql error in sql_product ".mysqli_error($con));
		$row_product=mysqli_fetch_assoc($result_product);
										
										
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Supply to Service item Full Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=supplytoserviceitem.php&option=add"><button class="btn btn-primary">Add Supply to Service item</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<tr><th>Supply Id</th><td><?php echo $row_fullview["supply_id"]; ?></td></tr>
								<tr><th>Product</th><td><?php echo $row_product["name"]; ?></td></tr>
								<tr><th>Quantity</th><td><?php echo $row_fullview["quantity"]; ?></td></tr>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=supplytoserviceitem.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
											<a href="index.php?page=supplytoserviceitem.php&option=edit&pk_supply_id=<?php echo $row_fullview["supply_id"]; ?>&pk_product_id=<?php echo $row_fullview["product_id"]; ?>"><button class="btn btn-info">Edit</button></a> 
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
		$get_pk_supply_id=$_GET["pk_supply_id"];
		$get_pk_product_id=$_GET["pk_product_id"];
		
		$sql_edit="SELECT * FROM suppltoserviceitem WHERE supply_id='$get_pk_supply_id' AND product_id='$get_pk_product_id'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit ".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Supply to Service item Edit</div>
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
											<label for="txtsupplyid">Supply ID</label>
											<select class="form-control" name="txtsupplyid" id="txtsupplyid" required placeholder="Supply ID">
												<option value="select"><?php echo $row_edit["supply_id"]; ?></option>
												<?php
												$sql_loadsupply="SELECT supply_id FROM supplytoservice ";
												$result_loadsupply=mysqli_query($con,$sql_loadsupply) or die("sql error in sql_loadsupply".mysqli_error($con));
												while($row_loadsupply=mysqli_fetch_assoc($result_loadsupply))
												{
													echo'<option value="'.$row_loadsupply["supply_id"].'">'.$row_loadsupply["supply_id"].'</option>';
												}
												?>
											</select>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtproductid">Product</label>
											<select class="form-control" name="txtproductid" id="txtproductid" required placeholder="Product ID">
												<option value="select"><?php echo $row_edit["product_id"]; ?></option>
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
											<label for="txtquantity">Quantity</label>
											<input type="number" onkeypress="return isNumberKey(event)"  class="form-control" name="txtquantity" id="txtquantity" required placeholder="NO of Product" value="<?php echo $row_edit["quantity"]; ?>" />
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
												<a href="index.php?page=supplytoserviceitem.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
		$get_pk_supply_id=$_GET["pk_supply_id"];
		$get_pk_product_id=$_GET["pk_product_id"];
		
		$sql_delete="DELETE FROM suppltoserviceitem WHERE supply_id='$get_pk_supply_id' AND product_id='$get_pk_product_id'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete)
		{
				echo '<script>alert("Successfully Delete");
						window.location.href="index.php?page=supplytoserviceitem.php&option=view";</script>';
		}		
	}
}
?>
</body>
<?php 
}
else{// other users redirect to index page
	echo '<script>window.location.href="index.php";</script>';
}
?>