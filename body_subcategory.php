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
$get_category_id=$_GET["category_id"];

$sql_category="SELECT * FROM packagecategory WHERE category_id='$get_category_id'";
$result_category=mysqli_query($con,$sql_category) or die("sql error in sql_category ".mysqli_error($con));
$row_category=mysqli_fetch_assoc($result_category);
?>


<div class="latest-news ">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="section-title">	
						<h3><span class="orange-text">Category</span> <?php echo $row_category["name"]; ?></h3>
						
					</div>
				</div>
			</div>
		<div class="row">
            <?php
            $sql_packagesubcategory="SELECT * FROM packagesubcategory WHERE status='Active' AND category_id='$row_category[category_id]'";
            $result_packagesubcategory=mysqli_query($con,$sql_packagesubcategory) or die("sql error in sql_packagesubcategory ".mysqli_error($con));
            while($row_packagesubcategory=mysqli_fetch_assoc($result_packagesubcategory))
            {
            ?>
				<div class="col-lg-4 col-md-6">
                    <a href="index.php?page=body_package.php&subcategory_id=<?php echo $row_packagesubcategory["subcategory_id"]; ?>">
					<div class="single-latest-news">
						<div class="product-image mb-1">
							<img src="file/subcategory/<?php echo $row_packagesubcategory["image"].'?'.date("h:i:s"); ?>" width="100%" hight="90%">
						</div>
						<div class="news-text-box">
							<h3><?php echo $row_packagesubcategory["name"]; ?></h3>
							<!-- <p class="blog-meta">
								<span class="author"><i class="fas fa-store"></i> Available in Store</span>
								<span class="date"><i class="fas fa-calendar-check"></i> Updated July 2025</span>
							</p>
							<p class="excerpt">Typically in a liquid or gel form, designed for topical application to the skin. It acts as a potent antioxidant, protecting the skin from environmental damage, brightening the complexion, and reducing the appearance of fine lines and wrinkles.</p> -->
						</div>
					</div>
                    </a>
				</div>
			<?php
            }
            ?>
                
                
			</div>
		</div>
	</div>