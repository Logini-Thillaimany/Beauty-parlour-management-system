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
	$sql_insert="INSERT INTO bookingpackage(booking_id,package_id)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtbookingid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtpackageid"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
	if($result_insert)
	{
		if(!isset($_SESSION["session_booking_id"]))
		{
			$_SESSION["session_booking_id"]=$_POST["txtbookingid"];
			$_SESSION["session_booking_type"]=$_POST["txtcategoryid"];

		}
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=bookingpackage.php&option=add";</script>';
	}
}
//insert code end

//update code start
if(isset($_POST["btnsavechanges"]))
{
	$sql_update="UPDATE bookingpackage SET
							starttime='".mysqli_real_escape_string($con,$_POST["txtstarttime"])."',
							endtime='".mysqli_real_escape_string($con,$_POST["txtendtime"])."'
					  WHERE booking_id='".mysqli_real_escape_string($con,$_POST["txtbookingid"])."' AND
							package_id='".mysqli_real_escape_string($con,$_POST["txtpackageid"])."'";
	$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
	if($result_update)
	{
		echo '<script>alert("Successfully UPDATE");
						window.location.href="index.php?page=bookingpackage.php&option=view";</script>';
	}
}
//update code end
?>
<script>
function activeSubcategory()
{
	var categoryid = document.getElementById("txtcategoryid").value;
	document.getElementById("txtsubcategoryid").innerHTML = '<option value="" disabled selected >Select Sub Category</option>';
	document.getElementById("txtpackageid").innerHTML = '<option value="" disabled selected >Select Package</option>';
	
	if (categoryid != "") {
		
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
			{
				var responsevalue = xmlhttp.responseText.trim();
				document.getElementById("txtsubcategoryid").innerHTML = responsevalue;
				
			}
		};
		xmlhttp.open("GET", "ajaxpage.php?frompage=bookingpackage_category&ajax_category_id=" + categoryid, true);
		xmlhttp.send();
	}
}
</script>
<script>
function activePackage()
{
	var subcategoryid = document.getElementById("txtsubcategoryid").value;
	document.getElementById("txtpackageid").innerHTML = '<option value="" disabled selected >Select Package</option>';
	
	if (subcategoryid != "") {
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
			{
				var responsevalue = xmlhttp.responseText.trim();
				document.getElementById("txtpackageid").innerHTML = responsevalue;
				
			}
		};
		xmlhttp.open("GET", "ajaxpage.php?frompage=bookingpackage_subcategory&ajax_subcategory_id=" + subcategoryid, true);
		xmlhttp.send();
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
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Booking packages</div>
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
											if(isset($_SESSION["session_booking_id"]))
											{
												$generatedid=$_SESSION["session_booking_id"];
											}
											else{
												$sql_generatedid="SELECT booking_id FROM bookingpackage ORDER BY booking_id DESC LIMIT 1";
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
											}
											?>
											<input type="text" class="form-control" name="txtbookingid" id="txtbookingid" required placeholder="Booking ID" value="<?php echo $generatedid;?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtcategoryid">Category</label>
											<select class="form-control" name="txtcategoryid" id="txtcategoryid" required onChange="activeSubcategory()" placeholder="category ID">
												
													<?php
													if(isset($_SESSION["session_booking_type"]))
													{
														$sql_load_category="SELECT category_id, name FROM packagecategory WHERE category_id='$_SESSION[session_booking_type]'";
													}
													else
													{
														echo '<option value="" disabled selected >Select Category</option>';
														$sql_load_category="SELECT category_id, name FROM packagecategory WHERE status='Active'";
													}
													
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
								<!-- one row end -->

								<!-- second row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtsubcategoryid">Sub Category</label>
											<select class="form-control" name="txtsubcategoryid" id="txtsubcategoryid" onChange="activePackage()" required placeholder="subcategory ID">
												<option value="" disabled selected >Select Sub Category</option>
												<?php 
													if(isset($_SESSION["session_booking_type"]))
													{
														$sql_load_subcategory="SELECT subcategory_id, name FROM packagesubcategory WHERE category_id='$_SESSION[session_booking_type]' AND status='Active'";
														$result_load_subcategory=mysqli_query($con,$sql_load_subcategory) or die("sql error in sql_load_subcategory".mysqli_error($con));
														while($row_load_subcategory=mysqli_fetch_assoc($result_load_subcategory))
														{
															echo'<option value="'.$row_load_subcategory["subcategory_id"].'">'.$row_load_subcategory["name"].'</option>';
														}
													}
												?>
											</select>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtpackageid">Package</label>
											<select class="form-control" name="txtpackageid" id="txtpackageid" required placeholder="Package ID">
												<option value="" disabled selected>Select Package</option>
													<?php
													
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
										<!---<div class="col-md-6 col-lg-6">									
											<label for="txtstarttime">Starting time</label>
											<input type="text" class="form-control" name="txtstarttime" id="txtstarttime" required placeholder="Start time"/>
										</div>-->
										<!-- column one end -->
										<!-- column two start -->
										<!---<div class="col-md-6 col-lg-6">
											<label for="txtendtime">End time</label>
											<input type="text" class="form-control" name="txtendtime" id="txtendtime" required placeholder="End time"/>
										</div>-->
										<!-- column two end -->
									</div>
								</div>
								<!-- second row end -->
								
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
		if(isset($_SESSION["session_booking_id"]))
		{
		?>
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
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$x=1;
								$sql_view="SELECT booking_id,package_id FROM bookingpackage";
								$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
								while($row_view=mysqli_fetch_assoc($result_view))
								{
									
									$sql_package="SELECT name from package WHERE package_id='$row_view[package_id]'";
									$result_package=mysqli_query($con,$sql_package) or die("sql error in sql_package ".mysqli_error($con));
									$row_package=mysqli_fetch_assoc($result_package);
									
									echo '<tr>';
										echo '<td>'.$x++.'</td>';
										echo '<td>'.$row_view["booking_id"].'</td>';
										echo '<td>'.$row_package["name"].'</td>';
										echo '<td>';
											echo '<a onclick="return delete_confirm()" href="index.php?page=bookingpackage.php&option=delete&pk_booking_id='.$row_view["booking_id"].'&pk_package_id='.$row_view["package_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
										echo '</td>';
									echo '</tr>';
								}
								if($x>1)
									{
										echo '<tr>';
											echo '<td>'.$x++.'</td>';
											echo '<td></td>';
											echo '<td></td>';
											echo '<td>';
												echo '<a href="index.php?page=booking.php&option=add"><button class="btn btn-success btn-sm"><i class="fa fa-pen"></i> finish </button></a> ';
											echo '</td>';
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
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
						<h4 class="card-title">Booking Package Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=bookingpackage.php&option=add"><button class="btn btn-primary">Add Booking Package</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>Booking ID</th>
										<th>Package </th>
										<th>Start time</th>
										<th>End Time</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql_view="SELECT booking_id,package_id,starttime,endtime FROM bookingpackage";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{	$sql_package="SELECT name from package WHERE package_id='$row_view[package_id]'";
										$result_package=mysqli_query($con,$sql_package) or die("sql error in sql_package ".mysqli_error($con));
										$row_package=mysqli_fetch_assoc($result_package);
										
										echo '<tr>';
											echo '<td>'.$row_view["booking_id"].'</td>';
											echo '<td>'.$row_package["name"].'</td>';
											echo '<td>'.$row_view["starttime"].'</td>';
											echo '<td>'.$row_view["endtime"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=bookingpackage.php&option=edit&pk_booking_id='.$row_view["booking_id"].'&pk_package_id='.$row_view["package_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
												echo '<a onclick="return delete_confirm()" href="index.php?page=bookingpackage.php&option=delete&pk_booking_id='.$row_view["booking_id"].'&pk_package_id='.$row_view["package_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
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
		$get_pk_package_id=$_GET["pk_package_id"];
		
		$sql_fullview="SELECT * FROM bookingpackage WHERE booking_id='$get_pk_booking_id' AND package_id='$get_pk_package_id'";
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
						<h4 class="card-title">Booking Package FULL Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="display table table-striped table-hover">
								<tr><th>Booking ID</th><td><?php echo $row_fullview["booking_id"]; ?></td></tr>
								<tr><th>Package</th><td><?php echo $row_package["name"]; ?></td></tr>
								<tr><th>Start time</th><td><?php echo $row_fullview["starttime"]; ?></td></tr>
								<tr><th>End Time</th><td><?php echo $row_fullview["endtime"]; ?></td></tr>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=bookingpackage.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
											<a href="index.php?page=bookingpackage.php&option=edit&pk_booking_id=<?php echo $row_fullview["booking_id"]; ?>&pk_package_id=<?php echo $row_fullview["package_id"]; ?>"><button class="btn btn-info">Edit</button></a> 
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
		$get_pk_package_id=$_GET["pk_package_id"];
		
		$sql_edit="SELECT * FROM bookingpackage WHERE booking_id='$get_pk_booking_id' AND package_id='$get_pk_package_id'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit ".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Edit Booking packages</div>
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
											<select class="form-control" name="txtbookingid" id="txtbookingid" required placeholder="Booking ID">
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
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtpackageid">Package</label>
											<select class="form-control" name="txtpackageid" id="txtpackageid" required placeholder="Package ID" value="<?php echo $row_edit["package_id"]; ?>" readonly >
												<option value="select">Select Package </option>
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
										<!-- column two end -->
									</div>
								</div>
								<!-- one row end -->
								
								<!-- second row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtstarttime">Starting time</label>
											<input type="text" class="form-control" name="txtstarttime" id="txtstarttime" required placeholder="Start time" value="<?php echo $row_edit["starttime"]; ?>"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtendtime">End time</label>
											<input type="text" class="form-control" name="txtendtime" id="txtendtime" required placeholder="End time" value="<?php echo $row_edit["endtime"]; ?>"/>
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
												<a href="index.php?page=bookingpackage.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
		$get_pk_package_id=$_GET["pk_package_id"];
		
		$sql_delete="DELETE FROM bookingpackage WHERE booking_id='$get_pk_booking_id' AND package_id='$get_pk_package_id'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete)
		{
			echo '<script>alert("Successfully delete");
						window.location.href="index.php?page=bookingpackage.php&option=add";</script>';

		}		
	}
}
?>
</body>