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
	$sql_insert="INSERT INTO productprice(product_id,startdate,enddate,price,offer)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtproductid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtstartdate"])."',
									'".mysqli_real_escape_string($con,$_POST["txtenddate"])."',
									'".mysqli_real_escape_string($con,$_POST["txtprice"])."',
									'".mysqli_real_escape_string($con,$_POST["txtoffer"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
	if($result_insert)
	{
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=productprice.php&option=add";</script>';
	}
}
//insert code end

//update code start
if(isset($_POST["btnsavechanges"]))
{
	$sql_update="UPDATE productprice SET
							startdate='".mysqli_real_escape_string($con,$_POST["txtstartdate"])."',
							enddate='".mysqli_real_escape_string($con,$_POST["txtenddate"])."',
							price='".mysqli_real_escape_string($con,$_POST["txtprice"])."',
							offer='".mysqli_real_escape_string($con,$_POST["txtoffer"])."'
							WHERE product_id='".mysqli_real_escape_string($con,$_POST["txtproductid"])."'";
	$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
	if($result_update)
	{
		echo '<script>alert("Successfully Update");
						window.location.href="index.php?page=productprice.php&option=view";</script>';
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
						<div class="card-title"> Form for Add Product Price</div>
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
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtstartdate">Start date</label>
											<input type="date" class="form-control" name="txtstartdate" id="txtstartdate" required placeholder="Start date"/>
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
											<label for="txtenddate">End Date</label>
											<input type="date" class="form-control" name="txtenddate" id="txtenddate" required placeholder="End date"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtprice">Price</label>
											<input type="text" onkeypress="return isNumberKey(event)" class="form-control" name="txtprice" id="txtprice" required placeholder="Price .Rs"/>
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
											<label for="txtoffer">Offer</label>
											<input type="text" class="form-control" name="txtoffer" id="txtoffer" required placeholder="offer %"/>
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
												<a href="index.php?page=productprice.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
						<h4 class="card-title">productprice Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=productprice.php&option=add"><button class="btn btn-primary">Add productprice</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>Product</th>
										<th>Start date</th>
										<th>End date</th>
										<th>Price</th>
										<th>Offer</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql_view="SELECT product_id,startdate,enddate,price,offer FROM productprice";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{	$sql_product="SELECT name from product WHERE product_id='$row_view[product_id]'";
										$result_product=mysqli_query($con,$sql_product) or die("sql error in sql_product ".mysqli_error($con));
										$row_product=mysqli_fetch_assoc($result_product);
										
										echo '<tr>';
											echo '<td>'.$row_product["name"].'</td>';
											echo '<td>'.$row_view["startdate"].'</td>';
											echo '<td>'.$row_view["enddate"].'</td>';
											echo '<td>'.$row_view["price"].'</td>';
											echo '<td>'.$row_view["offer"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=productprice.php&option=fullview&pk_product_id='.$row_view["product_id"].'&pk_startdate='.$row_view["startdate"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
												echo '<a href="index.php?page=productprice.php&option=edit&pk_product_id='.$row_view["product_id"].'&pk_startdate='.$row_view["startdate"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
												echo '<a onclick="return delete_confirm()" href="index.php?page=productprice.php&option=delete&pk_product_id='.$row_view["product_id"].'&pk_startdate='.$row_view["startdate"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
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
		$get_pk_product_id=$_GET["pk_product_id"];
		$get_pk_startdate=$_GET["pk_startdate"];
		
		$sql_fullview="SELECT * FROM productprice WHERE product_id='$get_pk_product_id' AND startdate='$get_pk_startdate'";
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
						<h4 class="card-title">productprice  Full Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table  class="display table table-striped table-hover">
								<tr><th>Product</th><td><?php echo $row_product["name"]; ?></td></tr>
								<tr><th>Start date</th><td><?php echo $row_fullview["startdate"]; ?></td></tr>
								<tr><th>End date</th><td><?php echo $row_fullview["enddate"]; ?></td></tr>
								<tr><th>Price</th><td><?php echo $row_fullview["price"]; ?></td></tr>
								<tr><th>Offer</th><td><?php echo $row_fullview["offer"]; ?></td></tr>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=productprice.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
											<a href="index.php?page=productprice.php&option=edit&pk_product_id=<?php echo $row_fullview["product_id"]; ?>&pk_startdate=<?php echo $row_fullview["startdate"]; ?>"><button class="btn btn-info">Edit</button></a> 
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
		$get_pk_product_id=$_GET["pk_product_id"];
		$get_pk_startdate=$_GET["pk_startdate"];
		
		$sql_edit="SELECT * FROM productprice WHERE product_id='$get_pk_product_id' AND startdate='$get_pk_startdate'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit ".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Edit Product Price</div>
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
											<label for="txtproductid">Product ID</label>
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
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtstartdate">Start date</label>
											<input type="date" class="form-control" name="txtstartdate" id="txtstartdate" required placeholder="Start date" value="<?php echo $row_edit["startdate"]; ?>" readonly />
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
											<label for="txtenddate">End Date</label>
											<input type="date" class="form-control" name="txtenddate" id="txtenddate" required placeholder="End date" value="<?php echo $row_edit["enddate"]; ?>" />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtprice">Price</label>
											<input type="text" onkeypress="return isNumberKey(event)"  class="form-control" name="txtprice" id="txtprice" required placeholder="Price .Rs" value="<?php echo $row_edit["price"]; ?>" />
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
											<label for="txtoffer">Offer</label>
											<input type="text" class="form-control" name="txtoffer" id="txtoffer" required placeholder="offer %" value="<?php echo $row_edit["offer"]; ?>" />
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
												<a href="index.php?page=productprice.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
		$get_pk_product_id=$_GET["pk_product_id"];
		$get_pk_startdate=$_GET["pk_startdate"];
		
		$sql_delete="DELETE FROM productprice WHERE product_id='$get_pk_product_id' AND startdate='$get_pk_startdate'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete)
			{
				echo '<script>alert("Successfully Update");
						window.location.href="index.php?page=productprice.php&option=view";</script>';
			}
	}
}
?>
</body>