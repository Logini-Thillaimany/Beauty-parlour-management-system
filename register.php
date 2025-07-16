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
if($system_usertype=="Guest")
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
		echo '<script>alert("Successfully Registerd");
                        alert("Your Login details, Your username is Your Email and the passwrd is your Mobile Number.");
						window.location.href="index.php?page=login.php";</script>';
	}
}
//insert code end

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
			document.getElementById("visible_otpbox").innerHTML="";
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
                        if(document.getElementById(mobiletextbox).value!=""){
                            send_otp();
                        }
					}
				}
			};
			xmlhttp.open("GET", "ajaxpage.php?frompage=customer_mobile&ajax_option=" + optionvalue+"&ajax_customer_id="+customer_id+"&ajax_mobile="+mobileno, true);
			xmlhttp.send();
		}

	</script>

<script>
		function send_otp()
		{
            var mobileno=document.getElementById("txtmobile").value;
			
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() 
			{
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
				{
					var responsevalue = xmlhttp.responseText.trim();
					if(responsevalue=="Yes")
					{
						alert("Check your mobile  for OTP!");
						document.getElementById("visible_otpbox").innerHTML='<label for="txtotp">OTP Code</label> <input type="text" onkeypress="return isNumberKey(event)"  onblur="check_otp()"  class="form-control" name="txtotp" id="txtotp" required placeholder="your OTP"/>';
					}
					else
					{
						alert("Please check your Internet connection");
                        document.getElementById("visible_otpbox").innerHTML='';
                        document.getElementById("txtmobile").value="";
					}
				}
			};
			xmlhttp.open("GET", "ajaxpage.php?frompage=register_sendotp&ajax_mobile="+mobileno, true);
			xmlhttp.send();
		}

	</script>
    <script>
		function check_otp()
		{
            var enterotp=document.getElementById("txtotp").value;
			
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() 
			{
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
				{
					var responsevalue = xmlhttp.responseText.trim();
					if(responsevalue=="Yes")
					{
					}
					else
					{
						alert("Sorry! your OTP code is wrong");
                        document.getElementById("txtotp").value="";
					}
				}
			};
			xmlhttp.open("GET", "ajaxpage.php?frompage=register_checkotp&ajax_otp="+enterotp, true);
			xmlhttp.send();
		}

	</script>
<!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
	<style>
		/* Common button style */
		.custom-btn {
			width: 120px;          
			height: 40px;              
			font-weight: bold;
			font-size: 14px;
			color: white;
			border: none;
			border-radius: 10px;        
			cursor: pointer;
			transition: 0.3s ease;
			margin-right: 10px; 
		}
		/* Optional: Hover effect */
		.custom-btn:hover {
			opacity: 0.8;
		}
	</style>
<body>

		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"><center>Form for Customer Registation<center/></div>
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

                                <!-- third_1 row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">	
                                            <div id="visible_otpbox"></div>								
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- third_2 row end -->

                                
								
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
												<a href="index.php"><input type="button" class="custom-btn btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
												<input type="reset" class="custom-btn  btn btn-danger" name="btnclear" id="btnclear"  value="Clear"/>
												<input type="submit" class="custom-btn btn btn-success" name="btnsave" id="btnsave"  value="Register"/>
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
else{// other users redirect to index page
	echo '<script>window.location.href="index.php";</script>';
}
?>