<?php
if(!isset($_SESSION))
{
	session_start();
}
include("connection.php");

//Login submit start
if(isset($_POST["btnlogin"]))
{
	$enterusername=$_POST["txtusername"];
	$enterpassword=md5($_POST["txtpassword"]);
	
	$sql_username= "SELECT * FROM login WHERE username='$enterusername'";
	$result_username=mysqli_query($con,$sql_username) or die("sql error in sql_username ".mysqli_error($con));
	
	if(mysqli_num_rows($result_username)==1)
	{// user name is there
		$row_username=mysqli_fetch_assoc($result_username);
		
		$sql_password="SELECT * FROM login WHERE username='$enterusername' AND password='$enterpassword'";
		$result_password=mysqli_query($con,$sql_password) or die("sql error in sql_password ".mysqli_error($con));
		if(mysqli_num_rows($result_password)==1)
		{// correct user name and password
			$sql_update="UPDATE login SET attempt =0  WHERE username='$enterusername'";
			$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
			
			if($row_username["status"]=="Active")
			{// user is active
				$_SESSION["login_username"]=$row_username["username"];
				$_SESSION["login_user_id"]=$row_username["user_id"];
				$_SESSION["login_usertype"]=$row_username["usertype"];
			
				echo '<script> window.location.href="index.php";</script>';
			}
			else
			{ // user has been deleted or deactivated 
				echo '<script>alert("We apologise that your account has been deleted! "); </script>';
			}
		}
		else if($row_username["attempt"]<3)
		{ //correct user name, wrong passsword less than 3 attempt
			$sql_update="UPDATE login SET attempt = attempt+1  WHERE username='$enterusername'";
			$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
			
			echo '<script>alert("Your password is incorrect! "); </script>';
		}
		else
		{ //correct user name, wrong passsword greater than 3 attempt
	
			$_SESSION["forget_username"]=$row_username["username"];
			echo '<script>alert("You make more than three attempts to log in.");
						window.location.href="index.php?page=forgetpassword.php";</script>';
		}
	}
	else
	{// not such user name
		echo '<script>alert("No such user name exists! "); </script>';
	}
}
//Login submit end

?>
<!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
<style>
	body	{
		background-image: url("assets/img/kaiadmin/login_background.jpg");		
		/* Button Styling */
        .btn-custom {
            border-radius: 10px;
			height: 30px;
			Width: 100px;
            background: linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);
            border: none;
            color: white;
        }
	
        .btn-custom:hover {
            background: linear-gradient(to right, #d8363a, #ee7724);
            color: white;
        }
		/* Clear Button Styling */
        .btn-clear {
            border-radius: 10px;
			height: 30px;
			Width: 100px;
            background: linear-gradient(to right, #fccb90, #f57d7d, #f78ca0);
            border: none;
            color: white;
            transition: background 0.3s ease;
        }
        .btn-clear:hover {
            background: linear-gradient(to right, #f57d7d, #f78ca0);
        }
		/* Common size for both buttons */
		.btn-equal {
		width: 150px;
		height: 40px; 
		display: inline-block; 
		text-align: center; 
		}

    </style>
<body>

<div class="row" >
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<div class="card-title"><center>Login<centre/></div>
			</div>
			<div class="card-body">
				<div class="row">
					<!-- form start -->
					<form method="POST" action="">
						
						
						<div class="form-group">
							<div class="row">
								<!-- row one start -->
								<div class="col-md-3 col-lg-3"></div>
								<div class="col-md-6 col-lg-6">
									<label for="txtcustomerid">UserName</label>
									<input type="text"  class="form-control" name="txtusername" id="txtusername" required placeholder="Enter Username" />
								</div>
								<div class="col-md-3 col-lg-3"></div>
								<!-- row one end -->
								<!-- row two start -->
								<div class="col-md-3 col-lg-3"></div>
								<div class="col-md-6 col-lg-6">
									<label for="txtname">Password</label>
									<input type="password" class="form-control mx-auto" name="txtpassword" id="txtpassword" required placeholder="Enter Password"/>
								</div>
								<div class="col-md-3 col-lg-3"></div>
								<!-- row two end -->
							</div>
						</div>
							
						
						
						<!-- button start -->
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-lg-12">	
									<center>
										<input type="reset" class="btn btn-clear btn-equal" name="btnclear" id="btnclear"  value="Clear"/>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="submit" class="btn btn-custom btn-equal" name="btnlogin" id="btnlogin"  value="Login"/>
									</center>
								</div>
							</div>
						</div>
						<!-- button end -->
						
						<div class="text-center">
							<div class="col-md-6 col-lg-12">
								<a href="index.php?page=forgetpassword.php" class="text-muted">Forgot password?</a>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="index.php?page=register.php" class="text-muted">Create a user profile.</a> 
								<!-- add the link for custermer register page-->
							</div>
                        </div>
						
					</form>
					<!-- form end -->
				</div>
			</div>
		</div>
	</div>
</div>


</body>