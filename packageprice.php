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
	$sql_insert="INSERT INTO packageprice(package_id,startdate,enddate,price,offer)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtpackageid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtstartdate"])."',
									'".mysqli_real_escape_string($con,$_POST["txtenddate"])."',
									'".mysqli_real_escape_string($con,$_POST["txtprice"])."',
									'".mysqli_real_escape_string($con,$_POST["txtoffer"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
	if($result_insert)
	{
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=packageprice.php&option=add";</script>';
	}
}
//insert code end

//update code start
if(isset($_POST["btnsavechanges"]))
{
	$sql_update="UPDATE packageprice SET 
									enddate='".mysqli_real_escape_string($con,$_POST["txtenddate"])."',
									price='".mysqli_real_escape_string($con,$_POST["txtprice"])."',
									offer='".mysqli_real_escape_string($con,$_POST["txtoffer"])."'
									WHERE package_id='".mysqli_real_escape_string($con,$_POST["txtpackageid"])."' AND startdate='".mysqli_real_escape_string($con,$_POST["txtstartdate"])."'";
	$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
	if($result_update)
	{
		echo '<script>alert("Successfully update");
						window.location.href="index.php?page=packageprice.php&option=view";</script>';
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
						<div class="card-title"> Form for Add Offer </div>
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
											<label for="txtpackageid">Package </label>
											<select class="form-control" name="txtpackageid" id="txtpackageid" required placeholder="Package ID">
												<option value="select">Select Package</option>
												<?php
												$sql_load="SELECT package_id, name FROM package ";
												$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
												while($row_load=mysqli_fetch_assoc($result_load))
												{
													echo'<option value="'.$row_load["package_id"].'">'.$row_load["name"].'</option>';
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
											<label for="txtenddate">End date</label>
											<input type="date" class="form-control" name="txtenddate" id="txtenddate" required placeholder="End date"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtprice">Price</label>
											<input type="number" onkeypress="return isNumberKey(event)" class="form-control" name="txtprice" id="txtprice" required placeholder="Price"/>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- second row end -->
								
								<!-- fourth row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtoffer">offer</label>
											<input type="text" class="form-control" name="txtoffer" id="txtoffer" required placeholder="Offer"/>
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
												<a href="index.php?page=packageprice.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
						<h4 class="card-title">Package price Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=packageprice.php&option=add"><button class="btn btn-primary">Add Package price</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>package</th>
										<th>Start date</th>
										<th>End date</th>
										<th>Price</th>
										<th>Offer</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql_view="SELECT package_id,startdate,enddate,price,offer FROM packageprice";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{
										$sql_package="SELECT name from package WHERE package_id='$row_view[package_id]'";
										$result_package=mysqli_query($con,$sql_package) or die("sql error in sql_package ".mysqli_error($con));
										$row_package=mysqli_fetch_assoc($result_package);
										echo '<tr>';
											echo '<td>'.$row_package["name"].'</td>';
											echo '<td>'.$row_view["startdate"].'</td>';
											echo '<td>'.$row_view["enddate"].'</td>';
											echo '<td>'.$row_view["price"].'</td>';
											echo '<td>'.$row_view["offer"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=packageprice.php&option=edit&pk_package_id='.$row_view["package_id"].'&pk_startdate='.$row_view["startdate"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
												echo '<a onclick="return delete_confirm()" href="index.php?page=packageprice.php&option=delete&pk_package_id='.$row_view["package_id"].'&pk_startdate='.$row_view["startdate"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
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
		$get_pk_package_id=$_GET["pk_package_id"];
		$get_pk_startdate=$_GET["pk_startdate"];
		
		$sql_fullview="SELECT * FROM packageprice WHERE package_id='$get_pk_package_id' AND startdate='$get_pk_startdate'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		$sql_package="SELECT name from package WHERE package_id='$row_fullview[package_id]'";
		$result_package=mysqli_query($con,$sql_package) or die("sql error in sql_package ".mysqli_error($con));
		$row_package=mysqli_fetch_assoc($result_package);
										
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Package price full Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="display table table-striped table-hover">
								<tr><th>Package </th><td><?php echo $row_package["name"]; ?></td></tr>
								<tr><th>Start date</th><td><?php echo $row_fullview["startdate"]; ?></td></tr>
								<tr><th>End date</th><td><?php echo $row_fullview["enddate"]; ?></td></tr>
								<tr><th>Price</th><td><?php echo $row_fullview["price"]; ?></td></tr>
								<tr><th>Offer</th><td><?php echo $row_fullview["offer"]; ?></td></tr>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=packageprice.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
											<a href="index.php?page=packageprice.php&option=edit&pk_package_id=<?php echo $row_fullview["package_id"]; ?>&pk_startdate=<?php echo $row_fullview["startdate"];?>"><button class="btn btn-info">Edit</button></a> 
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
		$get_pk_package_id=$_GET["pk_package_id"];
		$get_pk_startdate=$_GET["pk_startdate"];
		
		$sql_edit="SELECT * FROM packageprice WHERE package_id='$get_pk_package_id' AND startdate='$get_pk_startdate'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit ".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Edit Offer </div>
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
											<label for="txtpackageid">Package</label>
											<select class="form-control" name="txtpackageid" id="txtpackageid" required placeholder="Package ID">
												<option value="select">Select Package</option>
												<?php
												$sql_load="SELECT package_id, name FROM package WHERE package_id='$row_edit[package_id]'";
												$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
												while($row_load=mysqli_fetch_assoc($result_load))
												{
													echo'<option value="'.$row_load["package_id"].'">'.$row_load["name"].'</option>';
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
											<label for="txtenddate">End date</label>
											<input type="date" class="form-control" name="txtenddate" id="txtenddate" required placeholder="End date" value="<?php echo $row_edit["enddate"]; ?>"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtprice">Price</label>
											<input type="number" onkeypress="return isNumberKey(event)"  class="form-control" name="txtprice" id="txtprice" required placeholder="Price" value="<?php echo $row_edit["price"]; ?>"/>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- second row end -->
								
								<!-- fourth row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtoffer">offer</label>
											<input type="text" class="form-control" name="txtoffer" id="txtoffer" required placeholder="Offer" value="<?php echo $row_edit["offer"]; ?>"/>
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
												<a href="index.php?page=packageprice.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
		$get_pk_package_id=$_GET["pk_package_id"];
		$get_pk_startdate=$_GET["pk_startdate"];
		
		$sql_delete="DELETE FROM packageprice WHERE package_id='$get_pk_package_id' AND startdate='$get_pk_startdate'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete)
		{
				echo '<script>alert("Successfully Delete");
						window.location.href="index.php?page=packageprice.php&option=view";</script>';
		}		
	}
}
?>
</body>