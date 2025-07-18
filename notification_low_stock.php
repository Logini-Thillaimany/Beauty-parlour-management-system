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
if($system_usertype=="Admin" || $system_usertype=="Clerk")//Notify New bookings to admin and clerk
{
include("connection.php");
?>
<body>
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
									$sql_view="SELECT product_id,name,minimumstock,expiretype FROM product WHERE expiretype='Yes'";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{
										$purchasebutton=0;
										$priceAvailable=0;
										$product_variable=' <font color="red">(Product not in use)</font>';

										$sql_checkprice="SELECT startdate from productprice WHERE product_id='$row_view[product_id]' AND enddate IS NULL";
										$result_checkprice=mysqli_query($con,$sql_checkprice) or die("sql error in sql_checkprice ".mysqli_error($con));
										if(mysqli_num_rows($result_checkprice)>0)
										{
											$priceAvailable=1;
											$product_variable="";
										}

										$sql_stock="SELECT * from stock WHERE product_id='$row_view[product_id]' AND quantity>0";
										$result_stock=mysqli_query($con,$sql_stock) or die("sql error in sql_stock ".mysqli_error($con));
										if(mysqli_num_rows($result_stock)>0)
										{
											$noTime=mysqli_num_rows($result_stock);
											$y=1;
											$total_quantity=0;
											while($row_stock=mysqli_fetch_assoc($result_stock))
											{
												$today=date("Y-m-d");
												$sql_expire="SELECT expiredate from purchaseproduct WHERE purchase_id='$row_stock[purchase_id]' AND product_id='$row_view[product_id]' AND expiredate>'$today'";
												$result_expire=mysqli_query($con,$sql_expire) or die("sql error in sql_expire ".mysqli_error($con));
												if(mysqli_num_rows($result_expire)>0)
												{
													$total_quantity=$total_quantity+$row_stock["quantity"];
													$this_quantity=$row_stock["quantity"];
												}
												else
												{
													$this_quantity=$row_stock["quantity"].' <font color="red">Expired</font>';
												}
                                            }	
                                                if($row_view["minimumstock"]>=$total_quantity)
                                                {
												echo '<tr>';
												if($y>1)
												{
													echo '<td>'.$x.'.'.$y.'</td>';
													echo '<td> </td>';
												}
												else
												{
													echo '<td>'.$x.'</td>';
													echo '<td>'.$row_view["name"].'</td>';
													
												}
													
													echo '<td>'.$row_stock["purchase_id"].'</td>';
													echo '<td>'.$this_quantity.'</td>';
												if($y==$noTime)
												{
													if($row_view["minimumstock"]>=$total_quantity)
													{
														$quantity='<font color="Red">'.$total_quantity.'<br> (This product stock is below minimum stock '.$row_view["minimumstock"].')</font>';
														$purchasebutton=1;
													}
													else
													{
														$quantity=$total_quantity;
													}
													echo '<td>'.$quantity.'<br>'.$product_variable.'</td>';
												}
												else
												{
													echo '<td> </td>';
													
												}
													 
													echo'<td>';
														//echo '<a href="index.php?page=productdispose.php&option=add&pk_product_id='.$row_view["product_id"].'&pk_purchase_id='.$row_stock["purchase_id"].'"><button class="btn btn-danger btn-sm me-md-2">Dispose</button></a> ';
														if($y==$noTime)
														{
															$x++;
															if($purchasebutton==1 && $priceAvailable==1)
															{
																echo '<a href="index.php?page=purchaseproduct.php&option=add"><button class="btn btn-warning btn-sm me-md-2">Make Purchase</button></a>';
															}
														}
													echo'</td>';
												echo '</tr>';
												$y++;
                                                    }
											
										}
										else
										{
											$purchasebutton=1;
											$quantity='<font color="Red"> the product is out of stock </font>'.$product_variable;									
											echo '<tr>';
												echo '<td>'.$x++.'</td>';
												echo '<td>'.$row_view["name"].'</td>';
												echo '<td>-</td>';
												echo '<td>-</td>';
												echo '<td>'.$quantity.'</td>';
												echo'<td>';
													if($purchasebutton==1 && $priceAvailable==1)
													{
														echo '<a href="index.php?page=purchaseproduct.php&option=add"><button class="btn btn-warning btn-sm">Make Purchase</button></a>';
													}
												echo'</td>';
											echo '</tr>';
										}
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>


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
                                                //$purchasebutton=1;											
											}	
										}
										if($purchasebutton==1)
                                        {
                                            echo '<tr>';
                                                echo '<td>'.$x++.'</td>';
                                                echo '<td>'.$row_view["name"].'</td>';
                                                echo '<td>'.$quantity.'</td>';
                                                echo'<td>';
                                                    if($purchasebutton==1)
                                                    {
                                                        echo '<a href="index.php?page=purchaseproduct.php&option=add"><button class="btn btn-primary">Make Purchase</button></a>';
                                                    }
                                                echo'</td>';
                                            echo '</tr>';
                                        }
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
</body>
<?php 
}
else{// other users redirect to index page
	echo '<script>window.location.href="index.php";</script>';
}
?>