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
		$sql_insert="INSERT INTO staff(staff_id,name,nic,dob,gender,mobile,address,designation,jointdate)
								VALUES('".mysqli_real_escape_string($con,$_POST["txtstaffid"])."',
										'".mysqli_real_escape_string($con,$_POST["txtname"])."',
										'".mysqli_real_escape_string($con,$_POST["txtnic"])."',
										'".mysqli_real_escape_string($con,$_POST["txtdob"])."',
										'".mysqli_real_escape_string($con,$_POST["txtgender"])."',
										'".mysqli_real_escape_string($con,$_POST["txtmobile"])."',
										'".mysqli_real_escape_string($con,$_POST["txtaddress"])."',
										'".mysqli_real_escape_string($con,$_POST["txtdesignation"])."',
										'".mysqli_real_escape_string($con,$_POST["txtjoindate"])."')";
		$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
		
		//insert into login
		$password=md5($_POST["txtnic"]);
		$sql_insert_login="INSERT INTO login(user_id,username,password,usertype,attempt,code,status)
								VALUES('".mysqli_real_escape_string($con,$_POST["txtstaffid"])."',
										'".mysqli_real_escape_string($con,$_POST["txtnic"])."',
										'".mysqli_real_escape_string($con,$password)."',
										'".mysqli_real_escape_string($con,$_POST["txtdesignation"])."',
										'".mysqli_real_escape_string($con,0)."',
										'".mysqli_real_escape_string($con,0)."',
										'".mysqli_real_escape_string($con,"Active")."')";
		$result_insert_login=mysqli_query($con,$sql_insert_login) or die("sql error in sql_insert_login ".mysqli_error($con));

		if($_POST["txtdesignation"]=="MakeupArtist" || $_POST["txtdesignation"]=="SaloonService")
		{
			//insert package 
			$totalLoop=$_POST["txtloop"];
			for($x=1;$x<$totalLoop;$x++)
			{
				if(isset($_POST["txtpackageid_".$x]))
				{
					$sql_insert="INSERT INTO staffpackage(staff_id,package_id,status)
										VALUES('".mysqli_real_escape_string($con,$_POST["txtstaffid"])."',
												'".mysqli_real_escape_string($con,$_POST["txtpackageid_".$x])."',
												'".mysqli_real_escape_string($con,"Active")."')";
					$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
				}
			}
		}

		if($result_insert)
		{
			echo '<script>alert("Successfully Insert");
				  window.location.href="index.php?page=staff.php&option=add";</script>';
		}
	}
	//insert code end

	//update code start
	if(isset($_POST["btnsavechanges"]))
	{
		$sql_update="UPDATE staff SET
									name='".mysqli_real_escape_string($con,$_POST["txtname"])."',
									nic='".mysqli_real_escape_string($con,$_POST["txtnic"])."',
									dob='".mysqli_real_escape_string($con,$_POST["txtdob"])."',
									gender='".mysqli_real_escape_string($con,$_POST["txtgender"])."',
									mobile='".mysqli_real_escape_string($con,$_POST["txtmobile"])."',
									address='".mysqli_real_escape_string($con,$_POST["txtaddress"])."',
									designation='".mysqli_real_escape_string($con,$_POST["txtdesignation"])."',
									jointdate='".mysqli_real_escape_string($con,$_POST["txtjoindate"])."'
									WHERE staff_id='".mysqli_real_escape_string($con,$_POST["txtstaffid"])."'";
		$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
		
		$sql_update_login="UPDATE login SET
									usertype='".mysqli_real_escape_string($con,$_POST["txtdesignation"])."'
									WHERE user_id='".mysqli_real_escape_string($con,$_POST["txtstaffid"])."'";
		$result_update_login=mysqli_query($con,$sql_update_login) or die("sql error in sql_update_login ".mysqli_error($con));
		if($result_update)
		{
			echo '<script>alert("Successfully Update");
				  window.location.href="index.php?page=staff.php&option=fullview&pk_staff_id='.$_POST["txtstaffid"].'";</script>';
		}
	}
	//update code end
	?>
	<script>
		function check_username()
		{
			var username=document.getElementById("txtnic").value;
			document.getElementById("txtjoindate").value="";
			document.getElementById("txtjoindate").readOnly=true;
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() 
			{
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
				{
					var responsevalue = xmlhttp.responseText.trim();
					if(responsevalue=="Yes")
					{
						alert("Sorry, This NIC is already exist!");
						document.getElementById("txtnic").value="";
						document.getElementById("txtdob").value="";
						document.getElementById("txtgender").value="";
					}
					else
					{
						nicnumber();
						/*document.getElementById("txtjoindate").value="";
						document.getElementById("txtjoindate").readOnly=false;
						assign_minDate();*/
					}
				}
			};
			xmlhttp.open("GET", "ajaxpage.php?frompage=staff_customer_username&ajax_username=" + username, true);
			xmlhttp.send();
		}
	
	</script>
	<script>
		function assign_minDate()
		{
			if(document.getElementById("txtdob").value!="")
			{
				var dob=document.getElementById("txtdob").value;
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
					{
						var responsevalue = xmlhttp.responseText.trim();
						document.getElementById("txtjoindate").value="";
						document.getElementById("txtjoindate").readOnly=false;
						document.getElementById("txtjoindate").min=responsevalue;
						
					}
				};
				xmlhttp.open("GET", "ajaxpage.php?frompage=staff_jointdate&ajax_dob=" + dob, true);
				xmlhttp.send();
			}
		}
	</script>
	<script>
		function check_phonenumber(mobiletextbox,optionvalue)
		{
			var staff_id=document.getElementById("txtstaffid").value;
			var mobileno=document.getElementById(mobiletextbox).value;
			
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() 
			{
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
				{
					var responsevalue = xmlhttp.responseText.trim();
					if(responsevalue=="Yes")
					{
						alert("Sorry, This mobile no is already exist!");
						document.getElementById("txtmobile").value="";
					}
					else
					{
						phonenumber(mobiletextbox);
					}
				}
			};
			xmlhttp.open("GET", "ajaxpage.php?frompage=staff_mobile&ajax_option=" + optionvalue+"&ajax_staff_id="+staff_id+"&ajax_mobile="+mobileno, true);
			xmlhttp.send();
		}
	
	</script>

<script>
		function load_package()
		{
			var designation=document.getElementById("txtdesignation").value;
			if(designation=="MakeupArtist" || designation=="SaloonService")
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
					{
						var responsevalue = xmlhttp.responseText.trim();
						document.getElementById("load_package").innerHTML=responsevalue;
					}
				};
				xmlhttp.open("GET", "ajaxpage.php?frompage=staff_package&ajax_designation=" + designation, true);
				xmlhttp.send();
			}
			else{
				document.getElementById("load_package").innerHTML="";
			}
		}
	
	</script>

<script>
	function check_checkbox()
	{
		var designation=document.getElementById("txtdesignation").value;
		if(designation=="MakeupArtist" || designation=="SaloonService")
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
		else{
			return true;
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
							<div class="card-title">Form for Staff Addition</div>
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
												<label for="email2">Staff ID</label>
												<?php
													$sql_generatedid="SELECT staff_id FROM staff ORDER BY staff_id DESC LIMIT 1";
													$result_generatedid=mysqli_query($con,$sql_generatedid) or die("sql error in sql_generatedid ".mysqli_error($con));
													if(mysqli_num_rows($result_generatedid)==1)
													{// for  except from the first submission
														$row_generatedid=mysqli_fetch_assoc($result_generatedid);
														$generatedid=++$row_generatedid["staff_id"];
													}
													else
													{//For first time submission
														$generatedid="ST001";
													}
												?>
												<input type="text" class="form-control" name="txtstaffid" id="txtstaffid" required placeholder="Type your Staff ID" value="<?php echo $generatedid;?>" readonly />
											</div>
											<!-- column one end -->
											<!-- column two start -->
											<div class="col-md-6 col-lg-6">
												<label for="email2">Name</label>
												<input type="text" onkeypress="return isTextKey(event)" class="form-control" name="txtname" id="txtname" required placeholder="Type your Staff Name"/>
											</div>
											<!-- column two end -->
										</div>
									</div>
									<!-- one row end -->
									
									<!-- one row start -->
									<div class="form-group">
										<div class="row">
											<!-- column one start -->
											<div class="col-md-6 col-lg-6">									
												<label for="email2">NIC</label>
												<input type="text" class="form-control" onblur="check_username()" name="txtnic" id="txtnic" required placeholder="Type your NIC"/>
											</div>
											<!-- column one end -->
											<!-- column two start -->
											<div class="col-md-6 col-lg-6">
												<label for="email2">DOB</label>
												<input type="date" class="form-control" name="txtdob" id="txtdob" required placeholder="Type your DOB" readonly />
											</div>
											<!-- column two end -->
										</div>
									</div>
									<!-- one row end -->
									
									<!-- one row start -->
									<div class="form-group">
										<div class="row">
											<!-- column one start -->
											<div class="col-md-6 col-lg-6">									
												<label for="email2">Gender</label>
												<input type="text" class="form-control" name="txtgender" id="txtgender" required placeholder="Type your Gender" readonly />
											</div>
											<!-- column one end -->
											<!-- column two start -->
											<div class="col-md-6 col-lg-6">
												<label for="email2">Mobile</label>
												<input type="text" onkeypress="return isNumberKey(event)"  onblur="check_phonenumber('txtmobile','add')" class="form-control" name="txtmobile" id="txtmobile" required placeholder="Type your Mobile"/>
											</div>
											<!-- column two end -->
										</div>
									</div>
									<!-- one row end -->
									
									<!-- one row start -->
									<div class="form-group">
										<div class="row">
											<!-- column one start -->
											<div class="col-md-6 col-lg-6">									
												<label for="email2">Designation</label>
												<select class="form-control" name="txtdesignation" id="txtdesignation" onChange="load_package()" required placeholder="Type your Designation">
													<option value="" disabled selected>Select</option>
													<?php
													if($system_usertype=="Admin")
													{
														$designation_array=array("Admin","Clerk","MakeupArtist","SaloonService");
															
													}
													else
													{
														$designation_array=array("MakeupArtist","SaloonService");
															
													}
														for($x=0;$x<count($designation_array);$x++){
															echo'<option value="'.$designation_array[$x].'">'.$designation_array[$x].'</option>';
														}
															
													?>
												</select>
											</div>
											<!-- column one end -->
											<!-- column two start -->
											<div class="col-md-6 col-lg-6">
												<label for="email2">Join Date</label>
												<input type="date" class="form-control" name="txtjoindate" id="txtjoindate" max="<?php echo date("Y-m-d"); ?>" readonly required placeholder="Type your Join Date"/>
											</div>
											<!-- column two end -->
										</div>
									</div>
									<!-- one row end -->
									
									<!-- one row start -->
									<div class="form-group">
										<div class="row">
											<!-- column one start -->
											<div class="col-md-6 col-lg-6">									
												<label for="email2">Address</label>
												<textarea class="form-control" name="txtaddress" id="txtaddress" required placeholder="Type your Address"></textarea>
											</div>
											<!-- column one end -->
										</div>
									</div>
									<!-- one row end -->

									<!-- package details -->
									<div id="load_package"></div>
									
									
									<!-- button start -->
									<div class="form-group">
										<div class="row">
											<div class="col-md-6 col-lg-12">	
												<center>
													<a href="index.php?page=staff.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
							<h4 class="card-title">Staff Details</h4>
						</div>
						<div class="card-body">
							<div class="table-responsive">
							<?php
							if(isset($_GET["page"])) 
							{
								if($system_usertype=="Admin" || $system_usertype=="Clerk")
								{
								?>
									<a href="index.php?page=staff.php&option=add"><button class="btn btn-primary">Add Staff</button></a> &nbsp;&nbsp;&nbsp;
									<a href="print.php?print=staff.php&option=view" target="_blank"><button class="btn btn-primary">Print Staff</button></a><br><br>
								<?php 
								}
							}
							?>
								<table id="basic-datatables" class="display table table-striped table-hover">
									<thead>
										<tr>
											<th>Staff ID</th>
											<th>Name</th>
											<th>NIC</th>
											<th>Designation</th>
											<th>Mobile</th>
											<?php
											if(isset($_GET["page"]))
											{
											?>
												<th>Action</th>
											<?php
											}
											?>
										</tr>
									</thead>
									<tbody>
										<?php
										$sql_view="SELECT staff_id,name,nic,designation,mobile FROM staff";
										$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
										while($row_view=mysqli_fetch_assoc($result_view))
										{
											$sql_loginstatus="SELECT status FROM login WHERE user_id='$row_view[staff_id]'";
											$result_loginstatus=mysqli_query($con,$sql_loginstatus) or die("sql error in sql_loginstatus ".mysqli_error($con));
											$row_loginstatus=mysqli_fetch_assoc($result_loginstatus);
											
											
											echo '<tr>';
												echo '<td>'.$row_view["staff_id"].'</td>';
												echo '<td>'.$row_view["name"].'</td>';
												echo '<td>'.$row_view["nic"].'</td>';
												echo '<td>'.$row_view["designation"].'</td>';
												echo '<td>0'.$row_view["mobile"].'</td>';
												if(isset($_GET["page"]))
												{
												echo '<td>';
													echo '<a href="index.php?page=staff.php&option=fullview&pk_staff_id='.$row_view["staff_id"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
													if($row_loginstatus["status"]=="Active")
													{
														if($system_usertype=="Admin" || $system_usertype=="Clerk")
														{
															echo '<a href="index.php?page=staff.php&option=edit&pk_staff_id='.$row_view["staff_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
														}
														if($system_usertype=="Admin")
														{
															echo '<a onclick="return delete_confirm()" href="index.php?page=staff.php&option=delete&pk_staff_id='.$row_view["staff_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
														}
													}
													if($row_loginstatus["status"]=="Deleted" && $system_usertype=="Admin")
													{
														echo '<a onclick="return reactivate_confirm()" href="index.php?page=staff.php&option=reactivate&pk_staff_id='.$row_view["staff_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-undo"></i> Re-activate</button></a> ';
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
			$get_pk_staff_id=$_GET["pk_staff_id"];
			
			$sql_fullview="SELECT * FROM staff WHERE staff_id='$get_pk_staff_id'";
			$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
			$row_fullview=mysqli_fetch_assoc($result_fullview);
			
			$sql_loginstatus="SELECT status FROM login WHERE user_id='$row_fullview[staff_id]'";
			$result_loginstatus=mysqli_query($con,$sql_loginstatus) or die("sql error in sql_loginstatus ".mysqli_error($con));
			$row_loginstatus=mysqli_fetch_assoc($result_loginstatus);
			
			?>
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">Staff Full Details</h4>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="display table table-striped table-hover">
									<tr><th>Staff ID</th><td><?php echo $row_fullview["staff_id"]; ?></td></tr>
									<tr><th>Name</th><td><?php echo $row_fullview["name"]; ?></td></tr>
									<tr><th>NIC</th><td><?php echo $row_fullview["nic"]; ?></td></tr>
									<tr><th>Date of birth</th><td><?php echo $row_fullview["dob"]; ?></td></tr>
									<tr><th>Gender</th><td><?php echo $row_fullview["gender"]; ?></td></tr>
									<tr><th>Designation</th><td><?php echo $row_fullview["designation"]; ?></td></tr>
									<tr><th>Address</th><td><?php echo $row_fullview["address"]; ?></td></tr>
									<tr><th>Mobile</th><td>0<?php echo $row_fullview["mobile"]; ?></td></tr>
									<tr><th>Joint Date</th><td><?php echo $row_fullview["jointdate"]; ?></td></tr>
									<?php
									if(isset($_GET["page"]))
									{
									?>
									<tr>								
										<td colspan="2">
											<center>
												<a href="index.php?page=staff.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
												<?php
												if($row_loginstatus["status"]=="Active" && ($system_usertype=="Admin" || $system_usertype=="Clerk"))
												{
												?>
													<a href="index.php?page=staff.php&option=edit&pk_staff_id=<?php echo $row_fullview["staff_id"]; ?>"><button class="btn btn-info">Edit</button></a>
													<a href="print.php?print=staff.php&option=fullview&pk_staff_id=<?php echo $row_fullview["staff_id"]; ?>" target="_blank"><button class="btn btn-success">Print</button></a> 
												<?php
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

			<!---Package view details -->
			<?php
			if($row_fullview["designation"]=="MakeupArtist" || $row_fullview["designation"]=="SaloonService")
			{
			?>
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">Staff package Details</h4>
							</div>
							<div class="card-body">
								<div class="table-responsive">
								<?php
								if($row_loginstatus["status"]=="Active" && ($system_usertype=="Admin" || $system_usertype=="Clerk"))
								{
								?>
									<a href="index.php?page=staffpackage.php&option=add&pk_staff_id=<?php echo $row_fullview["staff_id"]; ?>"><button class="btn btn-primary">Add Staff staffpackage</button></a><br><br>
								<?php
								}
								?>
									<table id="basic-datatables" class="display table table-striped table-hover">
										<thead>
											<tr>
												<th>#</th>
												<th>package ID</th>
												<th>status</th>
												<th>action</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$x=1;
											$sql_view="SELECT staff_id,package_id,status FROM staffpackage WHERE staff_id='$row_fullview[staff_id]'";
											$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
											while($row_view=mysqli_fetch_assoc($result_view))
											{	
												
												$sql_package="SELECT name from package WHERE package_id='$row_view[package_id]'";
												$result_package=mysqli_query($con,$sql_package) or die("sql error in sql_package ".mysqli_error($con));
												$row_package=mysqli_fetch_assoc($result_package);
												
				
				
												echo '<tr>';
													echo '<td>'.$x++.'</td>';
													echo '<td>'.$row_package["name"].'</td>';
													echo '<td>'.$row_view["status"].'</td>';
													echo '<td>';
													if($row_loginstatus["status"]=="Active" && $row_view["status"]=="Active" && ($system_usertype=="Admin" || $system_usertype=="Clerk"))
													{	
														echo '<a onclick="return delete_confirm()" href="index.php?page=staffpackage.php&option=delete&pk_staff_id='.$row_view["staff_id"].'&pk_package_id='.$row_view["package_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
													}
													if($row_loginstatus["status"]=="Active" && $row_view["status"]=="Deleted" && ($system_usertype=="Admin" || $system_usertype=="Clerk"))
													{
														echo '<a onclick="return reactivate_confirm()" href="index.php?page=staffpackage.php&option=reactivate&pk_staff_id='.$row_view["staff_id"].'&pk_package_id='.$row_view["package_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-undo"></i> Re-activate</button></a> ';
													}
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
		}
		else if($_GET["option"]=="edit")
		{
			//edit form
			$get_pk_staff_id=$_GET["pk_staff_id"];
			
			$sql_edit="SELECT * FROM staff WHERE staff_id='$get_pk_staff_id'";
			$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit ".mysqli_error($con));
			$row_edit=mysqli_fetch_assoc($result_edit);
			
			?>
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<div class="card-title">Form for Staff Edit</div>
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
												<label for="email2">Staff ID</label>
												<input type="text" class="form-control" name="txtstaffid" id="txtstaffid" required placeholder="Type your Staff ID" value="<?php echo $row_edit["staff_id"]; ?>" readonly />
											</div>
											<!-- column one end -->
											<!-- column two start -->
											<div class="col-md-6 col-lg-6">
												<label for="email2">Name</label>
												<input type="text" onkeypress="return isTextKey(event)" class="form-control" name="txtname" id="txtname" required placeholder="Type your Staff Name" value="<?php echo $row_edit["name"]; ?>" />
											</div>
											<!-- column two end -->
										</div>
									</div>
									<!-- one row end -->
									
									<!-- one row start -->
									<div class="form-group">
										<div class="row">
											<!-- column one start -->
											<div class="col-md-6 col-lg-6">									
												<label for="email2">NIC</label>
												<input type="text"   class="form-control" name="txtnic" id="txtnic" required placeholder="Type your NIC" readonly  value="<?php echo $row_edit["nic"]; ?>" />
											</div>
											<!-- column one end -->
											<!-- column two start -->
											<div class="col-md-6 col-lg-6">
												<label for="email2">DOB</label>
												<input type="date" class="form-control" name="txtdob" id="txtdob" required placeholder="Type your DOB" readonly value="<?php echo $row_edit["dob"]; ?>" />
											</div>
											<!-- column two end -->
										</div>
									</div>
									<!-- one row end -->
									
									<!-- one row start -->
									<div class="form-group">
										<div class="row">
											<!-- column one start -->
											<div class="col-md-6 col-lg-6">									
												<label for="email2">Gender</label>
												<input type="text" class="form-control" name="txtgender" id="txtgender" required placeholder="Type your Gender" readonly value="<?php echo $row_edit["gender"]; ?>" />
											</div>
											<!-- column one end -->
											<!-- column two start -->
											<div class="col-md-6 col-lg-6">
												<label for="email2">Mobile</label>
												<input type="text"  onkeypress="return isNumberKey(event)" onblur="check_phonenumber('txtmobile','edit')"  class="form-control" name="txtmobile" id="txtmobile" required placeholder="Type your Mobile" value="0<?php echo $row_edit["mobile"]; ?>" />
											</div>
											<!-- column two end -->
										</div>
									</div>
									<!-- one row end -->
									
									<!-- one row start -->
									<div class="form-group">
										<div class="row">
											<!-- column one start -->
											<div class="col-md-6 col-lg-6">									
												<label for="email2">Designation</label>
												<Select class="form-control" name="txtdesignation" id="txtdesignation" required placeholder="Type your Designation"  >
												<?php
													if($system_usertype=="Admin")
													{
														$designation_array=array("Admin","Clerk","MakeupArtist","SaloonService");
															
													}
													else
													{
														if($row_edit["designation"]=="Admin" || $row_edit["designation"]=="Clerk")
														{
															$designation_array=array($row_edit["designation"]);
														}
														else
														{
															$designation_array=array("MakeupArtist","SaloonService");
														}	
													}
													
													for($x=0;$x<count($designation_array);$x++)
													{
														if($row_edit["designation"]==$designation_array[$x])
														{
															echo'<option selected value="'.$designation_array[$x].'">'.$designation_array[$x].'</option>';
														}
														else
														{
															echo'<option value="'.$designation_array[$x].'">'.$designation_array[$x].'</option>';
														}
													}
															
													?>
												</select>
											</div>
											<!-- column one end -->
											<!-- column two start -->
											<div class="col-md-6 col-lg-6">
												<label for="email2">Join Date</label>
												<input type="date" class="form-control" name="txtjoindate" id="txtjoindate" required placeholder="Type your Join Date" readOnly value="<?php echo $row_edit["jointdate"]; ?>" />
											</div>
											<!-- column two end -->
										</div>
									</div>
									<!-- one row end -->
									
									<!-- one row start -->
									<div class="form-group">
										<div class="row">
											<!-- column one start -->
											<div class="col-md-6 col-lg-6">									
												<label for="email2">Address</label>
												<textarea class="form-control" name="txtaddress" id="txtaddress" required placeholder="Type your Address"><?php echo $row_edit["address"]; ?></textarea>
											</div>
											<!-- column one end -->
										</div>
									</div>
									<!-- one row end -->
									
									
									<!-- button start -->
									<div class="form-group">
										<div class="row">
											<div class="col-md-6 col-lg-12">	
												<center>
													<a href="index.php?page=staff.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
													<input type="reset" class="btn btn-danger" name="btncancel" id="btncancel"  value="Cancel"/>
													<input type="submit" class="btn btn-success" name="btnsavechanges" id="btnsavechanges"  value="Save Changes"/>
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
			$get_pk_staff_id=$_GET["pk_staff_id"];
			
			$sql_delete="UPDATE login SET status='Deleted' WHERE user_id='$get_pk_staff_id'";
			$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
			if($result_delete)
			{
				echo '<script>alert("Successfully Deleted");
					  window.location.href="index.php?page=staff.php&option=view";</script>';
			}
		}
		
		else if($_GET["option"]=="reactivate")
		{
			//reactivate code
			$get_pk_staff_id=$_GET["pk_staff_id"];
			
			$sql_delete="UPDATE login SET status='Active' WHERE user_id='$get_pk_staff_id'";
			$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
			if($result_delete)
			{
				echo '<script>alert("Successfully reactivated");
					  window.location.href="index.php?page=staff.php&option=view";</script>';
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