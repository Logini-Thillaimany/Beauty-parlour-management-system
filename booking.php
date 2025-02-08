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
	$sql_insert="INSERT INTO booking(booking_id,bookdate,customer_id,totalamount,status,booktype,enterby,servicedate)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtbookingid"])."',
							       '".mysqli_real_escape_string($con,$_POST["txtdate"])."',
									'".mysqli_real_escape_string($con,$_POST["txtcustomerid"])."',
									'".mysqli_real_escape_string($con,$_POST["txttotalamount"])."',
									'".mysqli_real_escape_string($con,$_POST["txtstatus"])."',
									'".mysqli_real_escape_string($con,$_POST["txtbooktype"])."',
									'".mysqli_real_escape_string($con,$_POST["txtenterby"])."',
									'".mysqli_real_escape_string($con,$_POST["txtservicedate"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
	if($result_insert)
	{
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=booking.php&option=add";</script>';
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
												$sql_generatedid="SELECT booking_id FROM booking ORDER BY booking_id DESC LIMIT 1";
												$result_generatedid=mysqli_query($con,$sql_generatedid) or die("sql error in sql_generatedid ".mysqli_error($con));
												if(mysqli_num_rows($result_generatedid)==1)
												{// for  except from the first submission
													$row_generatedid=mysqli_fetch_assoc($result_generatedid);
													$generatedid=++$row_generatedid["booking_id"];
												}
												else
												{//For first time submission
													$generatedid="BK00000001";
												}
											?>
											<input type="text" class="form-control" name="txtbookingid" id="txtbookingid" required placeholder="Booking ID" value="<?php echo $generatedid;?>" readonly />
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
											<input type="date" class="form-control" name="txtdate" id="txtdate" required placeholder="Booking date"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">	
											<label for="txtbooktype">Book type</label>
											<input type="text" class="form-control" name="txtbooktype" id="txtbooktype" required placeholder="book type"/>
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
											<input type="text" class="form-control" name="txtstatus" id="txtstatus" required placeholder="status"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txttotalamount">Total amount</label>
											<input type="number" onkeypress="return isNumberKey(event)"  class="form-control" name="txttotalamount" id="txttotalamount" required placeholder="total amount"/>
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
											<input type="text" class="form-control" name="txtenterby" id="txtenterby" required placeholder="Enter by"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtservicedate">Service Date</label>
											<input type="date" class="form-control" name="txtservicedate" id="txtservicedate" required placeholder="Service date"/>
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
							<a href="index.php?page=booking.php&option=add"><button class="btn btn-primary">Add booking</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
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
									$sql_view="SELECT booking_id,bookdate,customer_id,totalamount,servicedate FROM booking";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{	
										$sql_customer="SELECT name from customer WHERE customer_id='$row_view[customer_id]'";
										$result_customer=mysqli_query($con,$sql_customer) or die("sql error in sql_customer ".mysqli_error($con));
										$row_customer=mysqli_fetch_assoc($result_customer);
										
										echo '<tr>';
											echo '<td>'.$row_view["booking_id"].'</td>';
											echo '<td>'.$row_view["bookdate"].'</td>';
											echo '<td>'.$row_customer["name"].'</td>';
											echo '<td>'.$row_view["totalamount"].'</td>';
											echo '<td>'.$row_view["servicedate"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=booking.php&option=fullview&pk_booking_id='.$row_view["booking_id"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
												echo '<a href="index.php?page=booking.php&option=edit&pk_booking_id='.$row_view["booking_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
												echo '<a onclick="return delete_confirm()" href="index.php?page=booking.php&option=delete&pk_booking_id='.$row_view["booking_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
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
										
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Booking fullview Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table  class="display table table-striped table-hover">
								<tr><th>Booking ID</th><td><?php echo $row_fullview["booking_id"]; ?></td></tr>
								<tr><th>Book date</th><td><?php echo $row_fullview["bookdate"]; ?></td></tr>
								<tr><th>Customer</th><td><?php echo $row_customer["name"]; ?></td></tr>
								<tr><th>Total amount</th><td><?php echo $row_fullview["totalamount"]; ?></td></tr>
								<tr><th>Status</th><td><?php echo $row_fullview["status"]; ?></td></tr>
								<tr><th>Book type</th><td><?php echo $row_fullview["booktype"]; ?></td></tr>
								<tr><th>Enter By</th><td><?php echo $row_fullview["enterby"]; ?></td></tr>
								<tr><th>Service Date</th><td><?php echo $row_fullview["servicedate"]; ?></td></tr>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=booking.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
											<a href="index.php?page=booking.php&option=edit&pk_booking_id=<?php echo $row_fullview["booking_id"]; ?>"><button class="btn btn-info">Edit</button></a> 
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
}
?>
</body>