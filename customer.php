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
	$sql_insert="INSERT INTO customer(customer_id,name,dob,email,mobile,address,comments)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtcustomerid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtname"])."',
									'".mysqli_real_escape_string($con,$_POST["txtdob"])."',
									'".mysqli_real_escape_string($con,$_POST["txtemail"])."',
									'".mysqli_real_escape_string($con,$_POST["txtmobile"])."',
									'".mysqli_real_escape_string($con,$_POST["txtaddress"])."',
									'".mysqli_real_escape_string($con,$_POST["txtcomments"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
	
	//insert into login
	$password=md5($_POST["txtmobile"]);
	$sql_insert_login="INSERT INTO login(user_id,username,password,usertype,attempt,code,status)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtcustomerid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtemail"])."',
									'".mysqli_real_escape_string($con,$password)."',
									'".mysqli_real_escape_string($con,"Customer")."',
									'".mysqli_real_escape_string($con,0)."',
									'".mysqli_real_escape_string($con,0)."',
									'".mysqli_real_escape_string($con,"Active")."')";
	$result_insert_login=mysqli_query($con,$sql_insert_login) or die("sql error in sql_insert_login ".mysqli_error($con));
	if($result_insert)
	{
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=customer.php&option=add";</script>';
	}
}
//insert code end

//update code start
if(isset($_POST["btnsavechanges"]))
{
	$sql_update="UPDATE customer SET
								name='".mysqli_real_escape_string($con,$_POST["txtname"])."',
								dob='".mysqli_real_escape_string($con,$_POST["txtdob"])."',
								email='".mysqli_real_escape_string($con,$_POST["txtemail"])."',
								mobile='".mysqli_real_escape_string($con,$_POST["txtmobile"])."',
								address='".mysqli_real_escape_string($con,$_POST["txtaddress"])."',
								comments='".mysqli_real_escape_string($con,$_POST["txtcomments"])."'
								WHERE customer_id='".mysqli_real_escape_string($con,$_POST["txtcustomerid"])."'";
	$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
	if($result_update)
	{
		echo '<script>alert("Successfully Update");
			  window.location.href="index.php?page=customer.php&option=fullview&pk_customer_id='.$_POST["txtcustomerid"].'";</script>';
	}
}
//update code end
?>
	<script>
		function check_username()
		{
			var username=document.getElementById("txtemail").value;
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() 
			{
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
				{
					var responsevalue = xmlhttp.responseText.trim();
					if(responsevalue=="Yes")
					{
						alert("Sorry, This E-mail is already exist!");
						document.getElementById("txtemail").value="";
					}
					else
					{
						emailvalidation();
					}
				}
			};
			xmlhttp.open("GET", "ajaxpage.php?frompage=staff_customer_username&ajax_username=" + username, true);
			xmlhttp.send();
		}

	</script>
	<script>
		function check_phonenumber(mobiletextbox,optionvalue)
		{
			var customer_id=document.getElementById("txtcustomerid").value;
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
			xmlhttp.open("GET", "ajaxpage.php?frompage=customer_mobile&ajax_option=" + optionvalue+"&ajax_customer_id="+customer_id+"&ajax_mobile="+mobileno, true);
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
						<div class="card-title"><Center>Customer Registation<Center/></div>
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
											<label for="txtcustomerid">Customer ID</label>
											<?php
												$sql_generatedid="SELECT customer_id FROM customer ORDER BY customer_id DESC LIMIT 1";
												$result_generatedid=mysqli_query($con,$sql_generatedid) or die("sql error in sql_generatedid ".mysqli_error($con));
												if(mysqli_num_rows($result_generatedid)==1)
												{// for  except from the first submission
													$row_generatedid=mysqli_fetch_assoc($result_generatedid);
													$generatedid=++$row_generatedid["customer_id"];
												}
												else
												{//For first time submission
													$generatedid="CUS0000001";
												}
											?>
											<input type="text" class="form-control" name="txtcustomerid" id="txtcustomerid" required placeholder="Customer ID" value="<?php echo $generatedid;?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtname">Name</label>
											<input type="text" onkeypress="return isTextKey(event)" class="form-control" name="txtname" id="txtname" required placeholder="Customer Name"/>
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
											<input type="email" onblur="check_username()" class="form-control" name="txtemail" id="txtemail" required placeholder="your E-mail"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtdob">Date Of Birth</label>
											<?php 
												$thisyear=date("Y");
												$before18=$thisyear-18;
												$before18date= $before18."-12-31";
												$before18date=date("Y-m-d",strtotime($before18date));
												
												$before80=$thisyear-80;
												$before80date= $before80."-01-01";
												$before80date=date("Y-m-d",strtotime($before80date));
												?>
											<input type="date" class="form-control" name="txtdob" id="txtdob" min="<?php echo $before80date; ?>" max="<?php echo $before18date; ?>" required placeholder="DOB"/>
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
											<label for="txtmobile">Mobile</label>
											<input type="text" onkeypress="return isNumberKey(event)"  onblur="check_phonenumber('txtmobile','add')"  class="form-control" name="txtmobile" id="txtmobile" required placeholder="your Mobile"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtaddress">Address</label>
											<textarea class="form-control" name="txtaddress" id="txtaddress" required placeholder="Address here"></textarea>
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
											<label for="txtcomments">Comments</label>
											<textarea class="form-control" name="txtcomments" id="txtcomments"  placeholder="if you have any skin disoders please mention that"></textarea>
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
												<a href="index.php?page=customer.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
												<input type="reset" class="btn btn-danger" name="btnclear" id="btnclear"  value="Clear"/>
												<input type="submit" class="btn btn-success" name="btnsave" id="btnsave"  value="Register"/>
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
						<h4 class="card-title">Customer Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
						<?php
						if(isset($_GET["page"])) 
						{
							
							if($system_usertype=="Admin" || $system_usertype=="Clerk")
							{
							?>
								<a href="index.php?page=customer.php&option=add"><button class="btn btn-primary">Add Customer</button></a>&nbsp;&nbsp;&nbsp;
								<a href="print.php?print=customer.php&option=view" target="_blank"><button class="btn btn-primary">Print Customer</button></a><br><br>
							<?php
							}
						}
						?>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>customer ID</th>
										<th>Name</th>
										<th>E-mail</th>
										<th>Mobile</th>
										<th>Address</th>
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
									$sql_view="SELECT customer_id,name,email,mobile,address FROM customer";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{
										$sql_loginstatus="SELECT status FROM login WHERE user_id='$row_view[customer_id]'";
										$result_loginstatus=mysqli_query($con,$sql_loginstatus) or die("sql error in sql_loginstatus ".mysqli_error($con));
										$row_loginstatus=mysqli_fetch_assoc($result_loginstatus);
											
										echo '<tr>';
											echo '<td>'.$row_view["customer_id"].'</td>';
											echo '<td>'.$row_view["name"].'</td>';
											echo '<td>'.$row_view["email"].'</td>';
											echo '<td>0'.$row_view["mobile"].'</td>';
											echo '<td>'.$row_view["address"].'</td>';
											if(isset($_GET["page"]))
											{
											echo '<td>';
												echo '<a href="index.php?page=customer.php&option=fullview&pk_customer_id='.$row_view["customer_id"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
												if($row_loginstatus["status"]=="Active")
													{
														if($system_usertype=="Admin" || $system_usertype=="Clerk")
														{
															echo '<a href="index.php?page=customer.php&option=edit&pk_customer_id='.$row_view["customer_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
														}
														if($system_usertype=="Admin")
														{
															echo '<a onclick="return delete_confirm()" href="index.php?page=customer.php&option=delete&pk_customer_id='.$row_view["customer_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
														}
													}
													if($row_loginstatus["status"]=="Deleted" && $system_usertype=="Admin")
													{
														echo '<a onclick="return reactivate_confirm()" href="index.php?page=customer.php&option=reactivate&pk_customer_id='.$row_view["customer_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-undo"></i> Re-activate</button></a> ';
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
		$get_pk_customer_id=$_GET["pk_customer_id"];
		
		$sql_fullview="SELECT * FROM customer WHERE customer_id='$get_pk_customer_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		$sql_loginstatus="SELECT status FROM login WHERE user_id='$row_fullview[customer_id]'";
		$result_loginstatus=mysqli_query($con,$sql_loginstatus) or die("sql error in sql_loginstatus ".mysqli_error($con));
		$row_loginstatus=mysqli_fetch_assoc($result_loginstatus);
			
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Customer Full Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="display table table-striped table-hover">
								<tr><th>customer ID</th><td><?php echo $row_fullview["customer_id"]; ?></td></tr>
								<tr><th>Name</th><td><?php echo $row_fullview["name"]; ?></td></tr>
								<tr><th>E-mail</th><td><?php echo $row_fullview["email"]; ?></td></tr>
								<tr><th>Date of birth</th><td><?php echo $row_fullview["dob"]; ?></td></tr>
								<tr><th>Address</th><td><?php echo $row_fullview["address"]; ?></td></tr>
								<tr><th>Mobile</th><td>0<?php echo $row_fullview["mobile"]; ?></td></tr>
								<tr><th>Comments</th><td><?php echo $row_fullview["comments"]; ?></td></tr>
								<?php
								if(isset($_GET["page"]))
								{
								?>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=customer.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
											<?php
												if($row_loginstatus["status"]=="Active" && ($system_usertype=="Admin" || $system_usertype=="Clerk"))
											{
											?>
												<a href="index.php?page=customer.php&option=edit&pk_customer_id=<?php echo $row_fullview["customer_id"]; ?>"><button class="btn btn-info">Edit</button></a> 
												<a href="print.php?print=staff.php&option=fullview&pk_customer_id=<?php echo $row_fullview["customer_id"]; ?>"  target="_blank"><button class="btn btn-success">Print</button></a> 
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
		<?php							
	}
	else if($_GET["option"]=="edit")
	{
		//edit form
		$get_pk_customer_id=$_GET["pk_customer_id"];
		
		$sql_edit="SELECT * FROM customer WHERE customer_id='$get_pk_customer_id'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit ".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Customer Edit</div>
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
											<label for="txtcustomerid">Customer ID</label>
											<input type="text" class="form-control" name="txtcustomerid" id="txtcustomerid" required placeholder="Customer ID" value="<?php echo $row_edit["customer_id"]; ?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtname">Name</label>
											<input type="text" onkeypress="return isTextKey(event)" class="form-control" name="txtname" id="txtname" required placeholder="Customer Name" value="<?php echo $row_edit["name"]; ?>"/>
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
											<input type="email" readonly class="form-control" name="txtemail" id="txtemail" required placeholder="your E-mail" value="<?php echo $row_edit["email"]; ?>"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtdob">Date Of Birth</label>
											<input type="date" class="form-control" name="txtdob" id="txtdob" readonly required placeholder="DOB" value="<?php echo $row_edit["dob"]; ?>"/>
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
											<label for="txtmobile">Mobile</label>
											<input type="text" onkeypress="return isNumberKey(event)"  onblur="check_phonenumber('txtmobile','edit')" class="form-control" name="txtmobile" id="txtmobile" required placeholder="your Mobile" value="0<?php echo $row_edit["mobile"]; ?>"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtaddress">Address</label>
											<textarea class="form-control" name="txtaddress" id="txtaddress" required placeholder="Address here"><?php echo $row_edit["address"]; ?></textarea>
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
											<label for="txtcomments">Comments</label>
											<textarea class="form-control" name="txtcomments" id="txtcomments"  placeholder="if you have any skin disoders please mention that"><?php echo $row_edit["comments"]; ?></textarea>
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
												<a href="index.php?page=customer.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
		$get_pk_customer_id=$_GET["pk_customer_id"];
		
		$sql_delete="UPDATE login SET status='Deleted' WHERE user_id='$get_pk_customer_id'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete)
		{
			echo '<script>alert("Successfully Deleted");
				  window.location.href="index.php?page=customer.php&option=view";</script>';
		}
	}
	
	else if($_GET["option"]=="reactivate")
	{
		//reactivate code
		$get_pk_customer_id=$_GET["pk_customer_id"];
		
		$sql_reactivate="UPDATE login SET status='Active' WHERE user_id='$get_pk_customer_id'";
		$result_reactivate=mysqli_query($con,$sql_reactivate) or die("sql error in sql_reactivate ".mysqli_error($con));
		if($result_reactivate)
		{
			echo '<script>alert("Successfully Re-activated ");
				  window.location.href="index.php?page=customer.php&option=view";</script>';
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