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
include("connection.php");

if(isset($_GET["frompage"]))
{
	if($_GET["frompage"]=="dob")
	{
		$selnic = $_GET["dobcal"];
		if(strlen($selnic)==10)
		{
			$bdayyear=substr($selnic, 0,2);
			$bdayyear=$bdayyear+1900;
			$bdaynum=substr($selnic, 2,3);
		}
		else if(strlen($selnic)==12)
		{
			$bdayyear=substr($selnic, 0,4);
			$bdaynum=substr($selnic, 4,3);
		}
		
		$bdaynum1=0;
		if($bdaynum>500)
		{
			$bdaynum1=$bdaynum-500;
			
		}
		else
		{
			$bdaynum1=$bdaynum;
		}
		
		$bdaydate;
		
		$month=array(31,29,31,30,31,30,31,31,30,31,30,31);
		$day_cal=0;//add total days of months
		$bdaymonth=0;
		$bdayday=0;
		for($x=0;$x<count($month);$x++)
		{
			$day_cal=$day_cal+$month[$x];
			if($day_cal>=$bdaynum1)
			{
				$bdayday=$bdaynum1-(($day_cal)-($month[$x]));
				$bdaymonth=++$x;
				break;
			}
		}
		$bdaydate=$bdayyear."-".$bdaymonth."-".$bdayday;
		$bdaydate=date("Y-m-d", strtotime($bdaydate));
		echo $bdaydate;
	}
	if($_GET["frompage"]=="staff_customer_username")
	{//Get nic or email from staff or customer for check username exist or not
		$get_ajax_username = $_GET["ajax_username"];
		
		$sql_check="SELECT username FROM login WHERE username='$get_ajax_username'";
		$result_check=mysqli_query($con,$sql_check) or die("sql error in sql_check ".mysqli_error($con));
		if(mysqli_num_rows($result_check)>0)
		{
			echo 'Yes';
		}
		else{
			echo 'No';
		}
	}
	if($_GET["frompage"]=="staff_jointdate")
	{//Get dob from staff for Joint date minmimum 
		$get_ajax_dob = $_GET["ajax_dob"];
		$birthYear = date("Y",strtotime($get_ajax_dob));
		$after18years= $birthYear+ 18;
		$minDate=$after18years."-01-01";
		$minDate=date("Y-m-d",strtotime($minDate));
		echo $minDate;
		
	}
	if($_GET["frompage"]=="staff_mobile")
	{//Get mobile from staff for check mobile no exist or not
		$get_ajax_option = $_GET["ajax_option"];
		$get_ajax_staff_id = $_GET["ajax_staff_id"];
		$get_ajax_mobile = $_GET["ajax_mobile"];
		
		if($get_ajax_option=="add")
		{
			$sql_check="SELECT mobile FROM staff WHERE mobile='$get_ajax_mobile'";
		}
		else
		{
			$sql_check="SELECT mobile FROM staff WHERE mobile='$get_ajax_mobile' AND staff_id!='$get_ajax_staff_id'";
		}
		$result_check=mysqli_query($con,$sql_check) or die("sql error in sql_check ".mysqli_error($con));
		if(mysqli_num_rows($result_check)>0)
		{
			echo 'Yes';
		}
		else{
			echo 'No';
		}
	}
	if($_GET["frompage"]=="customer_mobile")
	{//Get mobile from customer for check mobile no exist or not
		$get_ajax_option = $_GET["ajax_option"];
		$get_ajax_customer_id = $_GET["ajax_customer_id"];
		$get_ajax_mobile = $_GET["ajax_mobile"];
		
		if($get_ajax_option=="add")
		{
			$sql_check="SELECT mobile FROM customer WHERE mobile='$get_ajax_mobile'";
		}
		else
		{
			$sql_check="SELECT mobile FROM customer WHERE mobile='$get_ajax_mobile' AND customer_id!='$get_ajax_customer_id'";
		}
		$result_check=mysqli_query($con,$sql_check) or die("sql error in sql_check ".mysqli_error($con));
		if(mysqli_num_rows($result_check)>0)
		{
			echo 'Yes';
		}
		else{
			echo 'No';
		}
	}
	if($_GET["frompage"]=="supplier_mobile")
	{//Get mobile from supplier for check mobile no exist or not
		$get_ajax_option = $_GET["ajax_option"];
		$get_ajax_supplier_id = $_GET["ajax_supplier_id"];
		$get_ajax_mobile = $_GET["ajax_mobile"];
		
		if($get_ajax_option=="add")
		{
			$sql_check="SELECT mobile FROM supplier WHERE mobile='$get_ajax_mobile'";
		}
		else
		{
			$sql_check="SELECT mobile FROM supplier WHERE mobile='$get_ajax_mobile' AND supplier_id!='$get_ajax_supplier_id'";
		}
		$result_check=mysqli_query($con,$sql_check) or die("sql error in sql_check ".mysqli_error($con));
		if(mysqli_num_rows($result_check)>0)
		{
			echo 'Yes';
		}
		else{
			echo 'No';
		}
	}
	if($_GET["frompage"]=="supplier_email")
	{//Get email from supplier for check email no exist or not
		$get_ajax_option = $_GET["ajax_option"];
		$get_ajax_supplier_id = $_GET["ajax_supplier_id"];
		$get_ajax_email = $_GET["ajax_email"];
		
		if($get_ajax_option=="add")
		{
			$sql_check="SELECT email FROM supplier WHERE email='$get_ajax_email'";
		}
		else
		{
			$sql_check="SELECT email FROM supplier WHERE email='$get_ajax_email' AND supplier_id!='$get_ajax_supplier_id'";
		}
		$result_check=mysqli_query($con,$sql_check) or die("sql error in sql_check ".mysqli_error($con));
		if(mysqli_num_rows($result_check)>0)
		{
			echo 'Yes';
		}
		else{
			echo 'No';
		}
	}
	if($_GET["frompage"]=="category_name")
	{//Get mobile from packagecategory for check name no exist or not
		$get_ajax_option = $_GET["ajax_option"];
		$get_ajax_category_id = $_GET["ajax_category_id"];
		$get_ajax_name = $_GET["ajax_name"];
		
		if($get_ajax_option=="add")
		{
			$sql_check="SELECT name FROM packagecategory WHERE name='$get_ajax_name'";
		}
		else
		{
			$sql_check="SELECT name FROM packagecategory WHERE name='$get_ajax_name' AND category_id!='$get_ajax_category_id'";
		}
		$result_check=mysqli_query($con,$sql_check) or die("sql error in sql_check ".mysqli_error($con));
		if(mysqli_num_rows($result_check)>0)
		{
			echo 'Yes';
		}
		else{
			echo 'No';
		}
	}
	if($_GET["frompage"]=="subcategory_name")
	{//Get mobile from packagesubcategory for check name no exist or not
		$get_ajax_option = $_GET["ajax_option"];
		$get_ajax_subcategory_id = $_GET["ajax_subcategory_id"];
		$get_ajax_name = $_GET["ajax_name"];
		
		if($get_ajax_option=="add")
		{
			$sql_check="SELECT name FROM packagesubcategory WHERE name='$get_ajax_name'";
		}
		else
		{
			$sql_check="SELECT name FROM packagesubcategory WHERE name='$get_ajax_name' AND subcategory_id!='$get_ajax_subcategory_id'";
		}
		$result_check=mysqli_query($con,$sql_check) or die("sql error in sql_check ".mysqli_error($con));
		if(mysqli_num_rows($result_check)>0)
		{
			echo 'Yes';
		}
		else{
			echo 'No';
		}
	}
	if($_GET["frompage"]=="register_sendotp")
	{//Get mobile from Register for send OTP
		$get_ajax_mobile = $_GET["ajax_mobile"];
		
		$new_mobile=substr($get_ajax_mobile, 1,9);
				
		$verificationcode=rand(1000,9999);
		
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
			$_SESSION["Register_otp"]=$verificationcode;
			echo 'Yes';
		}
		else
		{//message not sent
			echo 'No';
		}
	}

	if($_GET["frompage"]=="register_checkotp")
	{//Get OTP from Register for check OTP
		$get_ajax_otp = $_GET["ajax_otp"];
		
		if ($_SESSION["Register_otp"]==$get_ajax_otp)
		{//correct otp
			echo 'Yes';
		}
		else
		{//wrong otp
			echo 'No';
		}
	}

	if($_GET["frompage"]=="package_subcategory")
	{//Get categoryid from package for load subcategory
		$get_ajax_category_id = $_GET["ajax_category_id"];
		
		echo'<option value="" disabled selected>Select Sub category </option>';
		$sql_load="SELECT subcategory_id, name FROM packagesubcategory WHERE category_id='$get_ajax_category_id'";
		$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
		while($row_load=mysqli_fetch_assoc($result_load))
		{
			echo'<option value="'.$row_load["subcategory_id"].'">'.$row_load["name"].'</option>';
		}
	}
	if($_GET["frompage"]=="staff_package")
	{//Get designation from staff for load package
		$get_ajax_designation = $_GET["ajax_designation"];

		?>
		<hr>Package Details<br>
		<div class="row">
			<div class="col-md-12 col-lg-12">
				<table  class="table">
			<?php
				$sql_view="SELECT package_id,name FROM package  WHERE package_id IN (SELECT package_id FROM packageprice WHERE enddate IS NULL)";
				$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
				$totalpackage=mysqli_num_rows($result_view);
				$x=0;
				$y=1;
				echo '<tr>';
				while($row_view=mysqli_fetch_assoc($result_view))
				{	
					echo '<td>';
						echo '<input type="checkbox" name="txtpackageid_'.$y.'" id="txtpackageid_'.$y.'" value="'.$row_view["package_id"].'"> '.$row_view["name"];
					echo '</td>';

					$x++;
					$y++;
					if($x==$totalpackage){
						if($x%4==0){
							echo'</tr>';
						}
						else if($x%4==1){
							echo'<td></td><td></td><td></td></tr>';
						}
						else if($x%4==2){
							echo'<td></td><td></td></tr>';
						}
						else if($x%4==3){
							echo'<td></td></tr>';
						}
					}
					else
					{
						if($x%4==0){
							echo'</tr><tr>';
						}
					}
				}
				echo '<input type="hidden" name="txtloop" id="txtloop" value="'.$y.'">';
				?>
				</table>
			</div>
		</div>
		<?php
	}
	if($_GET["frompage"]=="purchaseproduct_expire")
	{//Get productid  from purchase product for get expire type
		$get_ajax_productid = $_GET["ajax_productid"];

		$sql_enterby_product="SELECT expiretype from product WHERE product_id='$get_ajax_productid'";
		$result_enterby_product=mysqli_query($con,$sql_enterby_product) or die("sql error in sql_enterby_product ".mysqli_error($con));
		$row_enterby_product=mysqli_fetch_assoc($result_enterby_product);

		echo $row_enterby_product["expiretype"];
	}
	if($_GET["frompage"]=="supplytoserviceitem_max")
	{//Get productid  from supply to service item product for get stock quantity
		$get_ajax_productid = $_GET["ajax_productid"];

		$sql_enterby_product="SELECT expiretype from product WHERE product_id='$get_ajax_productid'";
		$result_enterby_product=mysqli_query($con,$sql_enterby_product) or die("sql error in sql_enterby_product ".mysqli_error($con));
		$row_enterby_product=mysqli_fetch_assoc($result_enterby_product);

		if($row_enterby_product["expiretype"]=="Yes"){
			$today = date("Y-m-d");
			$sql_max="SELECT sum(quantity) as totalquantity FROM stock WHERE product_id='$get_ajax_productid' AND purchase_id IN (SELECT purchase_id FROM purchaseproduct WHERE expiredate>='$today' AND product_id='$get_ajax_productid')";
		}
		else{
			$sql_max="SELECT quantity as totalquantity FROM stockne WHERE product_id='$get_ajax_productid'";			
		}

		$result_max=mysqli_query($con,$sql_max) or die("sql error in sql_max ".mysqli_error($con));
		if(mysqli_num_rows($result_max)==0)
		{
			echo 'NoPurchase';
		}
		else
		{
			$row_max=mysqli_fetch_assoc($result_max);
			if($row_max["totalquantity"]==0)
			{
				echo 'NoStock';
			}
			else
			{
				echo $row_max["totalquantity"];
			}
		}
	}
	if($_GET["frompage"]=="specialtime_date")
	{//Get productid  from supply to service item product for get stock quantity
		$get_ajax_date = $_GET["ajax_date"];

		$sql_check="SELECT date FROM specialtime WHERE date='$get_ajax_date'";
		$result_check=mysqli_query($con,$sql_check) or die("sql error in sql_check ".mysqli_error($con));
		if(mysqli_num_rows($result_check)>0)
		{
			echo 'NotAvailable';
		}
		else{
			echo 'Available';
		}

	}
	if($_GET["frompage"]=="staffleave_startdate")
	{//Get satrt date  from staff leave for calculate max endate
		$get_ajax_start_date = $_GET["ajax_start_date"];

		$maxdate=date("Y-m-d", strtotime($get_ajax_start_date."+15 days"));
		echo $maxdate;
	}
	if($_GET["frompage"]=="staffleave_check")
	{//Get satrt date,start time, end date, end time, staff id  from staff leave for Check eligibility for the leave
		$get_ajax_start_date = $_GET["ajax_start_date"];
		$get_ajax_start_time = $_GET["ajax_start_time"];
		$get_ajax_end_date = $_GET["ajax_end_date"];
		$get_ajax_end_time = $_GET["ajax_end_time"];
		$get_ajax_staff_id = $_GET["ajax_staff_id"];

		$today= date("Y-m-d");
		$sql_check="SELECT * FROM staffleave WHERE staff_id='$get_ajax_staff_id' AND enddate>='$today' AND (status='Pending' OR status='Approved')";
		$result_check=mysqli_query($con,$sql_check) or die("sql error in sql_check ".mysqli_error($con));
		if(mysqli_num_rows($result_check)==0){
			echo 'Yes';
		}
		else
		{
			$allowed=0;
			while($row_check=mysqli_fetch_assoc($result_check))
			{
				if($get_ajax_start_date<$row_check["startdate"] && $get_ajax_end_date<$row_check["startdate"])
				{//new leave start before old leave start
					
				}
				else if($get_ajax_start_date>$row_check["enddate"] && $get_ajax_end_date>$row_check["enddate"])
				{//new leave start after old leave end
					
				}
				else 
				{//new leave same as old leave
					$allowed++;
				}
			}
			if($allowed==0)
			{//no same leave
				echo 'Yes';
			}
			else
			{//same leave
				echo 'No';
			}
		}
	}
	if($_GET["frompage"]=="bookingpackage_category")
	{//Get Sub category from of the selected category for booking package
		$get_ajax_category_id = $_GET["ajax_category_id"];

		echo'<option value="" disabled selected>Select Sub category </option>';
		$sql_load_subcategory="SELECT subcategory_id, name FROM packagesubcategory WHERE category_id='$get_ajax_category_id' AND status='Active'";
		$result_load_subcategory=mysqli_query($con,$sql_load_subcategory) or die("sql error in sql_load_subcategory".mysqli_error($con));
		while($row_load_subcategory=mysqli_fetch_assoc($result_load_subcategory))
		{
			echo'<option value="'.$row_load_subcategory["subcategory_id"].'">'.$row_load_subcategory["name"].'</option>';
		}
	}
	if($_GET["frompage"]=="bookingpackage_subcategory")
	{//Get Package from of the selected sub category for booking package
		$get_ajax_subcategory_id = $_GET["ajax_subcategory_id"];
		
		echo'<option value="" disabled selected>Select package </option>';
		$sql_load_package="SELECT package_id,name FROM package WHERE subcategory_id='$get_ajax_subcategory_id' AND package_id IN (SELECT package_id FROM packageprice WHERE enddate IS NULL)";
		$result_load_package=mysqli_query($con,$sql_load_package) or die("sql error in sql_load_package".mysqli_error($con));
		while($row_load_package=mysqli_fetch_assoc($result_load_package))
		{
			echo'<option value="'.$row_load_package["package_id"].'">'.$row_load_package["name"].'</option>';
		}
	}
	if($_GET["frompage"]=="booking_checkavailableity")
	{//Check availableity of the selected package for booking
		$get_ajax_booking_id = $_GET["ajax_booking_id"];
		$get_ajax_servicedate = $_GET["ajax_servicedate"];
		$get_ajax_servicetime = $_GET["ajax_servicetime"];

		if($_SESSION["session_booking_type"]=="CA01")
		{
			$designation_staff="MakeupArtist";
		}
		else if($_SESSION["session_booking_type"]=="CA02")
		{
			$designation_staff="SaloonService";
		}

		$sql_acive_staff="SELECT staff_id FROM staff WHERE designation='$designation_staff' AND staff_id  IN (SELECT user_id FROM login WHERE status='Active')";
		$result_acive_staff=mysqli_query($con,$sql_acive_staff) or die("sql error in sql_acive_staff".mysqli_error($con));
		$no_of_active_staff=mysqli_num_rows($result_acive_staff);

		$sql_leave_staff="SELECT staff_id FROM staffleave WHERE status='Approved' AND startdate<='$get_ajax_servicedate' AND enddate>='$get_ajax_servicedate' AND staff_id IN (SELECT staff_id FROM staff WHERE designation='$designation_staff' AND staff_id  IN (SELECT user_id FROM login WHERE status='Active'))";
		$result_leave_staff=mysqli_query($con,$sql_leave_staff) or die("sql error in sql_leave_staff ".mysqli_error($con));
		$no_of_leave_staff=mysqli_num_rows($result_leave_staff);
		
		//calcualte no of staff available
		$N0_staff_available=$no_of_active_staff-$no_of_leave_staff;

		// calculate total duration of the selected package
		$duration=0;
		$available=0;

		$sql_load_package="SELECT * FROM bookingpackage WHERE booking_id='$get_ajax_booking_id' ";
		$result_load_package=mysqli_query($con,$sql_load_package) or die("sql error in sql_load_package".mysqli_error($con));
		while($row_load_package=mysqli_fetch_assoc($result_load_package))
		{ 
			$sql_check="SELECT duration FROM package WHERE package_id='$row_load_package[package_id]'";
			$result_check=mysqli_query($con,$sql_check) or die("sql error in sql_check ".mysqli_error($con));
			$row_check=mysqli_fetch_assoc($result_check);
			$duration=$duration+$row_check["duration"];
		}
		$endtime=date("H:i:s", strtotime($get_ajax_servicetime) + ($duration * 60));

		$sql_check_bookings="SELECT booking_id FROM booking WHERE servicedate='$get_ajax_servicedate' AND booking_id != '$get_ajax_booking_id' AND (status= 'Accept' OR status='Pending')";
		$result_check_bookings=mysqli_query($con,$sql_check_bookings) or die("sql error in sql_check_bookings ".mysqli_error($con));
		while($row_check_bookings=mysqli_fetch_assoc($result_check_bookings))
		{

			$sql_check_package="SELECT * FROM bookingpackage WHERE booking_id='$row_check_bookings[booking_id]' AND (starttime>='$get_ajax_servicetime' AND starttime<='$endtime')";
			$result_check_package=mysqli_query($con,$sql_check_package) or die("sql error in sql_check_package ".mysqli_error($con));
			if(mysqli_num_rows($result_check_package)>0)
			{
				$available++;
			}
		}

		//check if all packages are available
		$remaining= $N0_staff_available-$available;
		
		if((mysqli_num_rows($result_check_bookings)==0 || $remaining>0) && $endtime<="20:00:00")
		{//all packages are available
			echo 'Yes';
		}
		else
		{//some packages are not available
			echo 'No';
		}
	}
	if($_GET["frompage"]=="bookingsales_product")
	{//Check availableity of the selected product for sale
		$get_ajax_productid = $_GET["ajax_productid"];
		$today = date("Y-m-d");

		$sql_max="SELECT sum(quantity) as totalquantity FROM stock WHERE product_id='$get_ajax_productid' AND purchase_id IN (SELECT purchase_id FROM purchaseproduct WHERE expiredate>='$today' AND product_id='$get_ajax_productid')";
		$result_max=mysqli_query($con,$sql_max) or die("sql error in sql_max ".mysqli_error($con));
		$row_max=mysqli_fetch_assoc($result_max);

		$sql_checkprice="SELECT price,offer from productprice WHERE product_id='$get_ajax_productid' AND enddate IS NULL";
		$result_checkprice=mysqli_query($con,$sql_checkprice) or die("sql error in sql_checkprice ".mysqli_error($con));
		$row_checkprice=mysqli_fetch_assoc($result_checkprice);
		$unit_price=$row_checkprice["price"]*(1-$row_checkprice["offer"]/100);

		echo $row_max["totalquantity"]."****".$unit_price;
	}
	
}
?>
