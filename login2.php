<?php
if(!isset($_SESSION)) {
    session_start();
}
include("connection.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
		
         .gradient-custom-2 {
            background: linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);
            border-top-right-radius: .3rem;
            border-bottom-right-radius: .3rem;
            color: white;
        }
         .gradient-form {
            background: linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);
            height: 100vh !important;
        }
        .form-control {
            border-radius: 10px;
            width: 100%;
            max-width:700px; 
        }
		/* Button Styling */
        .btn-custom {
            border-radius: 20px;
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
            border-radius: 20px;
            background: linear-gradient(to right, #fccb90, #f57d7d, #f78ca0);
            border: none;
            color: white;
            transition: background 0.3s ease;
        }

        .btn-clear:hover {
            background: linear-gradient(to right, #f57d7d, #f78ca0);
        }

        /* Header Text Styling */
         .text-custom {
            color: #dd3675;
        }
    </style>
</head>
<body>


  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-xl-10">
            <div class="card rounded-3 text-black">
                <div class="row g-0">
                    <!-- Left Section -->
                    <div class="col-lg-6">
                        <div class="card-body p-md-5 mx-md-4">
                            <div class="text-center">
                                <img src="assets/img/kaiadmin/logo_light.jpg" style="width: 150px;" alt="logo">
                                <h4 class="mt-1 mb-5 pb-1">Welcome to Lathu Bridals </h4>
                            </div>

                            <form method="POST" action="">
						
						<div class="form-group">
							<div class="row">
								<!-- row one start -->
								<div class="col-md-3 col-lg-3"> </div>
								<div class="col-md- col-lg-9">
									<label for="txtcustomerid">UserName</label>
									<input type="text"  onkeypress="return isTextKey(event)" class="form-control mx-auto" name="txtusername" id="txtusername" required placeholder="Enter Username" />
								</div>
								<!-- row one end -->
								<!-- row two start -->
								<div class="col-md-3 col-lg-3"></div>
								<div class="col-md- col-lg-9">
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
										<input type="reset" class="btn btn-clear" name="btnclear" id="btnclear"  value="Clear"/>
										<input type="submit" class="btn btn-custom" name="btnlogin" id="btnlogin"  value="Login"/>
									</center>
								</div>
							</div>
						</div>
						<!-- button end -->

                                <div class="text-center">
                                    <a href="index.php?page=forgetpassword.php" class="text-muted">Forgot password?</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Right Section -->
                    <div class="col-lg-6 gradient-custom-2 d-flex align-items-center">
                        <div class="px-3 py-4 p-md-5 mx-md-4">
                            <h4>Your beauty, our priority</h4>
                            <p class="small mb-0">
                                At  Lathu Bridals , we offer premium salon services tailored to your needs. Book your appointment today for a glowing makeover that leaves you feeling radiant.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>


</body>
</html>
