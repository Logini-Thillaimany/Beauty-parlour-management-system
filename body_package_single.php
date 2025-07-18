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
$get_package_id=$_GET["package_id"];

$sql_package="SELECT * FROM package WHERE package_id='$get_package_id'";
$result_package=mysqli_query($con,$sql_package) or die("sql error in sql_package ".mysqli_error($con));
$row_package=mysqli_fetch_assoc($result_package);

$sql_package_photo="SELECT * FROM packagephoto WHERE package_id='$row_package[package_id]'";
$result_package_photo=mysqli_query($con,$sql_package_photo) or die("sql error in sql_package_photo ".mysqli_error($con));
$row_package_photo=mysqli_fetch_assoc($result_package_photo);

//get unit price
$sql_price="SELECT price,offer FROM packageprice WHERE package_id='$row_package[package_id]' AND enddate IS NULL";
$result_price=mysqli_query($con,$sql_price) or die("sql error in sql_price ".mysqli_error($con));
$row_price=mysqli_fetch_assoc($result_price);

$unit_price=$row_price["price"]*(1-$row_price["offer"]/100);
?>

<!-- single product -->
	<div class="single-product mt-150 mb-150">
		<div class="container">
			<div class="row">
				<div class="col-md-5">
					<div class="single-product-img">
					    <img src="file/package/<?php echo $row_package_photo["photo"].'?'.date("h:i:s"); ?>" onClick="popup_product('<?php echo $row_package_photo["photo"].'?'.date("h:i:s"); ?>')" data-bs-toggle="modal" data-bs-target="#system_popup" width="100%" hight="100%">
					</div>
				</div>
				<div class="col-md-7">
					<div class="single-product-content">
						<h3>
                            <?php echo $row_package["name"]; ?>
                        </h3>
						<?php
                            if($row_price["offer"]>0)
                            {
                                $display_price='<p class="single-product-pricing"><span><del>'.$row_price["price"].'</del></span> <span>LKR '.$unit_price.'</span></p>';
                            }
                            else
                            {
                                $display_price='<p class="single-product-pricing"><span>LKR '.$unit_price.'</span></p>';
                            }
                        ?>
                        <p class="single-product-pricing"><?php echo $display_price; ?></p>
                        <?php
                        $sql_review="SELECT * FROM review WHERE package_id='$row_package[package_id]'";
                        $result_review=mysqli_query($con,$sql_review) or die("sql error in sql_review ".mysqli_error($con));
                        while($row_review=mysqli_fetch_assoc($result_review))
                        {
                            $sql_customer="SELECT name from customer WHERE customer_id IN (SELECT customer_id FROM booking WHERE booking_id='$row_review[booking_id]' )";
                            $result_customer=mysqli_query($con,$sql_customer) or die("sql error in sql_customer ".mysqli_error($con));
                            $row_customer=mysqli_fetch_assoc($result_customer);
                            ?>
                            
                            <p><strong> "<?php echo $row_review["comments"]; ?>" </strong></p>
                            <!-- <div class="single-product-form">
                                <form action="index.html">
                                    <input type="number" placeholder="0">
                                </form>-->
                                <p>
                                    <strong>Ratings:</strong>
                                    <?php 
                                        for($i=1;$i<=5;$i++)
                                        {
                                            if($i<=$row_review["rate"])
                                            {
                                                echo '<i class="fa fa-star" style="color:orange;"></i>';
                                            }
                                            else
                                            {
                                                echo '<i class="fa fa-star"></i>';
                                            }
                                        }
                                    ?>
                                </p>
                                <p><strong> <?php echo $row_review["date"]; ?> </strong></p>
                                <p><strong> <?php echo $row_customer["name"]; ?> </strong></p>
                                <?php
                                    }
                                ?>
                            <a href="index.php?page=body_book_check.php&package_id=<?php echo $row_package["package_id"]; ?>"><button class="btn btn-success">Book Now</button></a>
							
                            
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end single product -->