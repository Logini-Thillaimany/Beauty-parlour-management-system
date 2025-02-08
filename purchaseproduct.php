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
	$sql_insert="INSERT INTO purchaseproduct(purchase_id,product_id,quantity,unitprice,expiredate,manufacturedate)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtpurchaseid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtproductid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtquantity"])."',
									'".mysqli_real_escape_string($con,$_POST["txtunitprice"])."',
									'".mysqli_real_escape_string($con,$_POST["txtexpiredate"])."',
									'".mysqli_real_escape_string($con,$_POST["txtmanufactordate"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
	if($result_insert)
	{
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=purchaseproduct.php&option=add";</script>';
	}
}
//insert code end

//delete code start
if(isset($_POST["btnsavechanges"]))
{
	$sql_delete="UPDATE purchaseproduct SET
									quantity='".mysqli_real_escape_string($con,$_POST["txtquantity"])."',
									unitprice='".mysqli_real_escape_string($con,$_POST["txtunitprice"])."',
									expiredate='".mysqli_real_escape_string($con,$_POST["txtexpiredate"])."',
									manufacturedate='".mysqli_real_escape_string($con,$_POST["txtmanufactordate"])."'
							  WHERE purchase_id='".mysqli_real_escape_string($con,$_POST["txtpurchaseid"])."' AND 
									product_id='".mysqli_real_escape_string($con,$_POST["txtproductid"])."'";
	$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
	if($result_delete)
	{
		echo '<script>alert("Successfully Delete");
						window.location.href="index.php?page=purchaseproduct.php&option=view";</script>';
	}
}
//delete code end
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
						<div class="card-title"> Form for Add Purchase Product </div>
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
											<label for="txtpurchaseid">Purchase</label>
											<select class="form-control" name="txtpurchaseid" id="txtpurchaseid" required placeholder="Purchase ID">
											<option value="select">Select Purchase </option>
												<?php
												$sql_load_purchase="SELECT purchase_id FROM purchase ";
												$result_load_purchase=mysqli_query($con,$sql_load_purchase) or die("sql error in sql_load_purchase".mysqli_error($con));
												while($row_load_purchase=mysqli_fetch_assoc($result_load_purchase))
												{
													echo'<option value="'.$row_load_purchase["purchase_id"].'">'.$row_load_purchase["purchase_id"].'</option>';
												}
												?>
											</select>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtproductid">Product</label>
											<select class="form-control" name="txtproductid" id="txtproductid" required placeholder="Product">
											<option value="select">Select Product </option>
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
											<label for="txtquantity">Quantity</label>
											<input type="number" onkeypress="return isNumberKey(event)" class="form-control" name="txtquantity" id="txtquantity" required placeholder="Quantity"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtunitprice">Unit price</label>
											<input type="text" onkeypress="return isNumberKey(event)" class="form-control" name="txtunitprice" id="txtunitprice" required placeholder="unit price"/>
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
											<label for="txtexpiredate">Expire date</label>
											<input type="date" class="form-control" name="txtexpiredate" id="txtexpiredate" required placeholder="expiredate"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtmanufactordate">Manufactor date</label>
											<input type="date" class="form-control" name="txtmanufactordate" id="txtmanufactordate" required placeholder="Manufactor date">
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
												<a href="index.php?page=purchaseproduct.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
						<h4 class="card-title">purchase product Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=purchaseproduct.php&option=add"><button class="btn btn-primary">Add purchase product</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>Purchase</th>
										<th>Product</th>
										<th>Quantity</th>
										<th>Unit price</th>
										<th>Expire date</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql_view="SELECT purchase_id,product_id,quantity,unitprice,expiredate FROM purchaseproduct";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{
										$sql_enterby_product="SELECT name from product WHERE product_id='$row_view[product_id]'";
										$result_enterby_product=mysqli_query($con,$sql_enterby_product) or die("sql error in sql_enterby_product ".mysqli_error($con));
										$row_enterby_product=mysqli_fetch_assoc($result_enterby_product);
										
										echo '<tr>';
											echo '<td>'.$row_view["purchase_id"].'</td>';
											echo '<td>'.$row_enterby_product["name"].'</td>';
											echo '<td>'.$row_view["quantity"].'</td>';
											echo '<td>'.$row_view["unitprice"].'</td>';
											echo '<td>'.$row_view["expiredate"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=purchaseproduct.php&option=fullview&pk_purchase_id='.$row_view["purchase_id"].'&pk_product_id='.$row_view["product_id"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
												echo '<a href="index.php?page=purchaseproduct.php&option=edit&pk_purchase_id='.$row_view["purchase_id"].'&pk_product_id='.$row_view["product_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
												echo '<a onclick="return delete_confirm()" href="index.php?page=purchaseproduct.php&option=delete&pk_purchase_id='.$row_view["purchase_id"].'&pk_product_id='.$row_view["product_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
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
		$get_pk_purchase_id=$_GET["pk_purchase_id"];
		$get_pk_product_id=$_GET["pk_product_id"];
		
		$sql_fullview="SELECT * FROM purchaseproduct WHERE purchase_id='$get_pk_purchase_id' AND product_id='$get_pk_product_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		$sql_enterby_product="SELECT name from product WHERE product_id='$row_fullview[product_id]'";
		$result_enterby_product=mysqli_query($con,$sql_enterby_product) or die("sql error in sql_enterby_product ".mysqli_error($con));
		$row_enterby_product=mysqli_fetch_assoc($result_enterby_product);
										
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">purchase product Full Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="display table table-striped table-hover">
								<tr><th>Purchase</th><td><?php echo $row_fullview["purchase_id"]; ?></td></tr>
								<tr><th>Product</th><td><?php echo $row_enterby_product["name"]; ?></td></tr>
								<tr><th>Quantity</th><td><?php echo $row_fullview["quantity"]; ?></td></tr>
								<tr><th>Unit price</th><td><?php echo $row_fullview["unitprice"]; ?></td></tr>
								<tr><th>Expire date</th><td><?php echo $row_fullview["expiredate"]; ?></td></tr>
								<tr><th>Manufacture date</th><td><?php echo $row_fullview["manufacturedate"]; ?></td></tr>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=purchaseproduct.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
											<a href="index.php?page=purchaseproduct.php&option=edit&pk_purchase_id=<?php echo $row_fullview["purchase_id"]; ?>&pk_product_id=<?php echo $row_fullview["product_id"]; ?>"><button class="btn btn-info">Edit</button></a> 
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
		$get_pk_purchase_id=$_GET["pk_purchase_id"];
		$get_pk_product_id=$_GET["pk_product_id"];
		
		$sql_edit="SELECT * FROM purchaseproduct WHERE purchase_id='$get_pk_purchase_id' AND product_id='$get_pk_product_id'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit ".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Edit Purchase Product </div>
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
											<label for="txtpurchaseid">Purchase</label>
											<select class="form-control" name="txtpurchaseid" id="txtpurchaseid" required placeholder="Purchase ID">
											<option value="select">Select Purchase </option>
												<?php
												$sql_load_purchase="SELECT purchase_id FROM purchase ";
												$result_load_purchase=mysqli_query($con,$sql_load_purchase) or die("sql error in sql_load_purchase".mysqli_error($con));
												while($row_load_purchase=mysqli_fetch_assoc($result_load_purchase))
												{
													echo'<option value="'.$row_load_purchase["purchase_id"].'">'.$row_load_purchase["purchase_id"].'</option>';
												}
												?>
											</select>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtproductid">Product ID</label>
											<select class="form-control" name="txtproductid" id="txtproductid" required placeholder="Product">
											<option value="select">Select Product </option>
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
											<label for="txtquantity">Quantity</label>
											<input type="number" onkeypress="return isNumberKey(event)" class="form-control" name="txtquantity" id="txtquantity" required placeholder="Quantity" value="<?php echo $row_edit["quantity"]; ?>" />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtunitprice">Unit price</label>
											<input type="text" onkeypress="return isNumberKey(event)" class="form-control" name="txtunitprice" id="txtunitprice" required placeholder="unit price" value="<?php echo $row_edit["unitprice"]; ?>" />
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
											<label for="txtexpiredate">Expire date</label>
											<input type="date" class="form-control" name="txtexpiredate" id="txtexpiredate" required placeholder="expiredate" value="<?php echo $row_edit["expiredate"]; ?>" />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtmanufactordate">Manufactor date</label>
											<input type="date" class="form-control" name="txtmanufactordate" id="txtmanufactordate" required placeholder="Manufactor date" value="<?php echo $row_edit["manufacturedate"]; ?>" />
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
												<a href="index.php?page=purchaseproduct.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
		$get_pk_purchase_id=$_GET["pk_purchase_id"];
		$get_pk_product_id=$_GET["pk_product_id"];
		
		$sql_delete="DELETE FROM purchaseproduct WHERE purchase_id='$get_pk_purchase_id' AND product_id='$get_pk_product_id'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete)
		{
			echo '<script>alert("Successfully Deleted");
						window.location.href="index.php?page=purchaseproduct.php&option=view";</script>';
		}		
	}
}
?>
</body>