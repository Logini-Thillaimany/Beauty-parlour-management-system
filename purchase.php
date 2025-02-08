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
	$sql_insert="INSERT INTO purchase(purchase_id,supplier_id,date,totalamount,paymode,paystatus,slipphoto)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtpurchaseid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtsupplierid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtdate"])."',
									'".mysqli_real_escape_string($con,$_POST["txttotalamount"])."',
									'".mysqli_real_escape_string($con,$_POST["txtpaymode"])."',
									'".mysqli_real_escape_string($con,$_POST["txtpaystatus"])."',
									'".mysqli_real_escape_string($con,$_POST["txtimage"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
	if($result_insert)
	{
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=purchase.php&option=add";</script>';
	}
}
//insert code end

//update code start
if(isset($_POST["btnsavechanges"]))
{
	$sql_update="UPDATE purchase SET
									supplier_id='".mysqli_real_escape_string($con,$_POST["txtsupplierid"])."',
									date='".mysqli_real_escape_string($con,$_POST["txtdate"])."',
									totalamount='".mysqli_real_escape_string($con,$_POST["txttotalamount"])."',
									paymode='".mysqli_real_escape_string($con,$_POST["txtpaymode"])."',
									paystatus='".mysqli_real_escape_string($con,$_POST["txtpaystatus"])."',
									slipphoto='".mysqli_real_escape_string($con,$_POST["txtimage"])."'
									WHERE purchase_id='".mysqli_real_escape_string($con,$_POST["txtpurchaseid"])."'";
	$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
	if($result_update)
	{
		echo '<script>alert("Successfully update");
						window.location.href="index.php?page=purchase.php&option=view";</script>';
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
						<div class="card-title"> Form for purchase Addition</div>
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
											<label for="txtpurchaseid">purchase ID</label>
											<?php
												$sql_generatedid="SELECT purchase_id FROM purchase ORDER BY purchase_id DESC LIMIT 1";
												$result_generatedid=mysqli_query($con,$sql_generatedid) or die("sql error in sql_generatedid ".mysqli_error($con));
												if(mysqli_num_rows($result_generatedid)==1)
												{// for  except from the first submission
													$row_generatedid=mysqli_fetch_assoc($result_generatedid);
													$generatedid=++$row_generatedid["purchase_id"];
												}
												else
												{//For first time submission
													$generatedid="PUR0000001";
												}
											?>
											<input type="text" class="form-control" name="txtpurchaseid" id="txtpurchaseid" required placeholder="purchase ID" value="<?php echo $generatedid;?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtsupplierid">Supplier</label>
											<select class="form-control" name="txtsupplierid" id="txtsupplierid" required placeholder="Supplier ID">
												<option value="select">Select Supplier </option>
												<?php
												$sql_load="SELECT supplier_id, name FROM supplier ";
												$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
												while($row_load=mysqli_fetch_assoc($result_load))
												{
													echo'<option value="'.$row_load["supplier_id"].'">'.$row_load["name"].'</option>';
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
											<label for="txtdate">Date</label>
											<input type="date" class="form-control" name="txtdate" id="txtdate" required placeholder="date"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txttotalamount">Total amount</label>
											<input type="text" onkeypress="return isNumberKey(event)"  class="form-control" name="txttotalamount" id="txttotalamount" required placeholder="total amount"/>
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
											<input type="text" class="form-control" name="txtpaymode" id="txtpaymode" required placeholder="Pay mode"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtpaystatus">Pay Status</label>
											<input type="text" class="form-control" name="txtpaystatus" id="txtpaystatus" required placeholder="Pay Status">
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
										<label for="txtimage">Slipt photo</label>
										<input type="text" class="form-control"  name="txtimage" id="txtimage" required placeholder="image"/>
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
												<a href="index.php?page=purchase.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
						<h4 class="card-title">Purchase Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=purchase.php&option=add"><button class="btn btn-primary">Add Purchase</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>Purchase ID</th>
										<th>Supplier</th>
										<th>Date</th>
										<th>Total amount</th>
										<th>Pay status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql_view="SELECT purchase_id,supplier_id,date,totalamount,paystatus FROM purchase";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{
										$sql_supplier="SELECT name from supplier WHERE supplier_id='$row_view[supplier_id]'";
										$result_supplier=mysqli_query($con,$sql_supplier) or die("sql error in sql_supplier ".mysqli_error($con));
										$row_supplier=mysqli_fetch_assoc($result_supplier);
										
										echo '<tr>';
											echo '<td>'.$row_view["purchase_id"].'</td>';
											echo '<td>'.$row_supplier["name"].'</td>';
											echo '<td>'.$row_view["date"].'</td>';
											echo '<td>'.$row_view["totalamount"].'</td>';
											echo '<td>'.$row_view["paystatus"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=purchase.php&option=fullview&pk_purchase_id='.$row_view["purchase_id"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
												echo '<a href="index.php?page=purchase.php&option=edit&pk_purchase_id='.$row_view["purchase_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
												echo '<a onclick="return delete_confirm()" href="index.php?page=purchase.php&option=delete&pk_purchase_id='.$row_view["purchase_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
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
		
		$sql_fullview="SELECT * FROM purchase WHERE purchase_id='$get_pk_purchase_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		$sql_supplier="SELECT name from supplier WHERE supplier_id='$row_fullview[supplier_id]'";
		$result_supplier=mysqli_query($con,$sql_supplier) or die("sql error in sql_supplier ".mysqli_error($con));
		$row_supplier=mysqli_fetch_assoc($result_supplier);
										
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Purchase Full Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="display table table-striped table-hover">
								<tr><th>Purchase ID</th><td><?php echo $row_fullview["purchase_id"]; ?></td></tr>
								<tr><th>Supplier</th><td><?php echo $row_supplier["name"]; ?></td></tr>
								<tr><th>Date</th><td><?php echo $row_fullview["date"]; ?></td></tr>
								<tr><th>Total amount</th><td><?php echo $row_fullview["totalamount"]; ?></td></tr>
								<tr><th>Pay mode</th><td><?php echo $row_fullview["paymode"]; ?></td></tr>
								<tr><th>Pay Status</th><td><?php echo $row_fullview["paystatus"]; ?></td></tr>
								<tr><th>Slip photo</th><td><?php echo $row_fullview["slipphoto"]; ?></td></tr>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=purchase.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
											<a href="index.php?page=purchase.php&option=edit&pk_purchase_id=<?php echo $row_fullview["purchase_id"]; ?>"><button class="btn btn-info">Edit</button></a> 
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
		
		$sql_edit="SELECT * FROM purchase WHERE purchase_id='$get_pk_purchase_id'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for purchase Edit</div>
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
											<label for="txtpurchaseid">purchase ID</label>
											<input type="text" class="form-control" name="txtpurchaseid" id="txtpurchaseid" required placeholder="purchase ID" value="<?php echo $row_edit["purchase_id"]; ?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtsupplierid">Supplier ID</label>
											<select class="form-control" name="txtsupplierid" id="txtsupplierid" required placeholder="Supplier ID">
												<option value="select">Select Supplier </option>
												<?php
												$sql_load="SELECT supplier_id, name FROM supplier ";
												$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
												while($row_load=mysqli_fetch_assoc($result_load))
												{
													echo'<option value="'.$row_load["supplier_id"].'">'.$row_load["name"].'</option>';
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
											<label for="txtdate">Date</label>
											<input type="date" class="form-control" name="txtdate" id="txtdate" required placeholder="date" value="<?php echo $row_edit["date"]; ?>"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txttotalamount">Total amount</label>
											<input type="text" onkeypress="return isNumberKey(event)"  class="form-control" name="txttotalamount" id="txttotalamount" required placeholder="total amount" value="<?php echo $row_edit["totalamount"]; ?>" />
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
											<input type="text" class="form-control" name="txtpaymode" id="txtpaymode" required placeholder="Pay mode" value="<?php echo $row_edit["paymode"]; ?>" />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtpaystatus">Pay Status</label>
											<input type="text" class="form-control" name="txtpaystatus" id="txtpaystatus" required placeholder="Pay Status" value="<?php echo $row_edit["paystatus"]; ?>" />
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
										<label for="txtimage">Slipt photo</label>
										<input type="text" class="form-control"  name="txtimage" id="txtimage" required placeholder="image" value="<?php echo $row_edit["slipphoto"]; ?>"/>
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
												<a href="index.php?page=purchase.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
		
		$sql_delete="DELETE FROM purchase WHERE purchase_id='$get_pk_purchase_id'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete".mysqli_error($con));
		if($result_delete)
		{
				echo '<script>alert("Successfully Delete");
						window.location.href="index.php?page=purchase.php&option=view";</script>';
		}		
	}
}
?>
</body>