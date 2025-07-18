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
date_default_timezone_set("Asia/Colombo");
include("connection.php");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Lathu Bridals - web Application</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="assets/img/kaiadmin/logo_light.jpg"
      type="image/x-icon"
    />

    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project 
    <link rel="stylesheet" href="assets/css/demo.css" />-->
  </head>
  <body>
  <script>
  //this is for Delete confirm
	function delete_confirm()
	{
	  var response_delete=confirm("Are you sure do you want to delete this record?");
	  if(response_delete)
	  {
		return true;  
	  }  
	  else
	  {
		  return false;
	  }
	}
	</script>
	<script>
  //this is for reactivate confirm
	function reactivate_confirm()
	{
	  var response_reactivate=confirm("Are you sure do you want to reactivate this record?");
	  if(response_reactivate)
	  {
		return true;  
	  }  
	  else
	  {
		  return false;
	  }
	}
	</script>

  <script>
  //this is for accept confirm
	function accept_confirm()
	{
	  var response_accept=confirm("Are you sure do you want to accept this record?");
	  if(response_accept)
	  {
		return true;  
	  }  
	  else
	  {
		  return false;
	  }
	}
	</script>
	<script>
  //this is for reject confirm
	function reject_confirm()
	{
	  var response_reject=confirm("Are you sure do you want to reject this record?");
	  if(response_reject)
	  {
		return true;  
	  }  
	  else
	  {
		  return false;
	  }
	}
</script>
  
<script>
	  //this is for text validation
	function isTextKey(evt) // only text to allow the input field
	{
		var charCode = (evt.which) ? evt.which : event.keyCode;
		if (((charCode >64 && charCode < 91)||(charCode >96 && charCode < 123)||charCode ==08 || charCode ==127||charCode ==32||charCode ==46)&&(!(evt.ctrlKey&&(charCode==118||charCode==86))))
			return true;

			return false;
	}
</script>
	<script>
	 //this is for number validation
	function isNumberKey(evt) // only numbers to allow the input field
	{
		var charCode = (evt.which) ? evt.which : event.keyCode;
		if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
			return false;

			return true;
	}
	</script>
	<script>
	//mobile number validation
	function phonenumber(mobile_text_box_name) // Mobile No 
	{
		var phoneno = /^\d{10}$/;
		if(document.getElementById(mobile_text_box_name).value=="")
		{
		}
		else
		{
			if( document.getElementById(mobile_text_box_name).value.match(phoneno))
			{
				hand(mobile_text_box_name);
			}
			else
			{
				alert("Enter 10 digit Mobile Number");
				document.getElementById(mobile_text_box_name).value="";
				//document.getElementById(mobile_text_box_name).focus()=true;		
				return false;
			}
		}	 
	}
	</script>
	<script>
	function hand(mobile_text_box_name)
	{
		var str = document.getElementById(mobile_text_box_name).value;
		var res = str.substring(0, 2);
		if(res=="07")
		{
			return true;
		}
		else
		{
			alert("Enter 10 digit of Mobile Number start with 07xxxxxxxx");
			document.getElementById(mobile_text_box_name).value="";
		//	document.getElementById(mobile_text_box_name).focus()=true;			
			return false;
		}	
	}
	</script>
	<script>
	//check email validation format
	function emailvalidation()
	{
		var email=document.getElementById("txtemail").value;
		var emailformat=/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
		if (email.match(emailformat))
		{
			
		}
		else if(email.length==0)
		{
			
		}
		else
		{
			alert("Email Address is Invalid");
			document.getElementById("txtemail").value="";
			document.getElementById("txtemail").focus()=true;
		}		
	}
	</script>
	<script>
	//nic format validation
function nicnumber()
{
	var nic=document.getElementById("txtnic").value;
	if(nic.length==10)
	{
		var nicformat1=/^[0-9]{9}[a-zA-Z0-9]{1}$/;
		if(nic.match(nicformat1))
		{
			var nicformat2=/^[0-9]{9}[vVxX]{1}$/;
			if(nic.match(nicformat2))
			{
				calculatedob(nic);
			}
			else
			{
				alert("last character must be V/v/X/x");
				document.getElementById("txtnic").value="";
				document.getElementById("txtnic").focus();
				document.getElementById("txtdob").value="";
				document.getElementById("txtgender").value="";
			}
		}
		else
		{
			alert("First 9 characters must be numbers");
			document.getElementById("txtnic").value="";	
			document.getElementById("txtnic").focus();
			document.getElementById("txtdob").value="";
			document.getElementById("txtgender").value="";
		}	
	}
	else if(nic.length==12)
	{	
		var nicformat3=/^[0-9]{12}$/;
		if(nic.match(nicformat3))
		{
			calculatedob(nic);
		}
		else
		{
			alert("All 12 characters must be number");
			document.getElementById("txtnic").value="";
			document.getElementById("txtnic").focus();
			document.getElementById("txtdob").value="";
			document.getElementById("txtgender").value="";
		}
	}
	else if(nic.length==0)
	{
	    document.getElementById("txtdob").value="";
		document.getElementById("txtgender").value="";
	}
	else
	{
		alert("NIC No must be 10 or 12 Characters");
		document.getElementById("txtnic").value="";
		document.getElementById("txtnic").focus();	
		document.getElementById("txtdob").value="";
		document.getElementById("txtgender").value="";
	}
}
</script>
<script>
//send nic to ajaxpage for get dob
function calculatedob(nic)
{
var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() 
{
  if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
  {
    document.getElementById("txtdob").value = xmlhttp.responseText.trim();
    if(nic.length==10)
    {
      var bday_num = nic.substring(2, 5);
    }
    else
    {
      var bday_num = nic.substring(4, 7);
    }
    if(bday_num>500)
    {
      var gender="Female";
    }
    else
    {
      var gender="Male";
    }
    document.getElementById("txtgender").value = gender;
    assign_minDate();
  }
};
xmlhttp.open("GET", "ajaxpage.php?frompage=dob&dobcal=" + nic, true);
xmlhttp.send();
}
</script>
  
    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="index.php" class="logo">
              <img
                src="assets/img/kaiadmin/logo.jpg"
                alt="navbar brand"
                class="navbar-brand"
                height="45"
              />
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <?php
			if($system_usertype=="Guest")
			{
				include("menu_guest.php");
			}
			else if($system_usertype=="Customer")
			{
				include("menu_customer.php");
			}
			else if($system_usertype=="Admin")
			{
				include("menu_admin.php");
			}
			else if($system_usertype=="Clerk")
			{
				include("menu_clerk.php");
			}
			else if($system_usertype=="MakeupArtist")
			{
				include("menu_ma.php");
			}
			else if($system_usertype=="SaloonService")
			{
				include("menu_ss.php");
			}
			
			?>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <a href="index.php" class="logo">
                <img
                  src="assets/img/kaiadmin/logo.jpg"
                  alt="navbar brand"
                  class="navbar-brand"
                  height="30"
                />
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
          <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom" >
            <div class="container-fluid">
              <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex" >
                <!--
                <div class="input-group">
                  <div class="input-group-prepend">
                    <button type="submit" class="btn btn-search pe-1">
                      <i class="fa fa-search search-icon"></i>
                    </button>
                  </div>
                  <input type="text" placeholder="Search ..." class="form-control" />
                </div>-->
              </nav>

              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none" >
                  <a class="nav-link dropdown-toggle"data-bs-toggle="dropdown"  href="#"  role="button"  aria-expanded="false" aria-haspopup="true" >
                    <i class="fa fa-search"></i>
                  </a>
                  <ul class="dropdown-menu dropdown-search animated fadeIn">
                    <form class="navbar-left navbar-form nav-search">
                      <div class="input-group">
                        <input  type="text"placeholder="Search ..." class="form-control"/>
                      </div>
                    </form>
                  </ul>
                </li>

                <li class="nav-item topbar-icon dropdown hidden-caret">
                  <a
                    class="nav-link dropdown-toggle"
                    href="#"
                    id="messageDropdown"
                    role="button"  data-bs-toggle="dropdown" aria-haspopup="true"  aria-expanded="false"  >
                    <i class="fa fa-envelope"></i>
                    <?php
                    $sql_view="SELECT message_id,from_id,date,time,subject,readstatus FROM message WHERE to_id='$system_user_id' AND inboxdelete='1' AND readstatus='1'";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
                    ?>
                    <span class="notification"><?php echo mysqli_num_rows($result_view); ?></span>
                  </a>
                  <ul
                    class="dropdown-menu messages-notif-box animated fadeIn"
                    aria-labelledby="messageDropdown"
                  >
                    <li>
                      <div
                        class="dropdown-title d-flex justify-content-between align-items-center"
                      >
                        Messages
                       
                      </div>
                    </li>
                    <li>
                      <div class="message-notif-scroll scrollbar-outer">
                        <div class="notif-center">
                      <?php
                      while($row_view=mysqli_fetch_assoc($result_view))
									{

                    $sql_fromusertype="SELECT usertype, user_id FROM login WHERE user_id='$row_view[from_id]'";
										$result_fromusertype=mysqli_query($con,$sql_fromusertype) or die("sql error in sql_fromusertype ".mysqli_error($con));
										$row_fromusertype=mysqli_fetch_assoc($result_fromusertype);
										
										if($row_fromusertype["usertype"]=="Customer")
										{//if from user is customer 
											$sql_fromusername="SELECT name FROM customer WHERE customer_id='$row_fromusertype[user_id]'";
										}
										else 
										{/// if from user is other than customer
											$sql_fromusername="SELECT name FROM staff WHERE staff_id='$row_fromusertype[user_id]'";
										}	
										$result_fromusername=mysqli_query($con,$sql_fromusername) or die("sql error in sql_fromusername ".mysqli_error($con));
										$row_fromusername=mysqli_fetch_assoc($result_fromusername);
                      ?>
                        <a href="index.php?page=message.php&option=fullview&pk_message_id_i=<?php echo $row_view["message_id"]; ?>">
                            <div class="notif-img">
                              <img
                                src="assets/img/jm_denis.jpg"
                                alt="Img Profile"
                              />
                            </div>
                            <div class="notif-content">
                              <span class="subject"><?php echo $row_fromusername["name"]; ?></span>
                              <span class="block"><?php echo $row_view["subject"]; ?></span>
                              <span class="time"><?php echo $row_view["date"]; ?></span>
                            </div>
                          </a>
                     <?php
                  }
                     ?>
                          
                        </div>
                      </div>
                    </li>
                    <li>
                      <a class="see-all" href="index.php?page=message.php&option=view"
                        >See all messages<i class="fa fa-angle-right"></i>
                      </a>
                    </li>
                  </ul>
                </li>
                <!-- <li class="nav-item topbar-icon dropdown hidden-caret">
                  <a class="nav-link dropdown-toggle" href="#" id="notifDropdown"  role="button"  data-bs-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-bell"></i>
                    <span class="notification">4</span>
                  </a>
                  <ul
                    class="dropdown-menu notif-box animated fadeIn"
                    aria-labelledby="notifDropdown"
                  >
                    <li>
                      <div class="dropdown-title">
                        You have 4 new notification
                      </div>
                    </li>
                    <li>
                      <div class="notif-scroll scrollbar-outer">
                        <div class="notif-center">
                          <a href="#">
                            <div class="notif-icon notif-primary">
                              <i class="fa fa-user-plus"></i>
                            </div>
                            <div class="notif-content">
                              <span class="block"> New user registered </span>
                              <span class="time">5 minutes ago</span>
                            </div>
                          </a>
                          <a href="#">
                            <div class="notif-icon notif-success">
                              <i class="fa fa-comment"></i>
                            </div>
                            <div class="notif-content">
                              <span class="block">
                                Rahmad commented on Admin
                              </span>
                              <span class="time">12 minutes ago</span>
                            </div>
                          </a>
                          <a href="#">
                            <div class="notif-img">
                              <img
                                src="assets/img/profile2.jpg"
                                alt="Img Profile"
                              />
                            </div>
                            <div class="notif-content">
                              <span class="block">
                                Reza send messages to you
                              </span>
                              <span class="time">12 minutes ago</span>
                            </div>
                          </a>
                          <a href="#">
                            <div class="notif-icon notif-danger">
                              <i class="fa fa-heart"></i>
                            </div>
                            <div class="notif-content">
                              <span class="block"> Farrah liked Admin </span>
                              <span class="time">17 minutes ago</span>
                            </div>
                          </a>
                        </div>
                      </div>
                    </li>
                    <li>
                      <a class="see-all" href="javascript:void(0);"
                        >See all notifications<i class="fa fa-angle-right"></i>
                      </a>
                    </li>
                  </ul>
                </li> -->
                <!--
                <li class="nav-item topbar-icon dropdown hidden-caret">
                  <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-expanded="false" >
                    <i class="fas fa-layer-group"></i>
                  </a>
                  <div class="dropdown-menu quick-actions animated fadeIn">
                    <div class="quick-actions-header">
                      <span class="title mb-1">Quick Actions</span>
                      <span class="subtitle op-7">Shortcuts</span>
                    </div>
                    <div class="quick-actions-scroll scrollbar-outer">
                      <div class="quick-actions-items">
                        <div class="row m-0">
                          <a class="col-6 col-md-4 p-0" href="#">
                            <div class="quick-actions-item">
                              <div class="avatar-item bg-danger rounded-circle">
                                <i class="far fa-calendar-alt"></i>
                              </div>
                              <span class="text">Calendar</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="#">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-warning rounded-circle"
                              >
                                <i class="fas fa-map"></i>
                              </div>
                              <span class="text">Maps</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="#">
                            <div class="quick-actions-item">
                              <div class="avatar-item bg-info rounded-circle">
                                <i class="fas fa-file-excel"></i>
                              </div>
                              <span class="text">Reports</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="#">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-success rounded-circle"
                              >
                                <i class="fas fa-envelope"></i>
                              </div>
                              <span class="text">Emails</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="#">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-primary rounded-circle"
                              >
                                <i class="fas fa-file-invoice-dollar"></i>
                              </div>
                              <span class="text">Invoice</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="#">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-secondary rounded-circle"
                              >
                                <i class="fas fa-credit-card"></i>
                              </div>
                              <span class="text">Payments</span>
                            </div>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>-->
                <?php
                $sql_staff="SELECT name FROM staff WHERE staff_id='$system_user_id'";
                $result_staff=mysqli_query($con,$sql_staff);
                $row_staff=mysqli_fetch_assoc($result_staff);
                  $staff_name=$row_staff["name"];
                ?>
                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a
                    class="dropdown-toggle profile-pic"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <div class="avatar-sm">
                      <img
                        src="assets/img/profile.jpg"
                        alt="..."
                        class="avatar-img rounded-circle"
                      />
                    </div>
                    <span class="profile-username">
                      <span class="op-7">Hi,</span>
                      <span class="fw-bold"><?php echo $staff_name."<br>".$system_usertype; ?></span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="avatar-lg">
                            <img
                              src="assets/img/profile.jpg"
                              alt="image profile"
                              class="avatar-img rounded"
                            />
                          </div>
                          <div class="u-text">
                            <h4><?php echo $staff_name; ?></h4>
                            <p class="text-muted"><?php echo $system_usertype; ?></p>
                            <a
                              href="index.php?page=profile.php"
                              class="btn btn-xs btn-secondary btn-sm"
                              >View Profile</a
                            >
                          </div>
                        </div>
                      </li>
                      <li>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="index.php?page=changepassword.php">Change Password</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php">Logout</a>
                      </li>
                    </div>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>
          <!-- End Navbar -->
        </div>

        <div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
             
            </div>
            <?php
			if(isset($_GET["page"]))
			{
				include($_GET["page"]);
			}
			else
			{
				include("body.php");
			}
			
			?>
          </div>
        </div>

        <footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <nav class="pull-left">
              <ul class="nav">
                <li class="nav-item">
                  <a class="nav-link" href="#">
                    Contact Us
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Help </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Licenses </a>
                </li>
              </ul>
            </nav>
            <div class="copyright">
              2025, made with <i class="fa fa-heart heart text-danger"></i> by
              <a href="#">Lathu Bridals</a>
            </div>
            <div>
              Distributed by
              <a target="_blank" href="#">Lathu Bridals</a>.
            </div>
          </div>
        </footer>
      </div>

      <!-- Custom template | don't include it in your project! -->
      <div class="custom-template">
        <div class="title">Settings</div>
        <div class="custom-content">
          <div class="switcher">
            <div class="switch-block">
              <h4>Logo Header</h4>
              <div class="btnSwitch">
                <button
                  type="button"
                  class="selected changeLogoHeaderColor"
                  data-color="dark"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="blue"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="purple"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="light-blue"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="green"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="orange"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="red"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="white"
                ></button>
                <br />
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="dark2"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="blue2"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="purple2"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="light-blue2"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="green2"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="orange2"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="red2"
                ></button>
              </div>
            </div>
            <div class="switch-block">
              <h4>Navbar Header</h4>
              <div class="btnSwitch">
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="dark"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="blue"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="purple"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="light-blue"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="green"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="orange"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="red"
                ></button>
                <button
                  type="button"
                  class="selected changeTopBarColor"
                  data-color="white"
                ></button>
                <br />
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="dark2"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="blue2"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="purple2"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="light-blue2"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="green2"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="orange2"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="red2"
                ></button>
              </div>
            </div>
            <div class="switch-block">
              <h4>Sidebar</h4>
              <div class="btnSwitch">
                <button
                  type="button"
                  class="changeSideBarColor"
                  data-color="white"
                ></button>
                <button
                  type="button"
                  class="selected changeSideBarColor"
                  data-color="dark"
                ></button>
                <button
                  type="button"
                  class="changeSideBarColor"
                  data-color="dark2"
                ></button>
              </div>
            </div>
          </div>
        </div>
        <div class="custom-toggle">
          <i class="icon-settings"></i>
        </div>
      </div>
      <!-- End Custom template -->
    </div>



<!-- System Modal -->
<div class="modal fade" id="system_popup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><div id="popup_title"></div></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div id="popup_body"></div>
      </div>
      <div class="modal-footer">
      <div id="popup_footer"></div>
      </div>
    </div>
  </div>
</div>
<!-- System Modal -->

    <!--   Core JS Files   -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="assets/js/plugin/jsvectormap/world.js"></script>

    <!-- Sweet Alert -->
    <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="assets/js/kaiadmin.min.js"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! 
    <script src="assets/js/setting-demo.js"></script>
    <script src="assets/js/demo.js"></script>-->
    <script>
      $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#177dff",
        fillColor: "rgba(23, 125, 255, 0.14)",
      });

      $("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#f3545d",
        fillColor: "rgba(243, 84, 93, .14)",
      });

      $("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#ffa534",
        fillColor: "rgba(255, 165, 52, .14)",
      });
    </script>
	
	
	<script>
      $(document).ready(function () {
        $("#basic-datatables").DataTable({});

        $("#basic-datatables1").DataTable({});

        $("#multi-filter-select").DataTable({
          pageLength: 5,
          initComplete: function () {
            this.api()
              .columns()
              .every(function () {
                var column = this;
                var select = $(
                  '<select class="form-select"><option value=""></option></select>'
                )
                  .appendTo($(column.footer()).empty())
                  .on("change", function () {
                    var val = $.fn.dataTable.util.escapeRegex($(this).val());

                    column
                      .search(val ? "^" + val + "$" : "", true, false)
                      .draw();
                  });

                column
                  .data()
                  .unique()
                  .sort()
                  .each(function (d, j) {
                    select.append(
                      '<option value="' + d + '">' + d + "</option>"
                    );
                  });
              });
          },
        });

        // Add Row
        $("#add-row").DataTable({
          pageLength: 5,
        });

        var action =
          '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

        $("#addRowButton").click(function () {
          $("#add-row")
            .dataTable()
            .fnAddData([
              $("#addName").val(),
              $("#addPosition").val(),
              $("#addOffice").val(),
              action,
            ]);
          $("#addRowModal").modal("hide");
        });
      });
    </script>
  </body>
</html>
