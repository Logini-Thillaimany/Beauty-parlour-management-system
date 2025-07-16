<?php
if(!isset($_SESSION))
{
	session_start();
}

if(isset($_SESSION["forgetchange_username"]))
{
	include("connection.php");

	$enterusername=$_SESSION["forgetchange_username"];
	
	if(isset($_POST["btnchangepassword"]))
	{	
		$newpassword=md5($_POST["txtnewpassword"]);
		$cnewpassword=md5($_POST["txtcnewpassword"]);
		
		if($newpassword==$cnewpassword)
		{
			$sql_update="UPDATE login SET password ='$newpassword'  WHERE username='$enterusername'";
			$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
			
			unset($_SESSION["forgetchange_username"]);
			echo '<script>alert("Your new password has updated! "); 
					window.location.href="index.php?page=login.php";</script>';
		}
		else
		{
			echo '<script> alert("The Passwords does not mutch! ");</script>';
		}	
	}
?>
<!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
<script>
	function check_password()
	{
	  var newpassword= document.getElementById("txtnewpassword").value;
	  var cnewpassword= document.getElementById("txtcnewpassword").value;
	  if(newpassword==cnewpassword)
	  {
		  return true;
	  }
	  else
	  {
		 alert("Your passwords are miss match!");
		 document.getElementById("txtnewpassword").value="";
		 document.getElementById("txtcnewpassword").value="";
		 return false;
	  }
	}
</script>
<body>

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<div class="card-title">Forget change password.</div>
			</div>
			<div class="card-body">
				<div class="row">
					<!-- form start -->
					<form method="POST" action="" onsubmit="return check_password()">
						
					
						<div class="form-group">
							<div class="row">
								<!-- Row one start -->
								<div class="col-md-3 col-lg-3"></div>
								<div class="col-md-6 col-lg-6">
									<label for="txtcustomerid">UserName</label>
									<input type="text"  class="form-control" value="<?php echo $enterusername ?>" name="txtusername" id="txtusername" readonly required placeholder="Enter Username" />
								</div>
								<div class="col-md-3 col-lg-3"></div>
								<!-- Row one end -->
								<!-- Row two start -->
								<div class="col-md-3 col-lg-3"></div>
								<div class="col-md-6 col-lg-6">
									<label for="txtname">New Password</label>
									<input type="password" class="form-control" name="txtnewpassword" id="txtnewpassword" required placeholder="Enter New Password"/>
								</div>
								<div class="col-md-3 col-lg-3"></div>
								<!-- Row two end -->
								
								<!-- Row three start -->
								<div class="col-md-3 col-lg-3"></div>
								<div class="col-md-6 col-lg-6">
									<label for="txtname">Confirm New Password</label>
									<input type="password" class="form-control" name="txtcnewpassword" id="txtcnewpassword" required placeholder="Re-Enter New Password"/>
								</div>
								<div class="col-md-3 col-lg-3"></div>
								<!-- Row three end -->
							</div>
						</div>
						
						
						
						<!-- button start -->
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-lg-12">	
									<center>
										<input type="reset" class="btn btn-danger" name="btnclear" id="btnclear"  value="Clear"/>
										<input type="submit" class="btn btn-success" name="btnchangepassword" id="btnchangepassword"  value="Change Password"/>
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