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

	if(isset($_POST["btnchangepassword"]))
	{
		$currentpassword=md5($_POST["txtpassword"]);
		$newpassword=md5($_POST["txtnewpassword"]);
		$cnewpassword=md5($_POST["txtcnewpassword"]);
		
		$sql_password="SELECT password FROM login WHERE username='$system_username'";
		$result_password=mysqli_query($con,$sql_password) or die("sql error in sql_password ".mysqli_error($con));
		$row_password=mysqli_fetch_assoc($result_password);
		
		if($row_password["password"]==$currentpassword)
		{// correct current password
			if($newpassword==$cnewpassword)
			{// new passwords are match
				$sql_update="UPDATE login SET password ='$newpassword'  WHERE username='$system_username'";
				$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
				
				session_destroy();
				echo '<script>alert("Your new password has updated! you have to Login with your new password. "); 
					window.location.href="index.php?page=login.php";</script>';
			}
			else
			{//new passwords are miss mathch
				echo '<script> alert("The Passwords does not mutch! ");</script>';
			}
		}
		else
		{// incorrect Current password
			echo '<script> alert("Sorry your Current Password is Wrong! ");</script>';
		}		
	}
	?>

	<body>
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
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div class="card-title">change password</div>
				</div>
				<div class="card-body">
					<div class="row">
						<!-- form start -->
						<form method="POST" action="" onsubmit="return check_password()">
							
							
							<div class="form-group">
								<div class="row">
									<!-- row one start -->
									<div class="col-md-3 col-lg-3"></div>
									<div class="col-md-6 col-lg-6">
										<label for="txtcustomerid">UserName</label>
										<input type="text" value="<?php echo $system_username ?>" readonly class="form-control" name="txtusername" id="txtusername" required placeholder="Enter Username" />
									</div>
									<div class="col-md-3 col-lg-3"></div>
									<!-- row one end -->
									<!-- row two start -->
									<div class="col-md-3 col-lg-3"></div>
									<div class="col-md-6 col-lg-6">
										<label for="txtname">current Password</label>
										<input type="password" class="form-control" name="txtpassword" id="txtpassword" required placeholder="Enter Password"/>
									</div>
									<div class="col-md-3 col-lg-3"></div>
									<!-- row two end -->
									<!-- row three start -->
									<div class="col-md-3 col-lg-3"></div>
									<div class="col-md-6 col-lg-6">
										<label for="txtname">New Password</label>
										<input type="password" class="form-control" name="txtnewpassword" id="txtnewpassword" required placeholder="Enter Password"/>
									</div>
									<div class="col-md-3 col-lg-3"></div>
									<!-- column three end -->
									<!-- column four start -->
									<div class="col-md-3 col-lg-3"></div>
									<div class="col-md-6 col-lg-6">
										<label for="txtname">Confirm New Password</label>
										<input type="password" class="form-control" name="txtcnewpassword" id="txtcnewpassword" required placeholder="Enter Password"/>
									</div>
									<div class="col-md-3 col-lg-3"></div>
									<!-- column four end -->
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
	echo '<script> window.location.href="index.php";</script>';
}
?>