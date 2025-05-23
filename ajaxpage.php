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
}
?>
