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
if($system_usertype=="Clerk" || $system_usertype=="Admin")
{
include("connection.php");

//insert code start
if(isset($_POST["btnsave"]))
{
	//insert into bookingsales table
	$sql_insert="INSERT INTO bookingsales(booking_id,product_id,quantity,price)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtbookingid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtproductid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtquantity"])."',
									'".mysqli_real_escape_string($con,$_POST["txtprice"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));

	//insert sucessfully
	if($result_insert)
	{
		if(!isset($_SESSION["session_sale_id"]))
		{
			$_SESSION["session_sale_id"]=$_POST["txtbookingid"];
		}
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=bookingsales.php&option=add";</script>';
	}
}
//insert code end

//update code start
if(isset($_POST["btnsavechanges"]))
{
	$sql_update="UPDATE bookingsales SET
							quantity='".mysqli_real_escape_string($con,$_POST["txtquantity"])."',
							price='".mysqli_real_escape_string($con,$_POST["txtprice"])."'
					  WHERE booking_id='".mysqli_real_escape_string($con,$_POST["txtbookingid"])."' AND
							product_id='".mysqli_real_escape_string($con,$_POST["txtproductid"])."'";
	$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
	if($result_update)
	{
		echo '<script>alert("Successfully update");
						window.location.href="index.php?page=bookingsales.php&option=add";</script>';
	}
}
//update code end
//insert booking code start
if(isset($_POST["btnfinish"]))
{
	//insert into booking table
	$status="Sale";
	$sql_insert_finish="INSERT INTO booking(booking_id,bookdate,customer_id,totalamount,status,booktype,enterby,servicedate)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtbookingid"])."',
							       '".mysqli_real_escape_string($con,$_POST["txtdate"])."',
									'".mysqli_real_escape_string($con,$_POST["txtcustomerid"])."',
									'".mysqli_real_escape_string($con,$_POST["txttotalamount"])."',
									'".mysqli_real_escape_string($con,$status)."',
									'".mysqli_real_escape_string($con,$_POST["txtbooktype"])."',
									'".mysqli_real_escape_string($con,$system_user_id)."',
									'".mysqli_real_escape_string($con,$_POST["txtdate"])."')";
	$result_insert_finish=mysqli_query($con,$sql_insert_finish) or die("sql error in sql_insert_finish ".mysqli_error($con));


	//insert sucessfully
	if($result_insert_finish)
	{
		unset($_SESSION["session_sale_id"]);
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=payment.php&option=sale&pk_booking_id='.$_POST["txtbookingid"].'";</script>';
	}
}
//insert code end
?>
<body>
<script>
function load_product_price()
{
	var productid=document.getElementById("txtproductid").value;
	document.getElementById("txtquantity").value="";
	document.getElementById("txtquantity").readOnly=true;
	document.getElementById("txtprice").value="";
	document.getElementById("txtprice").readOnly=true;

	if(productid!="")	
	{
		var xmlhttp=new XMLHttpRequest();
		xmlhttp.onreadystatechange=function()
		{
			if(this.readyState==4 && this.status==200)
			{
				var responsevalue = xmlhttp.responseText.trim();
				var response_array = responsevalue.split("****");
				document.getElementById("txtquantity").max=response_array[0];
				document.getElementById("txtquantity").readOnly=false;
				document.getElementById("txtprice").value=response_array[1];
			}
		};
		xmlhttp.open("GET", "ajaxpage.php?frompage=bookingsales_product&ajax_productid=" + productid, true);
		xmlhttp.send();
	}
}
</script>
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
						<div class="card-title"> Form for Booking sales Addition</div>
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
											<label for="txtbookingid">Sale ID</label>
											<?php
											if(isset($_SESSION["session_sale_id"]))
											{
												$generatedid=$_SESSION["session_sale_id"];
											}
											else{
												$sql_generatedid="SELECT booking_id FROM bookingsales ORDER BY booking_id DESC LIMIT 1";
												$result_generatedid=mysqli_query($con,$sql_generatedid) or die("sql error in sql_generatedid ".mysqli_error($con));
												if(mysqli_num_rows($result_generatedid)==1)
												{// for  except from the first submission
													$row_generatedid=mysqli_fetch_assoc($result_generatedid);
													$generatedid=++$row_generatedid["booking_id"];
												}
												else
												{//For first time submission
													$generatedid="BKS0000001";
												}
											}
											?>
											<input type="text" class="form-control" name="txtbookingid" id="txtbookingid" required placeholder="Booking ID" value="<?php echo $generatedid;?>" readonly />
											</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtproductid">Product</label>
											<select class="form-control" name="txtproductid" id="txtproductid" required placeholder="Package ID" onchange="load_product_price()">
												<option value="" disabled selected >Select Product</option>
													<?php
													$today = date("Y-m-d");
													$sql_load="SELECT product_id, name FROM product WHERE saletype='Sale' AND product_id IN (SELECT product_id FROM productprice WHERE enddate IS NULL)";
													$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
													while($row_load=mysqli_fetch_assoc($result_load))
													{
														$sql_check_stock="SELECT quantity FROM stock WHERE product_id='$row_load[product_id]' AND quantity>0 AND purchase_id IN (SELECT purchase_id FROM purchaseproduct WHERE expiredate>='$today' AND product_id='$row_load[product_id]')";
														$result_check_stock=mysqli_query($con,$sql_check_stock) or die("sql error in sql_load".mysqli_error($con));
														if(mysqli_num_rows($result_check_stock)>0)
														{
															if(isset($_SESSION["session_sale_id"]))
															{
																$sql_check_sale="SELECT * FROM bookingsales WHERE booking_id='".$_SESSION["session_sale_id"]."' AND product_id='$row_load[product_id]'";
																$result_check_sale=mysqli_query($con,$sql_check_sale) or die("sql error in sql_check_sale ".mysqli_error($con));
																if(mysqli_num_rows($result_check_sale)==0)
																{
																	echo'<option value="'.$row_load["product_id"].'">'.$row_load["name"].'</option>';
																}
															}
															else
															{
																echo'<option value="'.$row_load["product_id"].'">'.$row_load["name"].'</option>';
															}
															
														}
														
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
											<input type="number" readonly min="1" onkeypress="return isNumberKey(event)" class="form-control" name="txtquantity" id="txtquantity" required placeholder="Quantity"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtprice">Price</label>
											<input type="number" readonly class="form-control" name="txtprice" id="txtprice" required placeholder="price"/>
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
												<a href="index.php?page=bookingsales.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
		if(isset($_SESSION["session_sale_id"]))
		{
			?>
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">bookingsales Details</h4>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table id="basic-datatables" class="display table table-striped table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>Booking ID</th>
											<th>Product</th>
											<th>Quantity</th>
											<th>price</th>
											<th>Sub total</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$x=1;
										$total_amount=0;
										$sql_view="SELECT * FROM bookingsales WHERE booking_id='".$_SESSION["session_sale_id"]."'";
										$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
										while($row_view=mysqli_fetch_assoc($result_view))
										{	$sql_product="SELECT name from product WHERE product_id='$row_view[product_id]'";
											$result_product=mysqli_query($con,$sql_product) or die("sql error in sql_product".mysqli_error($con));
											$row_product=mysqli_fetch_assoc($result_product);

											$sub_total=$row_view["quantity"]*$row_view["price"];
											$total_amount+=$sub_total;
											
											echo '<tr>';
												echo '<td>'.$x++.'</td>';
												echo '<td>'.$row_view["booking_id"].'</td>';
												echo '<td>'.$row_product["name"].'</td>';
												echo '<td>'.$row_view["quantity"].'</td>';
												echo '<td>'.$row_view["price"].'</td>';
												echo '<td>'.$sub_total.'</td>';
												echo '<td>';
													echo '<a href="index.php?page=bookingsales.php&option=edit&pk_booking_id='.$row_view["booking_id"].'&pk_product_id='.$row_view["product_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
													echo '<a onclick="return delete_confirm()" href="index.php?page=bookingsales.php&option=delete&pk_booking_id='.$row_view["booking_id"].'&pk_product_id='.$row_view["product_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
												echo '</td>';
											echo '</tr>';
										}
										echo '<tr>';
												echo '<td>'.$x++.'</td>';
												echo '<td>Total Price</td>';
												echo '<td></td>';
												echo '<td></td>';
												echo '<td></td>';
												echo '<td>'.$total_amount.'</td>';
												echo '<td>';
													if($total_amount>0)
													{
														echo '<a href="index.php?page=bookingsales.php&option=finish&amount='.$total_amount.'"><button class="btn btn-success btn-sm"><i class="fa fa-check"></i> Finish</button></a> ';
													}
												echo '</td>';
											echo '</tr>';
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
						<h4 class="card-title">Sale Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=bookingsales.php&option=add"><button class="btn btn-primary">Add Sale</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>Booking ID</th>
										<th>Book date</th>
										<th>Customer </th>
										<th>Total amount</th>
										<th>Service date</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$x=1;
									$sql_view="SELECT booking_id,bookdate,customer_id,totalamount,servicedate FROM booking WHERE booktype='Sale' ORDER BY booking_id DESC";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{	
										$sql_customer="SELECT name from customer WHERE customer_id='$row_view[customer_id]'";
										$result_customer=mysqli_query($con,$sql_customer) or die("sql error in sql_customer ".mysqli_error($con));
										$row_customer=mysqli_fetch_assoc($result_customer);
										
										echo '<tr>';
											echo '<td>'.$x++.'</td>';
											echo '<td>'.$row_view["booking_id"].'</td>';
											echo '<td>'.$row_view["bookdate"].'</td>';
											echo '<td>'.$row_customer["name"].'</td>';
											echo '<td>'.$row_view["totalamount"].'</td>';
											echo '<td>'.$row_view["servicedate"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=bookingsales.php&option=fullview&pk_booking_id='.$row_view["booking_id"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
												//echo '<a href="index.php?page=booking.php&option=edit&pk_booking_id='.$row_view["booking_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
												//echo '<a onclick="return delete_confirm()" href="index.php?page=booking.php&option=delete&pk_booking_id='.$row_view["booking_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
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
		$get_pk_booking_id=$_GET["pk_booking_id"];
		
		$sql_fullview="SELECT * FROM booking WHERE booking_id='$get_pk_booking_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		$sql_customer="SELECT name from customer WHERE customer_id='$row_fullview[customer_id]'";
		$result_customer=mysqli_query($con,$sql_customer) or die("sql error in sql_customer ".mysqli_error($con));
		$row_customer=mysqli_fetch_assoc($result_customer);

		$sql_enterby="SELECT name from staff WHERE staff_id='$row_fullview[enterby]'";
		$result_enterby=mysqli_query($con,$sql_enterby) or die("sql error in sql_enterby ".mysqli_error($con));
		$row_enterby=mysqli_fetch_assoc($result_enterby);
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Sales fullview Details</h4>
						<?php
						if(isset($_GET["page"]))
						{
							if($system_usertype=="Admin" || $system_usertype=="Clerk")
							{
						?>
							<br/><a href="print.php?print=booking.php&option=fullview&pk_booking_id=<?php echo $row_fullview["booking_id"]; ?>" target="_blank"><button class="btn btn-success">Print</button></a>
						<?php
							}
						}
						?>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table  class="display table table-striped table-hover">
								<tr><th>Booking ID</th><td><?php echo $row_fullview["booking_id"]; ?></td></tr>
								<tr><th>Book date</th><td><?php echo $row_fullview["bookdate"]; ?></td></tr>
								<tr><th>Customer</th><td><?php echo $row_customer["name"]; ?></td></tr>
								<tr><th>Total amount</th><td><?php echo $row_fullview["totalamount"]; ?></td></tr>
								<tr><th>Book type</th><td><?php echo $row_fullview["booktype"] ;?></td></tr>
								<tr><th>Enter By</th><td><?php echo $row_enterby["name"]; ?></td></tr>
								<?php
								if(isset($_GET["page"]))
								{
								?>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=bookingsales.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
										</center>
									</td>
								</tr>
								<?php
								}
								?>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--Booking sales product details for the booking -->
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title"> sales Product Full Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table  class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>Booking ID</th>
										<th>Product </th>
										<th>Quantity</th>
										<th>Price</th>
										<th>Offer price</th>
										<th>Sub Total </th>
									</tr>
								</thead>
								<tbody>
									<?php
									$x=1;
									$subTotal=0;
									$total_amount=0;
									$sql_fullview_sale="SELECT * FROM bookingsales WHERE booking_id='$get_pk_booking_id'";
									$result_fullview_sale=mysqli_query($con,$sql_fullview_sale) or die("sql error in sql_fullview_sale ".mysqli_error($con));
									while($row_fullview_sale=mysqli_fetch_assoc($result_fullview_sale))
									{
										$sql_product="SELECT * from product WHERE product_id='$row_fullview_sale[product_id]'";
										$result_product=mysqli_query($con,$sql_product) or die("sql error in sql_product".mysqli_error($con));
										$row_product=mysqli_fetch_assoc($result_product);

										//get price ,offer from productprice table
										$sql_checkprice="SELECT price,offer from productprice WHERE product_id='$row_product[product_id]' AND enddate IS NULL";
										$result_checkprice=mysqli_query($con,$sql_checkprice) or die("sql error in sql_checkprice ".mysqli_error($con));
										$row_checkprice=mysqli_fetch_assoc($result_checkprice);
										//calculate unit price
										$unit_price=$row_checkprice["price"]*(1-$row_checkprice["offer"]/100);

										//calculate sub total
										$sub_total=$row_fullview_sale["quantity"]*$unit_price;
										echo '<tr>';
										echo '<td>'.$x++.'</td>';
										echo '<td>'.$get_pk_booking_id.'</td>';
										echo '<td>'.$row_product["name"].'</td>';
										echo '<td>'.$row_fullview_sale["quantity"].'</td>';
										echo '<td>'.$row_checkprice["price"].'</td>';
										echo '<td>'.$unit_price.'</td>';
										echo '<td>'.$sub_total.'</td>';
										echo '</tr>';
										$total_amount+=$sub_total;
									}
									if($x>1)
										{
											echo '<tr>';
												echo '<td>'.$x.'</td>';
												echo '<td>Total</td>';
												echo '<td></td>';
												echo '<td></td>';
												echo '<td></td>';
												echo '<td></td>';
												echo '<td>'.$total_amount.'</td>';
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
		<!--Payment details for the booking -->	
	<?php
	$sql_paied_amount= "SELECT SUM(payamount) AS total_pay FROM payment WHERE booking_id='$row_fullview[booking_id]' AND (paystatus='Paid' OR paystatus='Pending')";
	$result_paied_amount=mysqli_query($con,$sql_paied_amount) or die("sql error in sql_paied_amount ".mysqli_error($con));	
	$row_paied_amount=mysqli_fetch_assoc($result_paied_amount);
	?>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Payment Details</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<?php
						if(isset($_GET["page"]))
						{
							if($row_fullview["status"]=="Sale" && $row_paied_amount["total_pay"]<$total_amount)
							{
								//if the payment is not completed
								?>
								<a href="index.php?page=payment.php&option=sale&pk_booking_id=<?php echo $row_fullview["booking_id"] ; ?>"><button class="btn btn-primary">Add Payment</button></a><br><br>
								<?php
							}
						}
						?>
						<table id="basic-datatables" class="display table table-striped table-hover">
							<thead>
								<tr>
									<th>Payment ID</th>
									<th>Pay Date</th>
									<th>Pay Mode</th>
									<th>Pay Slip</th>
									<th>Pay Amount</th>
									<th>Status</th>
									<?php
									if(isset($_GET["page"]))
									{
										echo '<th>Action</th>';
									}
									?>
								</tr>
							</thead>
							<tbody>
								<?php
								$sql_view="SELECT * FROM payment WHERE booking_id='$row_fullview[booking_id]'";
								$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
								while($row_view=mysqli_fetch_assoc($result_view))
								{
									echo '<tr>';
										echo '<td>'.$row_view["payment_id"].'</td>';
										echo '<td>'.$row_view["paydate"].'</td>';
										echo '<td>'.$row_view["paymode"].'</td>';
										if($row_view["paymode"]=="Bank")
										{
											?>
											<td><img src="file/payment/<?php echo $row_view["slipphoto"].'?'.date("h:i:s"); ?>" onClick="popup_payslip('<?php echo $row_view["slipphoto"].'?'.date("h:i:s"); ?>')" data-bs-toggle="modal" data-bs-target="#system_popup" width="150" hight="150"></td>
											<?php
										}
										else
										{
											echo '<td></td>';
										}
										echo '<td>'.$row_view["payamount"].'</td>';
										echo '<td>'.$row_view["paystatus"].'</td>';
										
										if(isset($_GET["page"]))
										{
											echo '<td>';
											if(($system_usertype=="Admin" || $system_usertype=="Clerk") && $row_view["paystatus"]=="Pending")
											{
											
												echo '<a onclick="return accept_confirm()" href="index.php?page=payment.php&option=status&pk_payment_id='.$row_view["payment_id"].'&pk_booking_id='.$row_view["booking_id"].'&Status=Paid"><button class="btn btn-success btn-sm"><i class="fa fa-thumbs-up"></i> Accept</button></a> ';
												echo '<a onclick="return reject_confirm()" href="index.php?page=payment.php&option=status&pk_payment_id='.$row_view["payment_id"].'&pk_booking_id='.$row_view["booking_id"].'&Status=Reject"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Reject</button></a> ';
											
											}
											echo '</td>';
										}
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
	else if($_GET["option"]=="edit")
	{
		//edit form
		$get_pk_booking_id=$_GET["pk_booking_id"];
		$get_pk_product_id=$_GET["pk_product_id"];
		
		$sql_edit="SELECT * FROM bookingsales WHERE booking_id='$get_pk_booking_id' AND product_id='$get_pk_product_id'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit ".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Edit Booking sales </div>
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
											<label for="txtbookingid">Booking ID</label>
											<input type="text" class="form-control" readonly name="txtbookingid" id="txtbookingid" value="<?php echo $row_edit["booking_id"]; ?>" required placeholder="Booking ID">
												
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtproductid">Product</label>
											<select class="form-control" name="txtproductid" id="txtproductid" required placeholder="Package ID" value="<?php echo $row_edit["package_id"]; ?>" readonly >
												
													<?php
													$sql_load="SELECT product_id, name FROM product WHERE product_id='$row_edit[product_id]'";
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
											<?php
											$today = date("Y-m-d");
											$sql_max="SELECT sum(quantity) as totalquantity FROM stock WHERE product_id='$get_pk_product_id' AND purchase_id IN (SELECT purchase_id FROM purchaseproduct WHERE expiredate>='$today' AND product_id='$get_pk_product_id')";
											$result_max=mysqli_query($con,$sql_max) or die("sql error in sql_max ".mysqli_error($con));
											$row_max=mysqli_fetch_assoc($result_max);
											?>
											<input type="number" min="1" max="<?php echo $row_max["totalquantity"]; ?>" onkeypress="return isNumberKey(event)" class="form-control" name="txtquantity" id="txtquantity" required placeholder="Quantity" value="<?php echo $row_edit["quantity"]; ?>" />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtprice">Price</label>
											<input type="number" onkeypress="return isNumberKey(event)" class="form-control" name="txtprice" id="txtprice" required placeholder="price" readonly value="<?php echo $row_edit["price"]; ?>" />
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
												<a href="index.php?page=bookingsales.php&option=add"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
		$get_pk_booking_id=$_GET["pk_booking_id"];
		$get_pk_product_id=$_GET["pk_product_id"];
		
		$sql_delete="DELETE FROM bookingsales WHERE booking_id='$get_pk_booking_id' AND product_id='$get_pk_product_id'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete){
		echo '<script>alert("Successfully Deleted");
						window.location.href="index.php?page=bookingsales.php&option=add";</script>';
	
		}		
	}
	else if($_GET["option"]=="finish")
	{
		//add form
		$get_amount=$_GET["amount"];
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Booking sale Addition</div>
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
											<label for="txtbookingid">Booking ID</label>
											<?php
												$generatedid=$_SESSION["session_sale_id"];
											?>
											<input type="text" class="form-control" name="txtbookingid" id="txtbookingid" required placeholder="Booking ID" value="<?php echo $generatedid ;?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtcustomerid">Customer ID</label>
											<select class="form-control" name="txtcustomerid" id="txtcustomerid" required placeholder="Customer ID">
												<?php
													echo'<option value="" disabled selected>Select Customer </option>';
													$sql_load="SELECT customer_id, name FROM customer WHERE customer_id IN (SELECT user_id FROM login WHERE status='Active')";
													$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
													while($row_load=mysqli_fetch_assoc($result_load))
													{
														echo'<option value="'.$row_load["customer_id"].'">'.$row_load["name"].'</option>';
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
											<label for="txtdate">sales Date</label>
											<input type="date" class="form-control" name="txtdate" id="txtdate" readonly required value="<?php echo date("Y-m-d"); ?>" placeholder="Booking date"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">	
											<label for="txtbooktype">Book type</label>
											<input type="text" class="form-control" name="txtbooktype" id="txtbooktype" value="Sale"  readonly required placeholder="book type">
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- second row end -->
								
								<!-- third row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txttotalamount">Total amount</label>
											<input type="number" onkeypress="return isNumberKey(event)"  value="<?php echo $get_amount; ?>" readonly class="form-control" name="txttotalamount" id="txttotalamount" required placeholder="total amount"/>
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
												<a href="index.php?page=bookingsales.php&option=add"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
												<input type="reset" class="btn btn-danger" name="btnclear" id="btnclear"  value="Clear"/>
												<input type="submit" class="btn btn-success" name="btnfinish" id="btnfinish"  value="Save"/>
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
}
?>
</body>
<?php 
}
else{// other users redirect to index page
	echo '<script>window.location.href="index.php";</script>';
}
?>