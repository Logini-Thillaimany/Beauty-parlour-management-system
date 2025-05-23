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
if(isset($_SESSION["profile_mobile"]))
{
include("connection.php");
if(isset($_POST["btnverify"]))
{
	$entercode=$_POST["txtcode"];
	$new_mobile=$_SESSION["profile_mobile"];
	
	$sql_verifycode= "SELECT code FROM login WHERE user_id='$system_user_id'";
	$result_verifycode=mysqli_query($con,$sql_verifycode) or die("sql error in sql_verifycode ".mysqli_error($con));
	$row_verifycode=mysqli_fetch_assoc($result_verifycode);
	
	if($row_verifycode["code"]==$entercode)
	{//code match
		if($system_usertype=="Customer")
		{
			$sql_update= "UPDATE customer SET mobile='$new_mobile' WHERE customer_id='$system_user_id'";
		}
		else
		{
			$sql_update= "UPDATE staff SET mobile='$new_mobile' WHERE staff_id='$system_user_id'";
		}
		$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
		unset($_SESSION["profile_mobile"]);
				
		
		echo '<script>alert("Successfully updated! "); 
		window.location.href="index.php?page=profile.php";</script>';
	}
	else
	{//code isn't  match
		echo '<script> alert("The code does not mutch! ");
		window.location.href="index.php?page=verificationcode_profile.php";</script>';
	}
		
}	
?>

<body>

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<div class="card-title">verification code</div>
			</div>
			<div class="card-body">
				<div class="row">
					<!-- form start -->
					<form method="POST" action="">
						
						<!-- one row start -->
						<div class="form-group">
							<div class="row">
								<!-- row one start -->
								<div class="col-md-3 col-lg-3"></div>
								<div class="col-md-6 col-lg-6">
									<label for="txtcustomerid">Code:</label>
									<input type="text" onkeypress="return isNumberKey(event)"  class="form-control" name="txtcode" id="txtcode" required placeholder="Enter OTP" />
								</div>
								<div class="col-md-3 col-lg-3"></div>
								<!-- row one end -->
							</div>
						</div>
						<!-- button start -->
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-lg-12">	
									<center>
										<input type="reset" class="btn btn-danger" name="btnclear" id="btnclear"  value="Clear"/>
										<input type="submit" class="btn btn-success" name="btnverify" id="btnverify"  value="Verify"/>
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


</body>
<?php 
}
else
{
	echo '<script> window.location.href="index.php?page=profile.php";</script>';
}
?>
