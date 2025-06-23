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
?>
<body>
<?php
if(isset($_GET["option"]))
{
	if($_GET["option"]=="view")
	{
		//view table
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Stock Not Expired Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>Product</th>
										<th>quantity</th>
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
										
										$sql_stock="SELECT quantity from stockne WHERE product_id='$row_view[product_id]'";
										$result_stock=mysqli_query($con,$sql_stock) or die("sql error in sql_stock ".mysqli_error($con));
										if(mysqli_num_rows($result_stock)>0)
										{
											$row_stock=mysqli_fetch_assoc($result_stock);
											$sql_checkprice="SELECT startdate from productprice WHERE product_id='$row_view[product_id]' AND enddate IS NULL";
											$result_checkprice=mysqli_query($con,$sql_checkprice) or die("sql error in sql_checkprice ".mysqli_error($con));
											if(mysqli_num_rows($result_checkprice)>0)
											{
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
												$quantity='<font color="Red">'.$row_stock["quantity"].' (The product not in use)</font>';											
											}												
										}
										else
										{
											$sql_checkprice="SELECT startdate from productprice WHERE product_id='$row_view[product_id]' AND enddate IS NULL";
											$result_checkprice=mysqli_query($con,$sql_checkprice) or die("sql error in sql_checkprice ".mysqli_error($con));
											if(mysqli_num_rows($result_checkprice)>0)
											{
												$quantity='<font color="Red"> the product is still not purchase</font>';
												$purchasebutton=1;
											}
											else
											{
												$quantity='<font color="Red">The product not in use</font>';											
											}	
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
}
?>
</body>
<?php 
}
else{// other users redirect to index page
	echo '<script>window.location.href="index.php";</script>';
}
?>