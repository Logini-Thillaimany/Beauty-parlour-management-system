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
if($system_usertype!="Guest")
{
	include("connection.php");
	
	//update customer
	if(isset($_POST["btnsavechanges_customer"]))
	{
		$sqlmobile="SELECT mobile FROM customer WHERE customer_id='$system_user_id'";
		$resultmobile=mysqli_query($con,$sqlmobile) or die("sql error in sqlmobile".mysqli_error($con));
		$rowmobile=mysqli_fetch_assoc($resultmobile);
		
		$sql_update="UPDATE customer SET
									name='".mysqli_real_escape_string($con,$_POST["txtname"])."',
									dob='".mysqli_real_escape_string($con,$_POST["txtdob"])."',
									email='".mysqli_real_escape_string($con,$_POST["txtemail"])."',
									address='".mysqli_real_escape_string($con,$_POST["txtaddress"])."',
									comments='".mysqli_real_escape_string($con,$_POST["txtcomments"])."'
									WHERE customer_id='".mysqli_real_escape_string($con,$_POST["txtcustomerid"])."'";
		$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
		if($result_update)
		{
			if($_POST["txtmobile"]==$rowmobile["mobile"])
			{
				echo '<script>alert("Successfully Update");
					  window.location.href="index.php?page=profile.php";</script>';
			}
			else
			{
				$new_mobile=substr($_POST["txtmobile"], 1,9);
				
				$verificationcode=rand(1000,9999);
			
				$sql_update="UPDATE login SET code='$verificationcode' WHERE user_id='$system_user_id'";
				$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
				
				//sms OTP code
				$user = "94769669804";
				$password = "3100";
				$text = urlencode("Your verification code is ".$verificationcode); //message with code
				$to = "94".$new_mobile;// 

				$baseurl ="https://www.textit.biz/sendmsg";
				$url = "$baseurl/?id=$user&pw=$password&to=$to&text=$text";
				$ret = file($url);

				$res= explode(":",$ret[0]);

				if (trim($res[0])=="OK")
				{//message sent
					$_SESSION["profile_mobile"]=$new_mobile;
					echo '<script>alert("Please Check your mobile for OTP! "); 
						window.location.href="index.php?page=verificationcode_profile.php";</script>';
				}
				else
				{//message not sent
					echo '<script>alert("Please Check your Internet connection! "); </script>';
				}
			}
		}
	}
	//update staff
	if(isset($_POST["btnsavechanges_staff"]))
	{
		$sqlmobile="SELECT mobile FROM staff WHERE staff_id='$system_user_id'";
		$resultmobile=mysqli_query($con,$sqlmobile) or die("sql error in sqlmobile".mysqli_error($con));
		$rowmobile=mysqli_fetch_assoc($resultmobile);
		
		$sql_update="UPDATE staff SET
								name='".mysqli_real_escape_string($con,$_POST["txtname"])."',
								nic='".mysqli_real_escape_string($con,$_POST["txtnic"])."',
								dob='".mysqli_real_escape_string($con,$_POST["txtdob"])."',
								gender='".mysqli_real_escape_string($con,$_POST["txtgender"])."',
								address='".mysqli_real_escape_string($con,$_POST["txtaddress"])."',
								designation='".mysqli_real_escape_string($con,$_POST["txtdesignation"])."',
								jointdate='".mysqli_real_escape_string($con,$_POST["txtjoindate"])."'
								WHERE staff_id='".mysqli_real_escape_string($con,$_POST["txtstaffid"])."'";
		$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
		if($result_update)
		{
			if($_POST["txtmobile"]==$rowmobile["mobile"])
			{
				echo '<script>alert("Successfully Update");
					  window.location.href="index.php?page=profile.php";</script>';
			}
			else
			{
				$new_mobile=substr($_POST["txtmobile"], 1,9);
				
				$verificationcode=rand(1000,9999);
			
				$sql_update="UPDATE login SET code='$verificationcode' WHERE user_id='$system_user_id'";
				$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
				
				//sms OTP code
				$user = "94769669804";
				$password = "3100";
				$text = urlencode("Your verification code is ".$verificationcode); //message with code
				$to = "94".$new_mobile;// 

				$baseurl ="https://www.textit.biz/sendmsg";
				$url = "$baseurl/?id=$user&pw=$password&to=$to&text=$text";
				$ret = file($url);

				$res= explode(":",$ret[0]);

				if (trim($res[0])=="OK")
				{//message sent
					$_SESSION["profile_mobile"]=$new_mobile;
					echo '<script>alert("Please Check your mobile for OTP! "); 
						window.location.href="index.php?page=verificationcode_profile.php";</script>';
				}
				else
				{//message not sent
					echo '<script>alert("Please Check your Internet connection! "); </script>';
				}
			}
		}
	}
	
	
	
	if($system_usertype=="Customer")//Customer profile
	{
		$get_pk_customer_id=$system_user_id;
		
		$sql_edit="SELECT * FROM customer WHERE customer_id='$get_pk_customer_id'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit ".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Customer Profile Edit</div>
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
											<input type="text" class="form-control" name="txtemail" id="txtemail" required placeholder="your E-mail" readonly value="<?php echo $row_edit["email"]; ?>"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtdob">Date Of Birth</label>
											<input type="date" class="form-control" name="txtdob" id="txtdob" required placeholder="DOB" value="<?php echo $row_edit["dob"]; ?>"/>
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
											<input type="text" onkeypress="return isNumberKey(event)"  onblur="phonenumber('txtmobile')" class="form-control" name="txtmobile" id="txtmobile" required placeholder="your Mobile" value="0<?php echo $row_edit["mobile"]; ?>"/>
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
											<textarea class="form-control" name="txtcomments" id="txtcomments" required placeholder="Your openions"><?php echo $row_edit["comments"]; ?></textarea>
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
												<a href="index.php?page=profile.php"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
												<input type="reset" class="btn btn-danger" name="btncancel" id="btncancel"  value="Cancel"/>
												<input type="submit" class="btn btn-success" name="btnsavechanges_customer" id="btnsavechanges_customer"  value="Save changes"/>
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
	else//staff profile
	{
		$get_pk_staff_id=$system_user_id;
		
		$sql_edit="SELECT * FROM staff WHERE staff_id='$get_pk_staff_id'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit ".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title">Form for Staff Profile Edit</div>
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
											<input type="text"  class="form-control" name="txtnic" id="txtnic" required placeholder="Type your NIC" value="<?php echo $row_edit["nic"]; ?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="email2">DOB</label>
											<input type="date" class="form-control" name="txtdob" id="txtdob" required placeholder="Type your DOB" value="<?php echo $row_edit["dob"]; ?>" readonly />
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
											<input type="text" class="form-control" name="txtgender" id="txtgender" required placeholder="Type your Gender" value="<?php echo $row_edit["gender"]; ?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="email2">Mobile</label>
											<input type="text"  onkeypress="return isNumberKey(event)" onblur="phonenumber('txtmobile')"  class="form-control" name="txtmobile" id="txtmobile" required placeholder="Type your Mobile" value="0<?php echo $row_edit["mobile"]; ?>" />
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
											<input type="text" class="form-control" name="txtdesignation" id="txtdesignation" required placeholder="Type your Designation" value="<?php echo $row_edit["designation"]; ?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="email2">Join Date</label>
											<input type="date" class="form-control" name="txtjoindate" id="txtjoindate" required placeholder="Type your Join Date" value="<?php echo $row_edit["jointdate"]; ?>" readonly />
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
												<a href="index.php?page=profile.php"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
												<input type="reset" class="btn btn-danger" name="btncancel" id="btncancel"  value="Cancel"/>
												<input type="submit" class="btn btn-success" name="btnsavechanges_staff" id="btnsavechanges_staff"  value="Save Changes"/>
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
}
else
{
	echo '<script> window.location.href="index.php";</script>';
}
?>