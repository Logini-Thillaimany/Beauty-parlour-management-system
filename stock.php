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
	$sql_insert="INSERT INTO stock(purchase_id,product_id,quantity)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtpurchaseid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtproductid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtquantity"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
	if($result_insert)
	{
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=stock.php&option=add";</script>';
	}
}
//insert code end
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
						<div class="card-title"> Form for stock Addition</div>
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
											<label for="txtpurchaseid">Purchase ID</label>
											<input type="text" class="form-control" name="txtpurchaseid" id="txtpurchaseid" required placeholder="Purchase ID"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtproductid">Product</label>
											<select class="form-control" name="txtproductid" id="txtproductid" required placeholder="Product">
												<option value="select">Select Product </option>
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
											<input type="number" onkeypress="return isNumberKey(event)"  class="form-control" name="txtquantity" id="txtquantity" required placeholder="Quantity"/>
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
												<a href="index.php?page=stock.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
						<h4 class="card-title">Stock Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>product</th>
										<th>purchase ID</th>										
										<th>quantity</th>
										<th>Total quantity</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$x=1;									
									$sql_view="SELECT product_id,name,minimumstock,expiretype FROM product WHERE expiretype='No'";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{
										$purchasebutton=0;
										$sql_checkprice="SELECT startdate from productprice WHERE product_id='$row_view[product_id]' AND enddate IS NULL";
										$result_checkprice=mysqli_query($con,$sql_checkprice) or die("sql error in sql_checkprice ".mysqli_error($con));
										if(mysqli_num_rows($result_checkprice)>0)
										{
											$sql_stock="SELECT quantity from stockne WHERE product_id='$row_view[product_id]'";
											$result_stock=mysqli_query($con,$sql_stock) or die("sql error in sql_stock ".mysqli_error($con));
											if(mysqli_num_rows($result_stock)>0)
											{
												$row_stock=mysqli_fetch_assoc($result_stock);
												if($row_view["minimumstock"]>=$row_stock["quantity"])
												{
													$quantity='<font color="Red">'.$row_stock["quantity"].' (This product stock is below minimum stock '.$row_view["minimumstock"].')</font>';
													$purchasebutton=1;
												}
												else
												{
													$quantity=$row_stock["quantity"];
												}												
											}
											else
											{
												$quantity='<font color="Red"> the product is still not purchase</font>';
												$purchasebutton=1;
											}
										}
										else
										{
											$quantity='<font color="Red">'.$row_stock["quantity"].' (The product not in use)</font>';											
										}
										echo '<tr>';
											echo '<td>'.$x++.'</td>';
											echo '<td>'.$row_view["name"].'</td>';
											echo '<td>'.$quantity.'</td>';
											echo'<td>';
												if(mysqli_num_rows($result_checkprice)>0 && mysqli_num_rows($result_stock)>0 && $row_stock["quantity"]>0)
												{
													echo '<a href="index.php?page=productdispose.php&option=addne&pk_product_id='.$row_view["product_id"].'"><button class="btn btn-primary">Dispose</button></a> ';
												}
												$sql_check_dispose="SELECT dispose_id from productdispose WHERE product_id='$row_view[product_id]'";
												$result_check_dispose=mysqli_query($con,$sql_check_dispose) or die("sql error in sql_check_dispose ".mysqli_error($con));
												if(mysqli_num_rows($result_check_dispose)>0)
												{
													echo '<a href="index.php?page=productdispose.php&option=view&pk_product_id='.$row_view["product_id"].'"><button class="btn btn-primary">View Dispose</button></a> ';
												}
												if($purchasebutton==1)
												{
													echo '<a href="index.php?page=purchaseproduct.php&option=add"><button class="btn btn-primary">Make Purchase</button></a>';
												}
											echo'</td>';
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
	}
	else if($_GET["option"]=="edit")
	{
		//no-edit form for this page
	}
	else if($_GET["option"]=="delete")
	{
		//no delete code
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