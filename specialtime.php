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
if($system_usertype=="Admin" || $system_usertype=="Clerk" || $system_usertype=="MakeupArtist" || $system_usertype=="SaloonService")
{//allow these users to access this page 

include("connection.php");
//insert code start
if(isset($_POST["btnsave"]))
{
	$submit=0;
	for($x=1;$x<=$_POST["txtloop"];$x++)
	{
		if(isset($_POST["txtpackageid_".$x]))
		{
			$sql_insert="INSERT INTO specialtime(date,starttime,endtime,enterby,package_id)
									VALUES('".mysqli_real_escape_string($con,$_POST["txtdate"])."',
											'".mysqli_real_escape_string($con,$_POST["txtstarttime"])."',
											'".mysqli_real_escape_string($con,$_POST["txtendtime"])."',
											'".mysqli_real_escape_string($con,$system_user_id)."',
											'".mysqli_real_escape_string($con,$_POST["txtpackageid_".$x])."')";
			$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
			$submit++;
		}
	}
	if($submit>0)
	{
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=specialtime.php&option=add";</script>';
	}
}
//insert code end

//update code start
if(isset($_POST["btnsavechanges"]))
{
	$sql_update="UPDATE specialtime SET
							endtime='".mysqli_real_escape_string($con,$_POST["txtendtime"])."',
							enterby='".mysqli_real_escape_string($con,$_POST["txtstaffid"])."'
					  WHERE date='".mysqli_real_escape_string($con,$_POST["txtdate"])."' AND
							starttime='".mysqli_real_escape_string($con,$_POST["txtstarttime"])."'AND
							package_id='".mysqli_real_escape_string($con,$_POST["txtpackageid"])."'";
	$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
	if($result_update)
	{
		echo '<script>alert("Successfully update");
						window.location.href="index.php?page=specialtime.php&option=view";</script>';
	}
}
//update code end
?>
<script>
function activeStarttime()
{
	var sel_date = document.getElementById("txtdate").value;
	document.getElementById("txtstarttime").value="";
	document.getElementById("txtstarttime").readOnly= true;
	document.getElementById("txtendtime").value="";
	document.getElementById("txtendtime").readOnly= true;

	if(sel_date!="")
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
			{
				var responsevalue = xmlhttp.responseText.trim();
		
				if(responsevalue=="Available")
				{
					document.getElementById("txtstarttime").value="";
					document.getElementById("txtstarttime").readOnly= false;
				}
				else
				{
					alert("This date is not available for special time allocation. Please select another date.");
					document.getElementById("txtdate").value="";
					document.getElementById("txtstarttime").value="";
					document.getElementById("txtstarttime").readOnly= true;
				}
			}
		};
		xmlhttp.open("GET", "ajaxpage.php?frompage=specialtime_date&ajax_date=" + sel_date, true);
		xmlhttp.send();
	}
}
</script>
<script>
function activeEndtime()
{
	var starttime = document.getElementById("txtstarttime").value;
	var endtime = document.getElementById("txtendtime");
	endtime.value = ""; // Set end time to the default empty value
	
	if(starttime!="")
	{
		endtime.removeAttribute("readonly");
		endtime.setAttribute("min", starttime); // Set the minimum value for end time to be the same as start time
	}
	else
	{
		endtime.setAttribute("readonly", "true");
	}
}
</script>
<script>
	function check_checkbox()
	{
		var totalLoop=document.getElementById("txtloop").value;
		for(var x=1;x<totalLoop; x++)
		{
			if(document.getElementById("txtpackageid_"+x).checked==true)
			{
				return true;
			}
		}
		alert("Please select Atleat one package!");
		return false;
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
						<div class="card-title"> Form for Special time allocation</div>
					</div>
					<div class="card-body">
						<div class="row">
							<!-- form start -->
							<form method="POST" action="" onsubmit="return check_checkbox()">
								
								<!-- one row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtdate">Date</label>
											<?php  
											$today = date("Y-m-d");
											$min_date = date("Y-m-d", strtotime($today . ' + 1 day'));
											$max_date = date("Y-m-d", strtotime($today . ' + 30 days'));											

											?>
											<input type="date" class="form-control" min="<?php echo $min_date; ?>" max="<?php echo $max_date; ?>"  onChange="activeStarttime()" name="txtdate" id="txtdate" required placeholder="Date"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											
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
											<label for="txtstarttime">Start time</label>
											<input type="time" class="form-control" name="txtstarttime" id="txtstarttime" onChange="activeEndtime()" readonly  required placeholder="Opening time"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtendtime">End time</label>
											<input type="time" class="form-control" readonly  name="txtendtime" id="txtendtime" required placeholder="Close time"/>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- second row end -->

								<hr>Package Details<br>
									<div class="row">
										<div class="col-md-12 col-lg-12">
											<table  class="table">
										<?php
											$sql_view="SELECT package_id,name FROM package  WHERE package_id IN (SELECT package_id FROM packageprice WHERE enddate IS NULL)";
											$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
											$totalpackage=mysqli_num_rows($result_view);
											$x=0;
											$y=1;
											echo '<tr>';
											while($row_view=mysqli_fetch_assoc($result_view))
											{	
												echo '<td>';
													echo '<input type="checkbox" name="txtpackageid_'.$y.'" id="txtpackageid_'.$y.'" value="'.$row_view["package_id"].'"> '.$row_view["name"];
												echo '</td>';

												$x++;
												$y++;
												if($x==$totalpackage){
													if($x%4==0){
														echo'</tr>';
													}
													else if($x%4==1){
														echo'<td></td><td></td><td></td></tr>';
													}
													else if($x%4==2){
														echo'<td></td><td></td></tr>';
													}
													else if($x%4==3){
														echo'<td></td></tr>';
													}
												}
												else
												{
													if($x%4==0){
														echo'</tr><tr>';
													}
												}
											}
											echo '<input type="hidden" name="txtloop" id="txtloop" value="'.$y.'">';
											?>
											</table>
										</div>
									</div>
								
								
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=specialtime.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
						<h4 class="card-title">Special time Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<?php 
							if($system_usertype=="Admin" || $system_usertype=="Clerk")
							{
							?>
								<a href="index.php?page=specialtime.php&option=add"><button class="btn btn-primary">Add Special time</button></a><br><br>
							<?php 
							}
							?>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>Date</th>
										<th>Start time</th>
										<th>End time</th>
										<th>Enter by</th>
										<th>Package</th>
										<?php 
										if($system_usertype=="Admin" || $system_usertype=="Clerk")
										{
										?>
											<th>Action</th>
										<?php 
										}	?>
									</tr>
								</thead>
								<tbody>
									<?php
									$x=1;
									$sql_view="SELECT DISTINCT date,starttime,endtime,enterby  FROM specialtime ORDER BY date DESC,starttime ASC";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{	
										$sql_enterby="SELECT name from staff WHERE staff_id='$row_view[enterby]'";
										$result_enterby=mysqli_query($con,$sql_enterby) or die("sql error in sql_enterby ".mysqli_error($con));
										$row_enterby=mysqli_fetch_assoc($result_enterby);

										echo '<tr>';
											echo '<td>'.$x++.'</td>';
											echo '<td>'.$row_view["date"].'</td>';
											echo '<td>'.$row_view["starttime"].'</td>';
											echo '<td>'.$row_view["endtime"].'</td>';
											echo '<td>'.$row_enterby["name"].'</td>';
											
											echo '<td>';
											$sql_view_package="SELECT package_id FROM specialtime WHERE date='$row_view[date]' AND starttime='$row_view[starttime]'";
											$result_view_package=mysqli_query($con,$sql_view_package) or die("sql error in sql_view_package ".mysqli_error($con));
											while($row_view_package=mysqli_fetch_assoc($result_view_package))
											{
												$sql_package="SELECT name from package WHERE package_id='$row_view_package[package_id]'";
												$result_package=mysqli_query($con,$sql_package) or die("sql error in sql_package ".mysqli_error($con));
												$row_package=mysqli_fetch_assoc($result_package);
												echo $row_package["name"].'</br>';
											}
											echo '</td>';
											
											if($system_usertype=="Admin" || $system_usertype=="Clerk")
											{
												echo '<td>';
												if($row_view["date"]>=date("Y-m-d"))
												{
													echo '<a onclick="return delete_confirm()" href="index.php?page=specialtime.php&option=delete&pk_date='.$row_view["date"].'&pk_starttime='.$row_view["starttime"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
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
	else if($_GET["option"]=="fullview")
	{
		//fullview table
		$get_pk_date=$_GET["pk_date"];
		$get_pk_starttime=$_GET["pk_starttime"];
		$get_pk_package_id=$_GET["pk_package_id"];
		
		$sql_fullview="SELECT * FROM specialtime WHERE date='$get_pk_date' AND starttime='$get_pk_starttime' AND package_id='$get_pk_package_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		$sql_enterby="SELECT name from staff WHERE staff_id='$row_fullview[enterby]'";
		$result_enterby=mysqli_query($con,$sql_enterby) or die("sql error in sql_enterby ".mysqli_error($con));
		$row_enterby=mysqli_fetch_assoc($result_enterby);
										
		$sql_package="SELECT name from package WHERE package_id='$row_fullview[package_id]'";
		$result_package=mysqli_query($con,$sql_package) or die("sql error in sql_package ".mysqli_error($con));
		$row_package=mysqli_fetch_assoc($result_package);
										
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Special time Full Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table  class="display table table-striped table-hover">
								<tr><th>Date</th><td><?php echo $row_fullview["date"]; ?></td></tr>
								<tr><th>Start time </th><td><?php echo $row_fullview["starttime"]; ?></td></tr>
								<tr><th>End time </th><td><?php echo $row_fullview["endtime"]; ?></td></tr>
								<tr><th>Enter By</th><td><?php echo $row_enterby["name"]; ?></td></tr>
								<tr><th>Package </th><td><?php echo $row_package["name"]; ?></td></tr>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=specialtime.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
											<a href="index.php?page=specialtime.php&option=edit&pk_date=<?php echo $row_fullview["date"]; ?>&pk_starttime=<?php echo $row_fullview["starttime"]; ?>&pk_package_id=<?php echo $row_fullview["package_id"]; ?>"><button class="btn btn-info">Edit</button></a> 
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
		$get_pk_date=$_GET["pk_date"];
		$get_pk_starttime=$_GET["pk_starttime"];
		$get_pk_package_id=$_GET["pk_package_id"];
		
		$sql_edit="SELECT * FROM specialtime WHERE date='$get_pk_date' AND starttime='$get_pk_starttime' AND package_id='$get_pk_package_id'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Edit Special time allocation</div>
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
											<label for="txtdate">Date</label>
											<input type="date" class="form-control" name="txtdate" id="txtdate" required placeholder="Date" value="<?php echo $row_edit["date"]; ?>"  readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtstarttime">Start time</label>
											<input type="time" class="form-control" name="txtstarttime" id="txtstarttime" required placeholder="Opening time" value="<?php echo $row_edit["starttime"]; ?>"  readonly />
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
											<label for="txtendtime">End time</label>
											<input type="time" class="form-control" name="txtendtime" id="txtendtime" required placeholder="Close time" value="<?php echo $row_edit["endtime"]; ?>" />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtstaffid">Enterby</label>
											<select  class="form-control" name="txtstaffid" id="txtstaffid" required placeholder="Enter By">
												<option value="select"><?php echo $row_edit["enterby"]; ?></option>
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
										<!-- column two end -->
									</div>
								</div>
								<!-- second row end -->
								
								<!-- third row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtpackageid">package id</label>
											<select class="form-control" name="txtpackageid" id="txtpackageid" required placeholder="Package id">
											<option value="select">Select Package </option>
												<?php
												$sql_load_package="SELECT package_id , name FROM package ";
												$result_load_package=mysqli_query($con,$sql_load_package) or die("sql error in sql_load_package".mysqli_error($con));
												while($row_load_package=mysqli_fetch_assoc($result_load_package))
												{
													echo'<option value="'.$row_load_package["package_id"].'">'.$row_load_package["name"].'</option>';
												}
												?>
											</select>
										</div>
										<!-- column one end -->
									</div>
								</div>
								<!-- third row end -->
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=specialtime.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
		$get_pk_date=$_GET["pk_date"];
		$get_pk_starttime=$_GET["pk_starttime"];
		
		$sql_delete="DELETE FROM specialtime WHERE date='$get_pk_date' AND starttime='$get_pk_starttime'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete)
		{
		echo '<script>alert("Successfully Deleted");
						window.location.href="index.php?page=specialtime.php&option=view";</script>';
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