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
$get_subcategory_id=$_GET["subcategory_id"];

$sql_subcategory="SELECT * FROM packagesubcategory WHERE subcategory_id='$get_subcategory_id'";
$result_subcategory=mysqli_query($con,$sql_subcategory) or die("sql error in sql_subcategory ".mysqli_error($con));
$row_subcategory=mysqli_fetch_assoc($result_subcategory);
?>


<div class="latest-news ">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="section-title">	
						<h3><span class="orange-text">Sub-Category</span> <?php echo $row_subcategory["name"]; ?></h3>
						
					</div>
				</div>
			</div>
		<div class="row">
            <?php
            $sql_package="SELECT * FROM package WHERE subcategory_id='$row_subcategory[subcategory_id]' AND package_id IN (SELECT package_id from packageprice WHERE enddate IS NULL)";
            $result_package=mysqli_query($con,$sql_package) or die("sql error in sql_package ".mysqli_error($con));
            while($row_package=mysqli_fetch_assoc($result_package))
            {
                $sql_package_photo="SELECT photo_id,photo,package_id FROM packagephoto  WHERE package_id='$row_package[package_id]'";
                $result_package_photo=mysqli_query($con,$sql_package_photo) or die("sql error in sql_package_photo ".mysqli_error($con));
                $row_package_photo=mysqli_fetch_assoc($result_package_photo);

                //get unit price
                $sql_price="SELECT price,offer FROM packageprice WHERE package_id='$row_package[package_id]' AND enddate IS NULL";
                $result_price=mysqli_query($con,$sql_price) or die("sql error in sql_price ".mysqli_error($con));
                $row_price=mysqli_fetch_assoc($result_price);

                $unit_price=$row_price["price"]*(1-$row_price["offer"]/100);
                if($row_price["offer"]>0)
                {
                    $display_price='<del>'.$row_price["price"].'</del> <span>LKR '.$unit_price.'</span>';
                }
                else
                {
                    $display_price='<span>LKR '.$unit_price.'</span>';
                }
            ?>
				<div class="col-lg-4 col-md-6">
                    
					<div class="single-latest-news">
						<div class="product-image mb-1">
							<img src="file/package/<?php echo $row_package_photo["photo"].'?'.date("h:i:s"); ?>" width="100%" hight="90%">
						</div>
						<div class="news-text-box">
							<h3><?php echo $row_package["name"]; ?></h3>
							<p class="blog-meta">
                                <?php echo $display_price; ?>
								<a href="index.php?page=body_package_single.php&package_id=<?php echo $row_package["package_id"]; ?>"><button class="btn btn-primary">View More</button></a>
								<a href="index.php?page=body_book_check.php&package_id=<?php echo $row_package["package_id"]; ?>"><button class="btn btn-success">Book Now</button></a>
							</p>
							<!-- <p class="excerpt">Typically in a liquid or gel form, designed for topical application to the skin. It acts as a potent antioxidant, protecting the skin from environmental damage, brightening the complexion, and reducing the appearance of fine lines and wrinkles.</p> -->
						</div>
					</div>
				</div>
			<?php
            }
            ?>
                
                
			</div>
		</div>
	</div>