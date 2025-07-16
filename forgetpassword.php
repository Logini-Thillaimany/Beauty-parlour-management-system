<?php
if(!isset($_SESSION))
{
	session_start();
}
include("connection.php");



if(isset($_POST["btnrecover"]))
{
	$enterusername=$_POST["txtusername"];
	$entermobilenumber=$_POST["txtmobile"];

	$sql_username= "SELECT * FROM login WHERE username='$enterusername'";
	$result_username=mysqli_query($con,$sql_username) or die("sql error in sql_username ".mysqli_error($con));
	if(mysqli_num_rows($result_username)==1)
	{
		$row_username=mysqli_fetch_assoc($result_username);
		
		if($row_username["usertype"]=="Customer")
		{
			$sql_mobile="SELECT mobile FROM customer WHERE email='$enterusername'";
		}
		else
		{
			$sql_mobile="SELECT mobile FROM staff WHERE nic='$enterusername'";
		}
		
		$result_mobile=mysqli_query($con,$sql_mobile) or die("sql error in sql_mobile ".mysqli_error($con));
		$row_mobile=mysqli_fetch_assoc($result_mobile);
		
		if($row_mobile["mobile"]==$entermobilenumber)
		{// matched mobile number
			$verificationcode=rand(1000,9999);
			
			$sql_update="UPDATE login SET code='$verificationcode' WHERE username='$enterusername'";
			$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
			
			//sms OTP code
			$user = "94769669804";
			$password = "3100";
			$text = urlencode("Your verification code is ".$verificationcode); //message with code
			$to = "94".$row_mobile["mobile"];// 

			$baseurl ="https://www.textit.biz/sendmsg";
			$url = "$baseurl/?id=$user&pw=$password&to=$to&text=$text";
			$ret = file($url);

			$res= explode(":",$ret[0]);

			if (trim($res[0])=="OK")
			{//message sent
				if(isset($_SESSION["forget_username"]))
				{
					unset($_SESSION["forget_username"]);
				}
				$_SESSION["vc_username"]=$row_username["username"];
				echo '<script>alert("Please Check your mobile for OTP! "); 
					window.location.href="index.php?page=verificationcode.php";</script>';
			}
			else
			{//message not sent
				echo '<script>alert("Please Check your Internet connection! "); </script>';
			}
		}
		else
		{// unmatched mobile number
			echo '<script>alert("sorry that your mobile number is wrong! "); </script>';
		}
	}
	else
	{// not such user name
		echo '<script>alert("No such user name exists! "); </script>';
	}
}


if(isset($_SESSION["forget_username"]))
{ //After three incorrect password attempts, the user was redirected from the login page.
	$username=$_SESSION["forget_username"];
	$readonlystatus="readonly";
}
else
{// using forget password link
	$username="";
	$readonlystatus="";
}
?>
<!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />

<body>

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<div class="card-title"><center>Forget password<center/></div>
			</div>
			<div class="card-body">
				<div class="row">
					<!-- form start -->
					<form method="POST" action="">
						
					
						<div class="form-group">
							<div class="row">
								<!-- column one start -->
								<div class="col-md-3 col-lg-3"></div>
								<div class="col-md-6 col-lg-6">
									<label for="txtusername">UserName</label>
									<input type="text" class="form-control" name="txtusername" id="txtusername" required placeholder="Enter Username"  value="<?php echo $username; ?>" <?php echo $readonlystatus; ?>/>
								</div>
								<div class="col-md-3 col-lg-3"></div>
								<!-- column one end -->
								<!-- column two start -->
								<div class="col-md-3 col-lg-3"></div>
								<div class="col-md-6 col-lg-6">
									<label for="txtmobile">Mobile</label>
									<input type="text" onkeypress="return isNumberKey(event)" onblur="phonenumber('txtmobile')" class="form-control" name="txtmobile" id="txtmobile" required placeholder="Enter Mobile No" />
								</div>
								<div class="col-md-3 col-lg-3"></div>
								<!-- column two end -->
							</div>
						</div>
							
						
						
						<!-- button start -->
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-lg-12">	
									<center>
										<input type="reset" class="btn btn-danger" name="btnclear" id="btnclear"  value="Clear"/>
										<input type="submit" class="btn btn-success" name="btnrecover" id="btnrecover"  value="Recover"/>
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