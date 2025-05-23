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
		$sql_insert="INSERT INTO supplier(supplier_id,name,mobile,address,email,status)
								VALUES('".mysqli_real_escape_string($con,$_POST["txtsupplierid"])."',
										'".mysqli_real_escape_string($con,$_POST["txtname"])."',
										'".mysqli_real_escape_string($con,$_POST["txtmobile"])."',
										'".mysqli_real_escape_string($con,$_POST["txtaddress"])."',
										'".mysqli_real_escape_string($con,$_POST["txtemail"])."',
										'".mysqli_real_escape_string($con,"Active")."')";
		$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
		if($result_insert)
		{
			echo '<script>alert("Successfully Insert");
							window.location.href="index.php?page=supplier.php&option=add";</script>';
		}
	}
	//insert code end

	//Update code start
	if(isset($_POST["btnsavechanges"]))
	{
		$sql_update="UPDATE supplier SET
									name='".mysqli_real_escape_string($con,$_POST["txtname"])."',
									mobile='".mysqli_real_escape_string($con,$_POST["txtmobile"])."',
									address='".mysqli_real_escape_string($con,$_POST["txtaddress"])."',
									email='".mysqli_real_escape_string($con,$_POST["txtemail"])."'
								WHERE supplier_id='".mysqli_real_escape_string($con,$_POST["txtsupplierid"])."'";
		$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
		if($result_update)
		{
			echo '<script>alert("Successfully UPDATE");
							window.location.href="index.php?page=supplier.php&option=fullview&pk_supplier_id='.$_POST["txtsupplierid"].'";</script>';
		}
	}
	//Update code end
	?>
	<script>
		function check_phonenumber(mobiletextbox,optionvalue)
		{
			var supplier_id=document.getElementById("txtsupplierid").value;
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
			xmlhttp.open("GET", "ajaxpage.php?frompage=supplier_mobile&ajax_option=" + optionvalue+"&ajax_supplier_id="+supplier_id+"&ajax_mobile="+mobileno, true);
			xmlhttp.send();
		}
	
	</script>
	<script>
		function check_email(emailtextbox,optionvalue)
		{
			var supplier_id=document.getElementById("txtsupplierid").value;
			var email=document.getElementById(emailtextbox).value;
			
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() 
			{
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
				{
					var responsevalue = xmlhttp.responseText.trim();
					if(responsevalue=="Yes")
					{
						alert("Sorry, This email is already exist!");
						document.getElementById("txtemail").value="";
					}
					else
					{
						emailvalidation();
					}
				}
			};
			xmlhttp.open("GET", "ajaxpage.php?frompage=supplier_email&ajax_option=" + optionvalue+"&ajax_supplier_id="+supplier_id+"&ajax_email="+email, true);
			xmlhttp.send();
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
							<div class="card-title"> Form for Supplier Addition</div>
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
												<label for="txtsupplierid">Supplier ID</label>
												<?php
													$sql_generatedid="SELECT supplier_id FROM supplier ORDER BY supplier_id DESC LIMIT 1";
													$result_generatedid=mysqli_query($con,$sql_generatedid) or die("sql error in sql_generatedid ".mysqli_error($con));
													if(mysqli_num_rows($result_generatedid)==1)
													{// for  except from the first submission
														$row_generatedid=mysqli_fetch_assoc($result_generatedid);
														$generatedid=++$row_generatedid["supplier_id"];
													}
													else
													{//For first time submission
														$generatedid="SU001";
													}
												?>
												<input type="text" class="form-control" name="txtsupplierid" id="txtsupplierid" required placeholder="Supplier ID" value="<?php echo $generatedid;?>" readonly />
											</div>
											<!-- column one end -->
											<!-- column two start -->
											<div class="col-md-6 col-lg-6">
												<label for="txtname">Name</label>
												<input type="text" onkeypress="return isTextKey(event)" class="form-control" name="txtname" id="txtname" required placeholder="Supplier Name"/>
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
												<label for="txtemail">E-mail</label>
												<input type="email" class="form-control" onblur="check_email('txtemail','add')" name="txtemail" id="txtemail" required placeholder="your E-mail"/>
											</div>
											<!-- column one end -->
											<!-- column two start -->
											<div class="col-md-6 col-lg-6">
												<label for="txtmobile">Mobile</label>
												<input type="text" onkeypress="return isNumberKey(event)" onblur="check_phonenumber('txtmobile','add')"  class="form-control" name="txtmobile" id="txtmobile" required placeholder="your Mobile"/>
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
												<label for="txtaddress">Address</label>
												<textarea class="form-control" name="txtaddress" id="txtaddress" required placeholder="Address here"></textarea>
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
													<a href="index.php?page=supplier.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
							<h4 class="card-title">Supplier Details</h4>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<a href="index.php?page=supplier.php&option=add"><button class="btn btn-primary">Add Supplier</button></a><br><br>
								<table id="basic-datatables" class="display table table-striped table-hover">
									<thead>
										<tr>
											<th>Supplier ID</th>
											<th>Name</th>
											<th>Mobile</th>
											<th>Address</th>
											<th>E-mail</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$sql_view="SELECT supplier_id,name,mobile,address,email,status FROM supplier";
										$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
										while($row_view=mysqli_fetch_assoc($result_view))
										{
											echo '<tr>';
												echo '<td>'.$row_view["supplier_id"].'</td>';
												echo '<td>'.$row_view["name"].'</td>';
												echo '<td>0'.$row_view["mobile"].'</td>';
												echo '<td>'.$row_view["address"].'</td>';
												echo '<td>'.$row_view["email"].'</td>';
												echo '<td>';
													echo '<a href="index.php?page=supplier.php&option=fullview&pk_supplier_id='.$row_view["supplier_id"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
													if($row_view["status"]=="Active")
													{
														echo '<a href="index.php?page=supplier.php&option=edit&pk_supplier_id='.$row_view["supplier_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
														if($system_usertype=="Admin")
														{
															echo '<a onclick="return delete_confirm()" href="index.php?page=supplier.php&option=delete&pk_supplier_id='.$row_view["supplier_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
														}
													}
													if($system_usertype=="Admin" && $row_view["status"]=="Deleted")
														{
															echo '<a onclick="return reactivate_confirm()" href="index.php?page=supplier.php&option=reactivate&pk_supplier_id='.$row_view["supplier_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-undo"></i> Re-activate</button></a> ';
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
		else if($_GET["option"]=="fullview")
		{
			//fullview table
			$get_pk_supplier_id=$_GET["pk_supplier_id"];
			
			$sql_fullview="SELECT * FROM supplier WHERE supplier_id='$get_pk_supplier_id'";
			$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
			$row_fullview=mysqli_fetch_assoc($result_fullview);
			
			?>
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">Supplier Full Details</h4>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table id="basic-datatables" class="display table table-striped table-hover">
									<tr><th>Supplier ID</th><td><?php echo $row_fullview["supplier_id"]; ?></td></tr>
									<tr><th>Name</th><td><?php echo $row_fullview["name"]; ?></td></tr>
									<tr><th>Mobile</th><td>0<?php echo $row_fullview["mobile"]; ?></td></tr>
									<tr><th>Address</th><td><?php echo $row_fullview["address"]; ?></td></tr>
									<tr><th>E-mail</th><td><?php echo $row_fullview["email"]; ?></td></tr>
									<tr>			
										<td colspan="2">
											<center>
												<a href="index.php?page=supplier.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
												
												<?php
												if($row_fullview["status"]=="Active")
												{
												?>
												<a href="index.php?page=supplier.php&option=edit&pk_supplier_id=<?php echo $row_fullview["supplier_id"]; ?>"><button class="btn btn-info">Edit</button></a> 
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
			<?php	
		}
		else if($_GET["option"]=="edit")
		{
			//edit form
			$get_pk_supplier_id=$_GET["pk_supplier_id"];
			
			$sql_edit="SELECT * FROM supplier WHERE supplier_id='$get_pk_supplier_id'";
			$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit ".mysqli_error($con));
			$row_edit=mysqli_fetch_assoc($result_edit);
			
			?>
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<div class="card-title"> Form for Supplier Edit</div>
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
												<label for="txtsupplierid">Supplier ID</label>
												<input type="text" class="form-control" name="txtsupplierid" id="txtsupplierid" required placeholder="Supplier ID" value="<?php echo $row_edit["supplier_id"]; ?>" readonly />
											</div>
											<!-- column one end -->
											<!-- column two start -->
											<div class="col-md-6 col-lg-6">
												<label for="txtname">Name</label>
												<input type="text" class="form-control" onkeypress="return isTextKey(event)"  name="txtname" id="txtname" required placeholder="Supplier Name" value="<?php echo $row_edit["name"]; ?>"/>
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
												<label for="txtemail">E-mail</label>
												<input type="email" class="form-control" onblur="check_email('txtemail','edit')" name="txtemail" id="txtemail" required placeholder="your E-mail"  value="<?php echo $row_edit["email"]; ?>"/>
											</div>
											<!-- column one end -->
											<!-- column two start -->
											<div class="col-md-6 col-lg-6">
												<label for="txtmobile">Mobile</label>
												<input type="text" class="form-control" onkeypress="return isNumberKey(event)" onblur="check_phonenumber('txtmobile','edit')"  name="txtmobile" id="txtmobile" required placeholder="your Mobile" value="0<?php echo $row_edit["mobile"]; ?>" />
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
												<label for="txtaddress">Address</label>
												<textarea class="form-control" name="txtaddress" id="txtaddress" required placeholder="Address here"><?php echo $row_edit["address"]; ?></textarea>
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
													<a href="index.php?page=supplier.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
													<input type="reset" class="btn btn-danger" name="btnclear" id="btnclear"  value="Clear"/>
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
			$get_pk_supplier_id=$_GET["pk_supplier_id"];
			
			$sql_delete=" UPDATE  supplier SET status='Deleted' WHERE supplier_id='$get_pk_supplier_id'";
			$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
			if($result_delete)
			{
				echo '<script>alert("Successfully Deleted");
					  window.location.href="index.php?page=supplier.php&option=view";</script>';
			}
		}
		else if($_GET["option"]=="reactivate")
		{
			//reactivate code
			$get_pk_supplier_id=$_GET["pk_supplier_id"];
			
			$sql_reactivate=" UPDATE  supplier SET status='Active' WHERE supplier_id='$get_pk_supplier_id'";
			$result_reactivate=mysqli_query($con,$sql_reactivate) or die("sql error in sql_reactivate ".mysqli_error($con));
			if($result_reactivate)
			{
				echo '<script>alert("Successfully Reactivated");
					  window.location.href="index.php?page=supplier.php&option=view";</script>';
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