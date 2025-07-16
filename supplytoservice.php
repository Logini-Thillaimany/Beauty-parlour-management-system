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
	$sql_insert="INSERT INTO supplytoservice(supply_id,date,enterby)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtsupplyid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtdate"])."',
									'".mysqli_real_escape_string($con,$_POST["txtstaffid"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));

	$sql_view="SELECT supply_id,product_id,quantity FROM suppltoserviceitem WHERE supply_id='$_SESSION[session_supply]'";
	$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
	while($row_view=mysqli_fetch_assoc($result_view))
	{ 	
		$sql_product="SELECT expiretype from product WHERE product_id='$row_view[product_id]'";
		$result_product=mysqli_query($con,$sql_product) or die("sql error in sql_product ".mysqli_error($con));
		$row_product=mysqli_fetch_assoc($result_product);
		if($row_product["expiretype"]=="Yes")
		{
			$today=date("Y-m-d");
			$now_quantity=$row_view["quantity"];
			$sql_stock="SELECT * from stock WHERE product_id='$row_view[product_id]' AND purchase_id IN (SELECT purchase_id FROM purchaseproduct WHERE expiredate>='$today' AND product_id='$row_view[product_id]')";
			$result_stock=mysqli_query($con,$sql_stock) or die("sql error in sql_stock ".mysqli_error($con));
			while($row_stock=mysqli_fetch_assoc($result_stock))
			{
				if($now_quantity<=$row_stock["quantity"])
				{
					$new_quantity=$row_stock["quantity"]-$now_quantity;

					$sql_update="UPDATE stock SET quantity='$new_quantity' WHERE product_id='".mysqli_real_escape_string($con,$row_view["product_id"])."' AND purchase_id='".mysqli_real_escape_string($con,$row_stock["purchase_id"])."'";
					$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
					break;
				}
				else
				{
					$now_quantity=$now_quantity-$row_stock["quantity"];
					$new_quantity=0;

					$sql_update="UPDATE stock SET quantity='$new_quantity' WHERE product_id='".mysqli_real_escape_string($con,$row_view["product_id"])."' AND purchase_id='".mysqli_real_escape_string($con,$row_stock["purchase_id"])."'";
					$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
				}
			}			
		}
		else{
			$sql_stock="SELECT quantity from stockne WHERE product_id='".mysqli_real_escape_string($con,$row_view["product_id"])."'";
			$result_stock=mysqli_query($con,$sql_stock) or die("sql error in sql_stock ".mysqli_error($con));
			$row_stock=mysqli_fetch_assoc($result_stock);
			$new_quantity=$row_stock["quantity"]-$row_view["quantity"];

			$sql_update="UPDATE stockne SET quantity='$new_quantity' WHERE product_id='".mysqli_real_escape_string($con,$row_view["product_id"])."'";
			$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
		}

	}

	if($result_insert)
	{
		unset($_SESSION["session_supply"]);
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=supplytoservice.php&option=fullview&pk_supply_id='.$_POST["txtsupplyid"].'";</script>';
	}
}
//insert code end

//update code start
if(isset($_POST["btnsavechanges"]))
{
	$sql_update="UPDATE supplytoservice SET
							date='".mysqli_real_escape_string($con,$_POST["txtdate"])."',
							enterby='".mysqli_real_escape_string($con,$_POST["txtstaffid"])."'
							WHERE supply_id='".mysqli_real_escape_string($con,$_POST["txtsupplyid"])."'";
	$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
	if($result_update)
	{
		echo '<script>alert("Successfully Update");
						window.location.href="index.php?page=supplytoservice.php&option=view";</script>';
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
						<div class="card-title"> Form for supply to service Addition</div>
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
											<label for="txtsupplyid">Supply ID</label>
											<?php
											$generatedid=$_SESSION["session_supply"];
											
											?>
											<input type="text" class="form-control" name="txtsupplyid" id="txtsupplyid" required placeholder="Supply ID" value="<?php echo $generatedid;?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtdate">Date</label>
											<input type="date" value="<?php echo date("Y-m-d"); ?>" readonly class="form-control" name="txtdate" id="txtdate" required placeholder="Supply date"/>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- one row end -->
								
								<!-- fourth row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtstaffid">Enter By</label>
											<select  class="form-control" name="txtstaffid" id="txtstaffid" required placeholder="Enter By">
												<?php
												$sql_load="SELECT staff_id, name FROM staff WHERE staff_id='$system_user_id'";
												$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
												while($row_load=mysqli_fetch_assoc($result_load))
												{
													echo'<option value="'.$row_load["staff_id"].'">'.$row_load["name"].'</option>';
												}
												?>
											</select>
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
												<a href="index.php?page=supplytoserviceitem.php&option=add"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
						<h4 class="card-title">supply to service Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=supplytoserviceitem.php&option=add"><button class="btn btn-primary">Add supply to service</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>Supply ID</th>
										<th>Date</th>
										<th>Enter by</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$x=1;
									$sql_view="SELECT supply_id,date,enterby FROM supplytoservice ORDER BY date DESC";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{
										$sql_staff="SELECT name from staff WHERE staff_id='$row_view[enterby]'";
										$result_staff=mysqli_query($con,$sql_staff) or die("sql error in sql_staff ".mysqli_error($con));
										$row_staff=mysqli_fetch_assoc($result_staff);
										
										echo '<tr>';
											echo '<td>'.$x++.'</td>';
											echo '<td>'.$row_view["supply_id"].'</td>';
											echo '<td>'.$row_view["date"].'</td>';
											echo '<td>'.$row_staff["name"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=supplytoservice.php&option=fullview&pk_supply_id='.$row_view["supply_id"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
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
		$get_pk_supply_id=$_GET["pk_supply_id"];
		
		$sql_fullview="SELECT * FROM supplytoservice WHERE supply_id='$get_pk_supply_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		$sql_staff="SELECT name from staff WHERE staff_id='$row_fullview[enterby]'";
		$result_staff=mysqli_query($con,$sql_staff) or die("sql error in sql_staff".mysqli_error($con));
		$row_staff=mysqli_fetch_assoc($result_staff);
										
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">supply to service Full Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="display table table-striped table-hover">
								<tr><th>Supply ID</th><td><?php echo $row_fullview["supply_id"]; ?></td></tr>
								<tr><th>Date</th><td><?php echo $row_fullview["date"]; ?></td></tr>
								<tr><th>Enter By</th><td><?php echo $row_staff["name"]; ?></td></tr>
								<tr>			
									<td colspan="2">
										<center>
										<?php
										if(isset($_GET["page"]))
										{
										?>
											<a href="index.php?page=supplytoservice.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
											<a href="print.php?print=supplytoservice.php&option=fullview&pk_supply_id=<?php echo $row_fullview["supply_id"]; ?>" target="_blank"><button class="btn btn-success">Print</button></a> 
										<?php
										}
										?>
										</center>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- supply to service item table start -->
		 <div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Supply to Service item Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>supply ID</th>
										<th>product ID</th>
										<th>quantity</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$x=1;
									$sql_view="SELECT supply_id,product_id,quantity FROM suppltoserviceitem WHERE supply_id='$get_pk_supply_id'";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{ 	
										$sql_product="SELECT name from product WHERE product_id='$row_view[product_id]'";
										$result_product=mysqli_query($con,$sql_product) or die("sql error in sql_product ".mysqli_error($con));
										$row_product=mysqli_fetch_assoc($result_product);
										
										echo '<tr>';
											echo '<td>'.$x++.'</td>';
											echo '<td>'.$row_view["supply_id"].'</td>';
											echo '<td>'.$row_product["name"].'</td>';
											echo '<td>'.$row_view["quantity"].'</td>';
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
		$get_pk_supply_id=$_GET["pk_supply_id"];
		
		$sql_edit="SELECT * FROM supplytoservice WHERE supply_id='$get_pk_supply_id'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit ".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for supply to service Addition</div>
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
											<label for="txtsupplyid">Supply ID</label>
											<select class="form-control" name="txtsupplyid" id="txtsupplyid" required placeholder="Supply ID">
												<option value="select"><?php echo $row_edit["supply_id"]; ?></option>
												<?php
												$sql_loadsupply="SELECT supply_id FROM supplytoservice ";
												$result_loadsupply=mysqli_query($con,$sql_loadsupply) or die("sql error in sql_loadsupply".mysqli_error($con));
												while($row_loadsupply=mysqli_fetch_assoc($result_loadsupply))
												{
													echo'<option value="'.$row_loadsupply["supply_id"].'">'.$row_loadsupply["supply_id"].'</option>';
												}
												?>
											</select></div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtdate">Date</label>
											<input type="date" class="form-control" name="txtdate" id="txtdate" required placeholder="Supply date" value="<?php echo $row_edit["date"]; ?>"/>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- one row end -->
								
								<!-- fourth row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtstaffid">Enter By</label>
											<select  class="form-control" name="txtstaffid" id="txtstaffid" required placeholder="Enter By">
												<option value="select">Select Enter by </option>
												<?php
												$sql_load="SELECT staff_id, name FROM staff ";
												$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
												while($row_load=mysqli_fetch_assoc($result_load))
												{
													echo'<option value="'.$row_load["staff_id"].'">'.$row_load["name"].'</option>';
												}
												?>
											</select>
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
												<a href="index.php?page=supplytoservice.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
		$get_pk_supply_id=$_GET["pk_supply_id"];
		
		$sql_delete="DELETE FROM supplytoservice WHERE supply_id='$get_pk_supply_id'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete)
		{
			echo '<script>alert("Successfully Deleted");
				window.location.href="index.php?page=supplytoservice.php&option=view";</script>';
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