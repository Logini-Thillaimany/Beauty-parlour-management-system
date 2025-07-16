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
	$allowed=0;
	if($system_usertype=="Clerk" || $system_usertype=="Admin")
	{
		$status="Paid";
		$allowed=1;
		if($_POST["txtpaymode"]=="Cash")
		{
			$filename="";
		}
		else
		{
			$target_dir = "file/payment/";
			$target_file = $target_dir . basename($_FILES["txtimage"]["name"]);
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg")
			{
				echo '<script>alert("Sorry, only JPG, JPEG & PNG  files are allowed.");</script>';
			}
			else
			{
				$filename=$_POST["txtpaymentid"].".".$imageFileType;
				$fileupload=$target_dir . $filename;
				move_uploaded_file($_FILES["txtimage"]["tmp_name"], $fileupload);
				$allowed=1;
			}
		}
	}
	else
	{
		$status="Pending";

		$target_dir = "file/payment/";
		$target_file = $target_dir . basename($_FILES["txtimage"]["name"]);
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg")
		{
			echo '<script>alert("Sorry, only JPG, JPEG & PNG  files are allowed.");</script>';
		}
		else
		{
		$filename=$_POST["txtpaymentid"].".".$imageFileType;
		$fileupload=$target_dir . $filename;
		move_uploaded_file($_FILES["txtimage"]["tmp_name"], $fileupload);
		$allowed=1;
		}
	}
	if($allowed==1)
	{
		$sql_insert="INSERT INTO payment(payment_id,booking_id,paydate,paymode,payamount,paystatus,slipphoto)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtpaymentid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtbookingid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtdate"])."',
									'".mysqli_real_escape_string($con,$_POST["txtpaymode"])."',
									'".mysqli_real_escape_string($con,$_POST["txtamount"])."',
									'".mysqli_real_escape_string($con,$status)."',
									'".mysqli_real_escape_string($con,$filename)."')";
		$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
		if($result_insert)
		{
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=booking.php&option=fullview&pk_booking_id='.$_POST["txtbookingid"].'";</script>';
		}
	}
}
//insert code end

//update code start
if(isset($_POST["btnsavechanges"]))
{
	$sql_update="UPDATE payment SET
									booking_id='".mysqli_real_escape_string($con,$_POST["txtbookingid"])."',
									paydate='".mysqli_real_escape_string($con,$_POST["txtdate"])."',
									paymode='".mysqli_real_escape_string($con,$_POST["txtpaymode"])."',
									payamount='".mysqli_real_escape_string($con,$_POST["txtamount"])."',
									paystatus='".mysqli_real_escape_string($con,$_POST["txtstatus"])."',
									slipphoto='".mysqli_real_escape_string($con,$_POST["txtimage"])."'
									WHERE payment_id='".mysqli_real_escape_string($con,$_POST["txtpaymentid"])."'";
	$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
	if($result_update)
	{
		echo '<script>alert("Successfully UPDATE");
						window.location.href="index.php?page=payment.php&option=view";</script>';
	}
}
//update code end

//insert sale payment code start
if(isset($_POST["btnsavesale"]))
{
	$allowed=0;
	if($_POST["txtpaymode"]=="Cash")
	{
		$filename="";
		$status="Paid";
		$allowed=1;

	}
	else
	{
		$target_dir = "file/payment/";
		$target_file = $target_dir . basename($_FILES["txtimage"]["name"]);
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg")
		{
			echo '<script>alert("Sorry, only JPG, JPEG & PNG  files are allowed.");</script>';
		}
		else
		{
			$filename=$_POST["txtpaymentid"].".".$imageFileType;
			$fileupload=$target_dir . $filename;
			move_uploaded_file($_FILES["txtimage"]["tmp_name"], $fileupload);
			$status="Paid";
			$allowed=1;
		}
	}
	if($allowed==1)
	{
		$sql_insert="INSERT INTO payment(payment_id,booking_id,paydate,paymode,payamount,paystatus,slipphoto)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtpaymentid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtbookingid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtdate"])."',
									'".mysqli_real_escape_string($con,$_POST["txtpaymode"])."',
									'".mysqli_real_escape_string($con,$_POST["txtamount"])."',
									'".mysqli_real_escape_string($con,$status)."',
									'".mysqli_real_escape_string($con,$filename)."')";
		$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
		if($result_insert)
		{
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=bookingsales.php&option=fullview&pk_booking_id='.$_POST["txtbookingid"].'";</script>';
		}
	}
}
//insert code end
?>
<script>
	function active_slip()
	{
		var paymode=document.getElementById("txtpaymode").value;
		if(paymode=="Cash" )
		{
			document.getElementById("txtimage").value="";
			document.getElementById("txtimage").readOnly=true;
			document.getElementById("txtimage").type="text";
		}
		else 
		{
			document.getElementById("txtimage").value="";
			document.getElementById("txtimage").readOnly=false;
			document.getElementById("txtimage").type="file";
		}
		
	}
</script>
<body>
<?php
if(isset($_GET["option"]))
{
	if($_GET["option"]=="add")
	{
		//add form
		$get_pk_booking_id=$_GET["pk_booking_id"];
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for payment Addition</div>
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
											<label for="txtpaymentid">Payment ID</label>
											<?php
												$sql_generatedid="SELECT payment_id FROM payment ORDER BY payment_id DESC LIMIT 1";
												$result_generatedid=mysqli_query($con,$sql_generatedid) or die("sql error in sql_generatedid ".mysqli_error($con));
												if(mysqli_num_rows($result_generatedid)==1)
												{// for  except from the first submission
													$row_generatedid=mysqli_fetch_assoc($result_generatedid);
													$generatedid=++$row_generatedid["payment_id"];
												}
												else
												{//For first time submission
													$generatedid="PAY0000001";
												}
											?>
											<input type="text" class="form-control" name="txtpaymentid" id="txtpaymentid" required placeholder="payment ID" value="<?php echo $generatedid;?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtbookingid">Booking ID</label>
											<input type="text" class="form-control" name="txtbookingid" id="txtbookingid" required placeholder="Booking id" value="<?php echo $get_pk_booking_id;?>" readonly />
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
											<label for="txtdate">Pay date</label>
											<input type="date" class="form-control" value="<?php echo date('Y-m-d');?>" name="txtdate" id="txtdate" required placeholder="date" readonly/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- second row end -->
								 <!-- third row start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-2"></div>
										<div class="col-md-8">
										<table  class="display table table-striped table-hover">
											<tbody>
												<?php
												$sql_total="SELECT totalamount FROM booking WHERE booking_id='$get_pk_booking_id'";
												$result_total=mysqli_query($con,$sql_total) or die("sql error in sql_total ".mysqli_error($con));
												$row_total=mysqli_fetch_assoc($result_total);

												$sql_paied_amount= "SELECT SUM(payamount) AS total_pay FROM payment WHERE booking_id='$get_pk_booking_id'";
												$result_paied_amount=mysqli_query($con,$sql_paied_amount) or die("sql error in sql_paied_amount ".mysqli_error($con));	
												$row_paied_amount=mysqli_fetch_assoc($result_paied_amount);

												$Total_amount=$row_total["totalamount"];
												$Paied_amount=$row_paied_amount["total_pay"];
												$balance=$Total_amount- $Paied_amount;

												
													echo '<tr>';
														echo '<td>Total Amount:</td>';
														echo '<td>'.$Total_amount.'</td>';
													echo '</tr>';
													echo '<tr>';	
														echo '<td>Amount you have paied :</td>';
														echo '<td>'.$Paied_amount.'</td>';
													echo '</tr>';
													echo '<tr>';
														echo '<td>Balance amount to pay :</td>';
														echo '<td>'.$balance.'</td>';
													echo '</tr>';
												?>
											</tbody>
										</table>
										</div>
										<div class="col-md-2"></div>
									</div>
								</div>
								<!-- third row end -->
								
								<!-- forth row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtamount">Amount</label>
											<?php 
												if($Paied_amount==0)
												{
													$min= $Total_amount*0.3;//30% of the total amount
													$max=$Total_amount;
												}
												else
												{
													$min= 1;
													$max=$balance;
												}
											?>
											<input type="number" onkeypress="return isNumberKey(event)" min="<?php echo $min; ?>" max="<?php echo $max; ?>" class="form-control" name="txtamount" id="txtamount" required placeholder="amount to pay"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtpaymode">Pay mode</label>
											<select class="form-control" name="txtpaymode" id="txtpaymode" onChange="active_slip()" required placeholder="Paymode">
												<?php
												if($system_usertype=="Clerk" ||$system_usertype=="Admin")
												{
													echo'<option value="Cash">Cash</option>';
													echo'<option value="Bank">Bank</option>';
												}
												else
												{
													echo'<option value="Bank">Bank</option>';
												}	
												?>
											</select>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- forth row end -->
								
								<!-- fifth row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">	

											<label for="txtimage"> Slipt image</label>
											<?php
												if($system_usertype=="Clerk" ||$system_usertype=="Admin")
												{
													echo '<input type="text" class="form-control"  name="txtimage" id="txtimage" required placeholder="image" readonly />';
												}
												else
												{
													echo '<input type="file" class="form-control"  name="txtimage" id="txtimage" required placeholder="image"/>';
												}
												?>
											<font color="red"> *Only the image types can be upload (eg: .jpg, .jpeg, .png)</font>
										</div>
										<!-- column one end -->
									</div>
								</div>
								<!-- fifth row end -->
								
								
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=booking.php&option=fullview&pk_booking_id=<?php echo $get_pk_booking_id; ?>"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
						<h4 class="card-title">Payment Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=payment.php&option=add"><button class="btn btn-primary">Add Payment</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>Payment ID</th>
										<th>Booking ID</th>
										<th>Pay date</th>
										<th>Pay amount</th>
										<th>status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql_view="SELECT payment_id,booking_id,paydate,payamount,paystatus FROM payment";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{
										echo '<tr>';
											echo '<td>'.$row_view["payment_id"].'</td>';
											echo '<td>'.$row_view["booking_id"].'</td>';
											echo '<td>'.$row_view["paydate"].'</td>';
											echo '<td>'.$row_view["payamount"].'</td>';
											echo '<td>'.$row_view["paystatus"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=payment.php&option=fullview&pk_payment_id='.$row_view["payment_id"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
												echo '<a href="index.php?page=payment.php&option=edit&pk_payment_id='.$row_view["payment_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
												echo '<a onclick="return delete_confirm()" href="index.php?page=payment.php&option=delete&pk_payment_id='.$row_view["payment_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
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
		$get_pk_payment_id=$_GET["pk_payment_id"];
		
		$sql_fullview="SELECT * FROM payment WHERE payment_id='$get_pk_payment_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Payment Full Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="display table table-striped table-hover">
								<tr><th>payment ID</th><td><?php echo $row_fullview["payment_id"]; ?></td></tr>
								<tr><th>Booking ID</th><td><?php echo $row_fullview["booking_id"]; ?></td></tr>
								<tr><th>Pay Date</th><td><?php echo $row_fullview["paydate"]; ?></td></tr>
								<tr><th>Pay mode</th><td><?php echo $row_fullview["paymode"]; ?></td></tr>
								<tr><th>Pay Amount</th><td><?php echo $row_fullview["payamount"]; ?></td></tr>
								<tr><th>Pay Status</th><td><?php echo $row_fullview["paystatus"]; ?></td></tr>
								<tr><th>Slip photo</th><td><?php echo $row_fullview["slipphoto"]; ?></td></tr>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=payment.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
											<a href="index.php?page=payment.php&option=edit&pk_payment_id=<?php echo $row_fullview["payment_id"]; ?>"><button class="btn btn-info">Edit</button></a> 
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
		$get_pk_payment_id=$_GET["pk_payment_id"];
		
		$sql_edit="SELECT * FROM payment WHERE payment_id='$get_pk_payment_id'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Edit payment </div>
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
											<label for="txtpaymentid">Payment ID</label>
											<input type="text" class="form-control" name="txtpaymentid" id="txtpaymentid" required placeholder="payment ID" value="<?php echo $row_edit["payment_id"];  ?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtbookingid">Booking ID</label>
											<select class="form-control" name="txtbookingid" id="txtbookingid" required placeholder="Booking id" >
												<option value="select">Select Booking </option>
												<?php
												$sql_load_booking="SELECT booking_id FROM booking ";
												$result_load_booking=mysqli_query($con,$sql_load_booking) or die("sql error in sql_load_booking".mysqli_error($con));
												while($row_load_booking=mysqli_fetch_assoc($result_load_booking))
												{
													echo'<option value="'.$row_load_booking["booking_id"].'">'.$row_load_booking["booking_id"].'</option>';
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
											<label for="txtpaymode">Pay mode</label>
											<input type="text" class="form-control" name="txtpaymode" id="txtpaymode" required placeholder="Paymode" value="<?php echo $row_edit["paymode"]; ?>"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtdate">Pay date</label>
											<input type="date" class="form-control" name="txtdate" id="txtdate" required placeholder="date" value="<?php echo $row_edit["paydate"]; ?>"/>
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
											<label for="txtamount">Amount</label>
											<input type="text" onkeypress="return isNumberKey(event)"  class="form-control" name="txtamount" id="txtamount" required placeholder="amount to pay" value="<?php echo $row_edit["payamount"]; ?>"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtstatus">status</label>
											<input type="text" class="form-control" name="txtstatus" id="txtstatus" required placeholder="Status" value="<?php echo $row_edit["paystatus"]; ?>"/>
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
											<label for="txtimage"> Slipt image</label>
											<input type="txt" class="form-control"  name="txtimage" id="txtimage" required placeholder="image" value="<?php echo $row_edit["slipphoto"]; ?>" />
										</div>
										<!-- column one end -->
									</div>
								</div>
								<!-- fourth row end -->
								
								
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=payment.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
	if($_GET["option"]=="sale")
	{
		//add sale payment form
		$get_pk_booking_id=$_GET["pk_booking_id"];
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title">Sales payment</div>
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
											<label for="txtpaymentid">Payment ID</label>
											<?php
												$sql_generatedid="SELECT payment_id FROM payment ORDER BY payment_id DESC LIMIT 1";
												$result_generatedid=mysqli_query($con,$sql_generatedid) or die("sql error in sql_generatedid ".mysqli_error($con));
												if(mysqli_num_rows($result_generatedid)==1)
												{// for  except from the first submission
													$row_generatedid=mysqli_fetch_assoc($result_generatedid);
													$generatedid=++$row_generatedid["payment_id"];
												}
												else
												{//For first time submission
													$generatedid="PAY0000001";
												}
											?>
											<input type="text" class="form-control" name="txtpaymentid" id="txtpaymentid" required placeholder="payment ID" value="<?php echo $generatedid;?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtbookingid">Sales ID</label>
											<input type="text" class="form-control" name="txtbookingid" id="txtbookingid" required placeholder="Booking id" value="<?php echo $get_pk_booking_id;?>" readonly />
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
											<label for="txtdate">Pay date</label>
											<input type="date" class="form-control" value="<?php echo date('Y-m-d');?>" name="txtdate" id="txtdate" required placeholder="date" readonly/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtamount">Amount</label>
											<?php
												$sql_total="SELECT totalamount FROM booking WHERE booking_id='$get_pk_booking_id'";
												$result_total=mysqli_query($con,$sql_total) or die("sql error in sql_total ".mysqli_error($con));
												$row_total=mysqli_fetch_assoc($result_total);
												$amount=$row_total["totalamount"];
											?>
											<input type="number"  class="form-control" onkeypress="return isNumberKey(event)" value="<?php echo $amount; ?>" class="form-control" name="txtamount" id="txtamount" readonly required placeholder="amount to pay"/>
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
											<label for="txtpaymode">Pay mode</label>
											<select class="form-control" name="txtpaymode" id="txtpaymode" onChange="active_slip()" required placeholder="Paymode">
												<option value="Cash">Cash</option>
												<option value="Bank">Bank</option>
											</select>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtimage"> Slipt image</label>
											<input type="text" class="form-control"  name="txtimage" id="txtimage" required placeholder="image" readonly />
											<font color="red"> *Only the image types can be upload (eg: .jpg, .jpeg, .png)</font>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- forth row end -->
								
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=bookingsales.php&option=fullview&pk_booking_id=<?php echo $get_pk_booking_id; ?>"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
												<input type="reset" class="btn btn-danger" name="btnclear" id="btnclear"  value="Clear"/>
												<input type="submit" class="btn btn-success" name="btnsavesale" id="btnsavesale"  value="Save"/>
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
	//delete code
	else if($_GET["option"]=="delete")
	{
		//delete code
		$get_pk_payment_id=$_GET["pk_payment_id"];
		
		$sql_delete="DELETE FROM payment WHERE payment_id='$get_pk_payment_id'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete)		
		{
			echo '<script>alert("Successfully Delete");
						window.location.href="index.php?page=payment.php&option=view";</script>';
		}
	}
	else if($_GET["option"]=="status")
	{
		//delete code
		$get_pk_payment_id=$_GET["pk_payment_id"];
		$get_pk_booking_id=$_GET["pk_booking_id"];
		$get_Status=$_GET["Status"];
		
		$sql_update="UPDATE payment SET paystatus='$get_Status' WHERE payment_id='$get_pk_payment_id'";
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