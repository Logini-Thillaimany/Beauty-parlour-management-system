<?php
if(!isset($_SESSION))
{
	session_start();
}
if(isset($_SESSION["vc_username"]))
{
include("connection.php");
if(isset($_POST["btnverify"]))
{
	$entercode=$_POST["txtcode"];
	$enterusername=$_SESSION["vc_username"];
	
	$sql_verifycode= "SELECT code FROM login WHERE username='$enterusername'";
	$result_verifycode=mysqli_query($con,$sql_verifycode) or die("sql error in sql_verifycode ".mysqli_error($con));
	$row_verifycode=mysqli_fetch_assoc($result_verifycode);
	
	if($row_verifycode["code"]==$entercode)
	{//code match
		unset($_SESSION["vc_username"]);
				
		$_SESSION["forgetchange_username"]=$enterusername;
		
		echo '<script>alert("Please Change your password! "); 
		window.location.href="index.php?page=forgetchangepassword.php";</script>';
	}
	else
	{//code isn't  match
		echo '<script> alert("The code does not mutch! ");
		window.location.href="index.php?page=verificationcode.php";</script>';
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
	echo '<script> window.location.href="index.php?page=forgetpassword.php";</script>';
}
?>
