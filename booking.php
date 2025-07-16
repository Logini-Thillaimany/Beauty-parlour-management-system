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
if($system_usertype!="Guest")
{
include("connection.php");

//insert code start
if(isset($_POST["btnsave"]))
{
	if($system_usertype=="Customer")
	{
		$status="Pending";
	}
	else
	{
		$status ="Accept";
	}

	$sql_insert="INSERT INTO booking(booking_id,bookdate,customer_id,totalamount,status,booktype,enterby,servicedate)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtbookingid"])."',
							       '".mysqli_real_escape_string($con,$_POST["txtdate"])."',
									'".mysqli_real_escape_string($con,$_POST["txtcustomerid"])."',
									'".mysqli_real_escape_string($con,$_POST["txttotalamount"])."',
									'".mysqli_real_escape_string($con,$status)."',
									'".mysqli_real_escape_string($con,$_POST["txtbooktype"])."',
									'".mysqli_real_escape_string($con,$system_user_id)."',
									'".mysqli_real_escape_string($con,$_POST["txtservicedate"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));

	$starttime=$_POST["txtservicetime"];
	
	$sql_load_bookingpackage="SELECT * FROM bookingpackage WHERE booking_id='$_POST[txtbookingid]'";
	$result_load_bookingpackage=mysqli_query($con,$sql_load_bookingpackage) or die("sql error in sql_load_bookingpackage ".mysqli_error($con));
	while($row_load_bookingpackage=mysqli_fetch_assoc($result_load_bookingpackage))
	{
		$sql_check="SELECT duration FROM package WHERE package_id='$row_load_bookingpackage[package_id]'";
		$result_check=mysqli_query($con,$sql_check) or die("sql error in sql_check ".mysqli_error($con));
		$row_check=mysqli_fetch_assoc($result_check);
		if(mysqli_num_rows($result_check)>0)
		{
			$endtime=date("H:i:s", strtotime($starttime) + ($row_check["duration"] * 60));

			$sql_update="UPDATE bookingpackage SET
							starttime='".mysqli_real_escape_string($con,$starttime)."',
							endtime='".mysqli_real_escape_string($con,$endtime)."'
					  WHERE booking_id='".mysqli_real_escape_string($con,$_POST["txtbookingid"])."' AND
							package_id='".mysqli_real_escape_string($con,$row_load_bookingpackage["package_id"])."'";
			$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));

			$starttime= $endtime; // Update start time for the next package

		}
	}
	if($result_insert )
	{
		unset($_SESSION["session_booking_id"]);
		unset($_SESSION["session_booking_type"]);
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=payment.php&option=add&pk_booking_id='.$_POST["txtbookingid"].'";</script>';
	}
}
//insert code end

//update code start
if(isset($_POST["btnsavechanges"]))
{
	$sql_update="UPDATE booking SET 
								bookdate='".mysqli_real_escape_string($con,$_POST["txtdate"])."',
								customer_id='".mysqli_real_escape_string($con,$_POST["txtcustomerid"])."',
								totalamount='".mysqli_real_escape_string($con,$_POST["txttotalamount"])."',
								status='".mysqli_real_escape_string($con,$_POST["txtstatus"])."',	
								booktype='".mysqli_real_escape_string($con,$_POST["txtbooktype"])."',	
								enterby='".mysqli_real_escape_string($con,$_POST["txtenterby"])."',	
								servicedate='".mysqli_real_escape_string($con,$_POST["txtservicedate"])."'
								WHERE booking_id='".mysqli_real_escape_string($con,$_POST["txtbookingid"])."'";      
	$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
	if($result_update)
	{
		echo '<script>alert("Successfully update");
						window.location.href="index.php?page=booking.php&option=view";</script>';
	}
}
//update code end
?>

<script>
function checkAvailability()
{
	var bookingid = document.getElementById("txtbookingid").value;
	var servicedate = document.getElementById("txtservicedate").value;
	var servicetime = document.getElementById("txtservicetime").value;
	
	if (bookingid != "" && servicedate != "" && servicetime != "") 
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
			{
				var responsevalue = xmlhttp.responseText.trim();
				if(responsevalue == "Yes")
				{
					alert("The selected date and time is available for booking.");
				}
				else
				{
					alert("The selected date and time is not available for booking. Please choose another date or time. or change the packages.");
					document.getElementById("txtservicedate").value = "";
					document.getElementById("txtservicetime").value = "";
				}
				
			}
		};
		xmlhttp.open("GET", "ajaxpage.php?frompage=booking_checkavailableity&ajax_booking_id=" + bookingid+"&ajax_servicedate="+servicedate+"&ajax_servicetime="+servicetime, true);
		xmlhttp.send();
	}
}
</script>
<script>
	function popup_payslip(imageName)
	{
		document.getElementById("popup_title").innerHTML='Payment Image';
		document.getElementById("popup_body").innerHTML='<img src="file/payment/'+imageName+'" width="100%" height="100%">';
	}
</script>

<body>
<?php
if(isset($_GET["option"]))
{
	if($_GET["option"]=="add")
	{
		//add form
		$get_subTotal=$_GET["subTotal"];
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Booking Addition</div>
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
												$generatedid=$_SESSION["session_booking_id"];
											?>
											<input type="text" class="form-control" name="txtbookingid" id="txtbookingid" required placeholder="Booking ID" value="<?php echo $generatedid ;?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtcustomerid">Customer ID</label>
											<select class="form-control" name="txtcustomerid" id="txtcustomerid" required placeholder="Customer ID">
												<?php
													if($system_usertype=="Clerk" || $system_usertype=="Admin")
													{
														echo'<option value="" disabled selected>Select Customer </option>';
														$sql_load="SELECT customer_id, name FROM customer WHERE customer_id IN (SELECT user_id FROM login WHERE status='Active')";
													}
													else
													{
														$sql_load="SELECT customer_id, name FROM customer WHERE customer_id='$system_user_id'";
													}
												
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
											<label for="txtdate">booking Date</label>
											<input type="date" class="form-control" name="txtdate" id="txtdate" readonly required value="<?php echo date("Y-m-d"); ?>" placeholder="Booking date"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">	
											<label for="txtbooktype">Book type</label>
											<select class="form-control" name="txtbooktype" id="txtbooktype" required placeholder="book type">
												<?php
													$sql_load_category="SELECT category_id, name FROM packagecategory WHERE category_id= '$_SESSION[session_booking_type]'";
													$result_load_category=mysqli_query($con,$sql_load_category) or die("sql error in sql_load_category".mysqli_error($con));
													while($row_load_category=mysqli_fetch_assoc($result_load_category))
													{
														echo'<option value="'.$row_load_category["category_id"].'">'.$row_load_category["name"].'</option>';
													}	
												?>
											</select>
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
											<?php
											$today=date('Y-m-d');
											
											if($system_usertype=="Customer")
											{
												$mindate=date("Y-m-d", strtotime($today."+1 day"));
											}
											else
											{
												$mindate=$today;
											}
											$maxdate=date("Y-m-d", strtotime($today."+2 months"));
											?>
											<label for="txtservicedate">Service Date</label>
											<input type="date" class="form-control" name="txtservicedate" id="txtservicedate" onChange="checkAvailability()" min="<?php echo $mindate; ?>" max="<?php echo $maxdate; ?>" required placeholder="Service date"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtservicetime">Service Time</label>
											<input type="time" class="form-control" name="txtservicetime" id="txtservicetime" onChange="checkAvailability()" min="08:30:00" max="16:30:00" required placeholder="Service time"/>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- third row end -->
								
								<!-- fourth row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txttotalamount">Total amount</label>
											<input type="number" onkeypress="return isNumberKey(event)"  value="<?php echo $get_subTotal; ?>" readonly class="form-control" name="txttotalamount" id="txttotalamount" required placeholder="total amount"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- fourth row end -->
								
								
								
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=bookingpackage.php&option=add"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
						<h4 class="card-title">Booking Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<?php
							if($system_usertype=="Admin" || $system_usertype=="Clerk" || $system_usertype=="Customer")
							{							
							?>
							<a href="index.php?page=bookingpackage.php&option=add"><button class="btn btn-primary">Add booking</button></a><br><br>
							<?php
							}
							?>
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
									if($system_usertype=="Customer")
									{
										$sql_view="SELECT booking_id,bookdate,customer_id,totalamount,servicedate FROM booking WHERE customer_id='$system_user_id' AND booktype!='Sale' ORDER BY booking_id DESC";
									}
									else if($system_usertype=="Admin" || $system_usertype=="Clerk")
									{
										$sql_view="SELECT booking_id,bookdate,customer_id,totalamount,servicedate FROM booking WHERE booktype!='Sale' ORDER BY booking_id DESC";
									}
									else
									{
										$sql_view="SELECT booking_id,bookdate,customer_id,totalamount,servicedate FROM booking WHERE  booktype!='Sale' AND booking_id IN(SELECT DISTINCT booking_id FROM bookingallocatestaff WHERE staff_id='$system_user_id')  ORDER BY booking_id DESC";
									}

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
												echo '<a href="index.php?page=booking.php&option=fullview&pk_booking_id='.$row_view["booking_id"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
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

		$sql_bookingtype="SELECT name from packagecategory WHERE category_id='$row_fullview[booktype]'";
		$result_bookingtype=mysqli_query($con,$sql_bookingtype) or die("sql error in sql_bookingtype ".mysqli_error($con));
		$row_bookingtype=mysqli_fetch_assoc($result_bookingtype);

		$enterby_firsttwoLetter=substr($row_fullview["enterby"],0,2);
		if($enterby_firsttwoLetter=="CU"){
			$sql_enterby="SELECT name from customer WHERE customer_id='$row_fullview[enterby]'";
		}
		else
		{
			$sql_enterby="SELECT name from staff WHERE staff_id='$row_fullview[enterby]'";
		}
		$result_enterby=mysqli_query($con,$sql_enterby) or die("sql error in sql_enterby ".mysqli_error($con));
		$row_enterby=mysqli_fetch_assoc($result_enterby);
										
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Booking fullview Details</h4>
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
								<tr><th>Status</th><td><?php echo $row_fullview["status"]; ?></td></tr>
								<tr><th>Book type</th><td><?php echo $row_bookingtype["name"]; ?></td></tr>
								<tr><th>Enter By</th><td><?php echo $row_enterby["name"]; ?></td></tr>
								<tr><th>Service Date</th><td><?php echo $row_fullview["servicedate"]; ?></td></tr>
								<?php
								if(isset($_GET["page"]))
								{
								?>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=booking.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
											<?php
											if(($system_usertype=="Admin" || $system_usertype=="Clerk") && $row_fullview["status"]=="Pending")
											{
											?>
												<a onclick="return reject_confirm()" href="index.php?page=booking.php&option=status&pk_booking_id=<?php echo $row_fullview["booking_id"]; ?>&Status=Reject"><button class="btn btn-danger"><i class="fa fa-trash"></i> Reject</button></a>
												<a onclick="return accept_confirm()" href="index.php?page=booking.php&option=status&pk_booking_id=<?php echo $row_fullview["booking_id"]; ?>&Status=Accept"><button class="btn btn-success"><i class="fa fa-thumbs-up"></i> Accept</button></a>
											<?php
											}
											$sql_maxtime="SELECT MAX(endtime) AS maxtime FROM bookingpackage WHERE booking_id='$row_fullview[booking_id]' ";
											$result_maxtime=mysqli_query($con,$sql_maxtime) or die("sql error in sql_maxtime ".mysqli_error($con));
											$row_maxtime=mysqli_fetch_assoc($result_maxtime);
											$endtime=date("H:i:s", strtotime($row_maxtime["maxtime"]));
											$timenow=date("H:i:s");
											if(($system_usertype=="Admin"|| $system_usertype=="Clerk") && $row_fullview["status"]=="Accept" && ((date("Y-m-d")==$row_fullview["servicedate"] && $timenow>=$endtime) || (date("Y-m-d")>$row_fullview["servicedate"])) )
											{
												echo '<a onclick="return accept_confirm()" href="index.php?page=booking.php&option=status&pk_booking_id='.$row_fullview["booking_id"].'&Status=Finish"><button class="btn btn-success"><i class="fa fa-check"></i>Finish</button></a>';
											}
											?>

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
	<!--Booking package details for the booking -->
		<div class="card">
		<div class="card-header">
			<h4 class="card-title">Booking Package Details</h4>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table id="basic-datatables" class="display table table-striped table-hover">
					<thead>
						<tr>
							<th>#</th>
							<th>Booking ID</th>
							<th>Package </th>
							<th>Start Time</th>
							<th>End Time</th>
							<th>Unit Price </th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x=1;
						$subTotal=0;
						$sql_view="SELECT * FROM bookingpackage WHERE booking_id='$row_fullview[booking_id]'";
						$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
						while($row_view=mysqli_fetch_assoc($result_view))
						{
							
							$sql_package="SELECT name from package WHERE package_id='$row_view[package_id]'";
							$result_package=mysqli_query($con,$sql_package) or die("sql error in sql_package ".mysqli_error($con));
							$row_package=mysqli_fetch_assoc($result_package);

							//get unit price
							$sql_price="SELECT price,offer FROM packageprice WHERE package_id='$row_view[package_id]' AND startdate<='$row_fullview[bookdate]' AND (enddate>='$row_fullview[bookdate]' OR enddate IS NULL)";
							$result_price=mysqli_query($con,$sql_price) or die("sql error in sql_price ".mysqli_error($con));
							$row_price=mysqli_fetch_assoc($result_price);

							$unit_price=$row_price["price"]*(1-$row_price["offer"]/100);
							$subTotal=$subTotal+$unit_price;
							
							echo '<tr>';
								echo '<td>'.$x++.'</td>';
								echo '<td>'.$row_view["booking_id"].'</td>';
								echo '<td>'.$row_package["name"].'</td>';
								echo '<td>'.$row_view["starttime"].'</td>';
								echo '<td>'.$row_view["endtime"].'</td>';
								echo '<td>'.$unit_price.'</td>';
							echo '</tr>';
						}
						if($x>1)
							{
								echo '<tr>';
									echo '<td>'.$x.'</td>';
									echo '<td>Sub Total</td>';
									echo '<td></td>';
									echo '<td></td>';
									echo '<td></td>';
									echo '<td>'.$subTotal.'</td>';
								echo '</tr>';
							}
						?>
					</tbody>
				</table>
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
							if($row_fullview["status"]=="Accept" && $row_paied_amount["total_pay"]<$subTotal)
							{
								//if the payment is not completed
								?>
								<a href="index.php?page=payment.php&option=add&pk_booking_id=<?php echo $row_fullview["booking_id"] ; ?>"><button class="btn btn-primary">Add Payment</button></a><br><br>
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
										if($system_usertype=="Admin" || $system_usertype=="Clerk")
										{
										?>
											<th>Action</th>
										<?php
										}
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
	<!--Staff alocating for the booking details-->
	<?php
	if ($row_fullview["status"]=="Accept" || $row_fullview["status"]=="Finish" )
	{
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Staff allocated for the booking Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<?php
							$sql_no_bookingpackage="SELECT * FROM bookingpackage WHERE booking_id='$row_fullview[booking_id]'";
							$result_no_bookingpackage=mysqli_query($con,$sql_no_bookingpackage) or die("sql error in sql_no_bookingpackage ".mysqli_error($con));
							$row_no_bookingpackage=mysqli_fetch_assoc($result_no_bookingpackage);

							$sql_no_staffallocate="SELECT booking_id,package_id,staff_id FROM bookingallocatestaff WHERE booking_id='$row_fullview[booking_id]'";
							$result_no_staffallocate=mysqli_query($con,$sql_no_staffallocate) or die("sql error in sql_no_staffallocate ".mysqli_error($con));
							$row_no_staffallocate=mysqli_fetch_assoc($result_no_staffallocate);
							if(isset($_GET["page"]))
							{
								$sql_view="SELECT * FROM payment WHERE booking_id='$row_fullview[booking_id]' AND paystatus='Paid'";
								$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
								
								if(mysqli_num_rows($result_view)>0 && $row_fullview["status"]=="Accept" && ($system_usertype=="Admin" || $system_usertype=="Clerk") && (mysqli_num_rows($result_no_bookingpackage)>mysqli_num_rows($result_no_staffallocate)))
								{
									//if the payment is completed and the booking is accepted and no staff allocated and the user is admin or clerk then allow to allocate staff for the booking
								?>
									<a href="index.php?page=bookingallocatestaff.php&option=add&pk_booking_id=<?php echo $row_fullview["booking_id"] ; ?>"><button class="btn btn-primary">Allocate staff for booking</button></a><br><br>
								<?php
								}
							}
							?>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>Package</th>
										<th>Staff</th>
										<?php
										if(isset($_GET["page"]))
										{
											if($system_usertype=="Admin" || $system_usertype=="Clerk")
											{
											?>
												<th>Action</th>
											<?php
											}
										}
										?>
									</tr>
								</thead>
								<tbody>
									<?php
									$x=1;
									$sql_view="SELECT booking_id,package_id,staff_id FROM bookingallocatestaff WHERE booking_id='$row_fullview[booking_id]'";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{	$sql_package="SELECT name from package WHERE package_id='$row_view[package_id]'";
										$result_package=mysqli_query($con,$sql_package) or die("sql error in sql_package ".mysqli_error($con));
										$row_package=mysqli_fetch_assoc($result_package);
										
										$sql_staff="SELECT name from staff WHERE staff_id='$row_view[staff_id]'";
										$result_staff=mysqli_query($con,$sql_staff) or die("sql error in sql_staff ".mysqli_error($con));
										$row_staff=mysqli_fetch_assoc($result_staff);
										
										echo '<tr>';
											echo '<td>'.$x++.'</td>';
											echo '<td>'.$row_package["name"].'</td>';
											echo '<td>'.$row_staff["name"].'</td>';
										if(isset($_GET["page"]))
										{
											if(($system_usertype=="Admin" || $system_usertype=="Clerk") && $row_fullview["status"]=="Accept")
											{
											echo '<td>';
												echo '<a onclick="return delete_confirm()" href="index.php?page=bookingallocatestaff.php&option=delete&pk_booking_id='.$row_view["booking_id"].'&pk_package_id='.$row_view["package_id"].'&pk_staff_id='.$row_view["staff_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
											echo '</td>';
											}
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
			<!--Review details for the booking -->
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Review Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<?php
							if(isset($_GET["page"]))
							{
								if($row_fullview["status"]=="Finish" && $system_usertype=="Customer")
								{
									//if the booking is finished and the user is customer
									$sql_allow_review="SELECT * FROM review WHERE booking_id='$row_fullview[booking_id]'";
									$result_allow_review=mysqli_query($con,$sql_allow_review) or die("sql error in sql_allow_review ".mysqli_error($con));
									if(mysqli_num_rows($result_allow_review)==0)
									{
										?>
										<a href="index.php?page=review.php&option=add&pk_booking_id=<?php echo $row_fullview["booking_id"] ; ?>"><button class="btn btn-primary">Add Review</button></a><br><br>
										<?php
									}
								}
							}
							?>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>Review ID</th>
										<th>package</th>
										<th>date</th>
										<th>rate</th>
										<th>comments</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$y=1;
									$sql_view_review="SELECT * FROM review WHERE booking_id='$row_fullview[booking_id]'";
									$result_view_review=mysqli_query($con,$sql_view_review) or die("sql error in sql_view_review ".mysqli_error($con));
									while($row_view_review=mysqli_fetch_assoc($result_view_review))
									{	$sql_package="SELECT name from package WHERE package_id='$row_view_review[package_id]'";
										$result_package=mysqli_query($con,$sql_package) or die("sql error in sql_package ".mysqli_error($con));
										$row_package=mysqli_fetch_assoc($result_package);
										
										echo '<tr>';
											echo '<td>'.$y++.'</td>';
											echo '<td>'.$row_view_review["review_id"].'</td>';
											echo '<td>'.$row_package["name"].'</td>';
											echo '<td>'.$row_view_review["date"].'</td>';
											echo '<td>';
											for($i=1;$i<=5;$i++)
											{
												if($i<=$row_view_review["rate"])
												{
													echo '<i class="fa fa-star" style="color:orange;"></i>';
												}
												else
												{
													echo '<i class="fa fa-star"></i>';
												}
											}
											echo '</td>';
											echo '<td>'.$row_view_review["comments"].'</td>';
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
	else if($_GET["option"]=="edit")
	{
		//edit form
		$get_pk_booking_id=$_GET["pk_booking_id"];
		
		$sql_edit="SELECT * FROM booking WHERE booking_id='$get_pk_booking_id'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit ".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Edit Booking </div>
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
											<input type="text" class="form-control" name="txtbookingid" id="txtbookingid" required placeholder="Booking ID"  value="<?php echo $row_edit["booking_id"]; ?>" readonly  />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtcustomerid">Customer ID</label>
											<select class="form-control" name="txtcustomerid" id="txtcustomerid" required placeholder="Customer ID">
												<option value="select">Select customer </option>
												<?php
												$sql_load="SELECT customer_id, name FROM customer ";
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
											<label for="txtdate">booking Date</label>
											<input type="date" class="form-control" name="txtdate" id="txtdate" required placeholder="Booking date"  value="<?php echo $row_edit["bookdate"]; ?>"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">	
											<label for="txtbooktype">Book type</label>
											<input type="text" class="form-control" name="txtbooktype" id="txtbooktype" required placeholder="book type"  value="<?php echo $row_edit["booktype"]; ?>"/>
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
											<label for="txtstatus">Status</label>
											<input type="text" class="form-control" name="txtstatus" id="txtstatus" required placeholder="status"  value="<?php echo $row_edit["status"]; ?>"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txttotalamount">Total amount</label>
											<input type="number" onkeypress="return isNumberKey(event)"  class="form-control" name="txttotalamount" id="txttotalamount" required placeholder="total amount"  value="<?php echo $row_edit["totalamount"]; ?>"/>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- third row end -->
								
								<!-- fourth row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtenterby">Enter BY</label>
											<input type="text" class="form-control" name="txtenterby" id="txtenterby" required placeholder="Enter by"  value="<?php echo $row_edit["enterby"]; ?>"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtservicedate">Service Date</label>
											<input type="date" class="form-control" name="txtservicedate" id="txtservicedate" required placeholder="Service date"  value="<?php echo $row_edit["servicedate"]; ?>"/>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- fourth row end -->
								
								
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=booking.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
		
		$sql_delete=" DELETE FROM booking WHERE booking_id='$get_pk_booking_id'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete)
		{
			echo '<script>alert("Successfully Deleted");
				  window.location.href="index.php?page=booking.php&option=view";</script>';
		}
	}
	else if($_GET["option"]=="status")
	{
		//delete code
		$get_pk_booking_id=$_GET["pk_booking_id"];
		$get_Status=$_GET["Status"];
		
		$sql_update="UPDATE booking SET status='$get_Status' WHERE booking_id='$get_pk_booking_id'";
		$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
		if($result_update)		
		{
			echo '<script>alert("Successfully Update Status");
						window.location.href="index.php?page=booking.php&option=fullview&pk_booking_id='.$get_pk_booking_id.'";</script>';
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