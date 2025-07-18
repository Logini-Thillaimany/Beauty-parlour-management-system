<script>
	function popup_category(imageName)
	{
		document.getElementById("popup_title").innerHTML='Category Image';
		document.getElementById("popup_body").innerHTML='<img src="file/category/'+imageName+'" width="100%" height="100%">';
	}
</script>

<script>
	function popup_subcategory(imageName)
	{
		document.getElementById("popup_title").innerHTML='subcategory Image';
		document.getElementById("popup_body").innerHTML='<img src="file/subcategory/'+imageName+'" width="100%" height="100%">';
	}
</script>
<!-- features list section -->
<div class="list-section pt-80 pb-80">
	<div class="container">
		<div class="row">
			<div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
				<div class="list-box d-flex align-items-center">
					<div class="list-icon">
						<i class="fas fa-star"></i>
					</div>
					<div class="content">
						<h3>Top Quality</h3>
						<p>Elegant looks, expert artists</p>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
				<div class="list-box d-flex align-items-center">
					<div class="list-icon">
						<i class="fas fa-tags"></i>
					</div>
					<div class="content">
						<h3>Best Price</h3>
						<p>Affordable packages, no hidden fees</p>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6">
				<div class="list-box d-flex justify-content-start align-items-center">
					<div class="list-icon">
						<i class="fas fa-headset"></i>
					</div>
					<div class="content">
						<h3>Best Support</h3>
						<p>Friendly and timely care always</p>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
<!-- end features list section -->

<!-- Category section (Slider style like Testimonials) -->
<div class="category-section mt-80 mb-100">
	<div class="container">
		<div class="row">
			<div class="col-lg-8 offset-lg-2 text-center">
				<div class="section-title">
					<h3><span class="orange-text">Our</span> Services</h3>
					<p>Choose from our wide range of makeup and salon packages tailored to your beauty needs.</p>
				</div>
			</div>
		</div>

		<div class="d-flex justify-content-center flex-wrap gap-4">
			<?php 
			$sql_Category="SELECT * FROM packagecategory";
			$result_Category=mysqli_query($con,$sql_Category) or die("sql error in sql_Category ".mysqli_error($con));
			while($row_Category=mysqli_fetch_assoc($result_Category)) 
			{
			?>
			<div class="single-product-item p-3 text-center" style="width: 350px;">
				<div class="product-image mb-1 ">
					<?php echo '<a href="index.php?page=body_subcategory.php&category_id='.$row_Category["category_id"].'">'; ?>
						<img src="file/category/<?php echo $row_Category["image"].'?'.date("h:i:s"); ?>" 
							onClick="popup_category('<?php echo $row_Category["image"].'?'.date("h:i:s"); ?>')" 
							data-bs-toggle="modal" data-bs-target="#system_popup" 
							style="width: 100%; height: 200px;  border-radius: 10px;">
					</a>
				</div>
				<h3><?php echo $row_Category["name"]; ?></h3>
			</div>
			<?php 
			}
			?>
			
		</div>
	</div>
</div>
<!-- end category-section -->

<!-- cart banner section -->
<section class="cart-banner">
	<div class="container">
		<div class="row clearfix">
			<!--Image Column-->
			<div class="image-column col-lg-6">
				<div class="image">
					<?php
					$sql_addvertisement="SELECT * FROM advertisement WHERE status='Publish' Limit 1 ";
					$result_addvertisement=mysqli_query($con,$sql_addvertisement) or die("sql error in sql_view ".mysqli_error($con));
					while($row_addvertisement=mysqli_fetch_assoc($result_addvertisement))
					{
						?>
						<img src="file/advertisement/<?php echo $row_addvertisement["image"].'?'.date("h:i:s"); ?>" width="75%" hight="50px">
						<?php
					}	
					?>
				</div>
				<div class="price-box">
					<div class="inner-price">
						<span class="price">
							<strong>20%</strong> <br> off per Booking
						</span>
					</div>
				</div>
			</div>
			<!--Content Column-->
			<div class="content-column col-lg-6">
				<h3><span class="orange-text">Deal</span> of the month</h3>
				<h4>Signature Bridal Package</h4>
				<div class="text">Experience the perfect blend of elegance and tradition. This premium bridal package includes HD makeup, herbal facial, hair styling, saree draping & accessories – all tailored to make you shine on your special day.
									Book now and enjoy exclusive discounts for this month only!</div>
				<!--Countdown Timer-->
				<div class="time-counter">
					<div class="time-countdown clearfix" data-countdown="2025/7/31">
						<div class="counter-column">
							<div class="inner">
								<span class="count">00</span>Days
							</div>
						</div> 
						<div class="counter-column">
							<div class="inner">
								<span class="count">00</span>Hours
							</div>
						</div>  
						<div class="counter-column">
							<div class="inner">
								<span class="count">00</span>Mins
							</div>
						</div>  
						<div class="counter-column">
							<div class="inner">
								<span class="count">00</span>Secs
							</div>
						</div>
					</div>
				</div>
				<!-- <a href="index.php?page=body_book_check.php&package_id=<?php echo $row_package["package_id"]; ?>" class="cart-btn mt-3"><i class="fas fa-calendar-alt"></i> Book Now</a> -->
			</div>
		</div>
	</div>
</section>
<!-- end cart banner section -->

<!-- Sub Category section -->
<div class="product-section  mt-80 ">
	<div class="container">
		<div class="row">
			<div class="col-lg-8 offset-lg-2 text-center">
				<div class="section-title mb-1">	
					<h3><span class="orange-text">Sub</span> Categories</h3>
					<p>Explore our service subcategories like facials, haircuts, bridal makeup, and more.</p>
				</div>
			</div>
		</div>

		<!-- Scrollable row -->
		<div class="scrolling-wrapper d-flex flex-nowrap overflow-auto justify-content-start px-4 gap-3" style="scrollbar-width: none; -ms-overflow-style: none;">
			<?php 
			$sql_SubCategory = "SELECT * FROM packagesubcategory";
			$result_SubCategory = mysqli_query($con, $sql_SubCategory) or die("SQL error in sql_SubCategory: ".mysqli_error($con));
			while($row_SubCategory = mysqli_fetch_assoc($result_SubCategory)) {
			?>
			<a href="index.php?page=body_package.php&subcategory_id=<?php echo $row_SubCategory["subcategory_id"]; ?>">
				<div class="card flex-shrink-0  style="width: 300px; margin-right: 16px;">
					<div class="single-product-item p-3 shadow-sm">
						<div class="product-image mb-2">
								<img src="file/subcategory/<?php echo $row_SubCategory['image'].'?'.date('h:i:s'); ?>" 
									onClick="popup_subcategory('<?php echo $row_SubCategory['image'].'?'.date('h:i:s'); ?>')" 
									data-bs-toggle="modal" data-bs-target="#system_popup" 
									style="width: 100%; height: 150px; object-fit: cover; border-radius: 10px;">
						</div class="p-3">
						<h3><?php echo $row_SubCategory["name"]; ?></h3>
					</div>
				</div>
			</a>
			<?php } ?>
		</div>
	</div>
</div>
<!-- end sub category section -->


<!-- Review section -->
<div class="testimonail-section mt-80">
	<div class="container">
		<div class="row">
			<div class="col-lg-10 offset-lg-1 text-center">
				<div class="testimonial-sliders">
					<?php
					$sql_view_review="SELECT * FROM review";
					$result_view_review=mysqli_query($con,$sql_view_review) or die("sql error in sql_view_review ".mysqli_error($con));
					while($row_view_review=mysqli_fetch_assoc($result_view_review))
					{	
						$sql_package="SELECT * from package WHERE package_id='$row_view_review[package_id]'";
						$result_package=mysqli_query($con,$sql_package) or die("sql error in sql_package ".mysqli_error($con));
						$row_package=mysqli_fetch_assoc($result_package);
						
						$sql_SubCategory="SELECT * from packagesubcategory WHERE subcategory_id='$row_package[subcategory_id]'";
						$result_SubCategory=mysqli_query($con,$sql_SubCategory) or die("sql error in sql_SubCategory ".mysqli_error($con));
						$row_SubCategory=mysqli_fetch_assoc($result_SubCategory);

						$sql_package_photo="SELECT photo_id,photo,package_id FROM packagephoto  WHERE package_id='$row_package[package_id]'";
						$result_package_photo=mysqli_query($con,$sql_package_photo) or die("sql error in sql_package_photo ".mysqli_error($con));
						$row_package_photo=mysqli_fetch_assoc($result_package_photo);

						{
						?>
						<div class="single-testimonial-slider">
							<div class="single-product-item p-3">
								<div class="client-avater">
									<img src="file/package/<?php echo $row_package_photo["photo"].'?'.date("h:i:s"); ?>"  style="border-radius: 10px;">
								</div>
								<div class="client-meta">
									<h3><?php echo $row_SubCategory["name"]; ?><span><?php echo $row_package["name"]; ?></span></h3>
									<p class="testimonial-body">
										"<?php echo $row_view_review["comments"]; ?>"
										<br>
										<span>
											<?php 
												for($i=1;$i<=5;$i++)
												{
													if($i<=$row_view_review["rate"])
													{
														echo '<i class="fa fa-star" style="color:orange;"></i>';
													}
													else
													{
														echo '<i class="fa fa-star"></i>';
													}
												}
											?>
										</span>
									</p>
									<div class="last-icon">
										<i class="fas fa-quote-right"></i>
									</div>
								</div>
							</div>
						</div>
						<?php
						}
					} // end while loop for reviews
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end testimonail-section -->
	
<!-- parlour products section -->
	<div class="latest-news ">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="section-title">	
						<h3><span class="orange-text">Selling</span> Products</h3>
						<p>Explore our exclusive in-store beauty products available only at our parlour.<br> Visit us to purchase directly.</p>
					</div>
				</div>
			</div>
		<div class="row">
				<div class="col-lg-4 col-md-6">
					<div class="single-latest-news">
						<div class="product-image mb-1">
							<img src="file/product/PPR00009.jpg" onClick="popup_product('PPR00009.jpg')" data-bs-toggle="modal" data-bs-target="#system_popup" width="100%" hight="90%">
						</div>
						<div class="news-text-box">
							<h3>Vitamin-C Serum</h3>
							<p class="blog-meta">
								<span class="author"><i class="fas fa-store"></i> Available in Store</span>
								<span class="date"><i class="fas fa-calendar-check"></i> Updated July 2025</span>
							</p>
							<p class="excerpt">Typically in a liquid or gel form, designed for topical application to the skin. It acts as a potent antioxidant, protecting the skin from environmental damage, brightening the complexion, and reducing the appearance of fine lines and wrinkles.</p>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="single-latest-news">
						<div class="product-image mb-1">
							<img src="file/product/PPR00003.jpg" onClick="popup_product('PPR00003.jpg')" data-bs-toggle="modal" data-bs-target="#system_popup" width="100%" hight="90%">
						</div>
						<div class="news-text-box">
							<h3><a href="#">Facial Kit</a></h3>
							<p class="blog-meta">
								<span class="author"><i class="fas fa-store"></i>  Available in Store</span>
								<span class="date"><i class="fas fa-calendar-check"></i> Updated July 2025</span>
							</p>
							<p class="excerpt">A facial kit is a collection of skincare products, often including cleanser, toner, exfoliator, mask, serum, and moisturizer, designed to provide a complete skincare routine in a convenient package.</p>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6 offset-md-3 offset-lg-0">
					<div class="single-latest-news">
						<div class="product-image mb-1">
							<img src="file/product/Hairmask.jpg" onClick="popup_product('Hairmask.jpg')" data-bs-toggle="modal" data-bs-target="#system_popup" width="100%" hight="90%">
						</div>
						<div class="news-text-box">
							<h3><a href="#">Hair Spa Pack</a></h3>
							<p class="blog-meta">
								<span class="author"><i class="fas fa-store"></i> Available in Store</span>
								<span class="date"><i class="fas fa-calendar-check"></i> Updated July 2025</span>
							</p>
							<p class="excerpt">Boost your hair health with our in-house organic spa product. Only available in parlour outlet</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end latest news -->