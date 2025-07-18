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
?>
<ul>
								<li class="current-list-item"><a href="index.php">Home</a>
								</li>
								<li><a href="#">Parlor</a>
									<ul class="sub-menu">
										<?php
										$sql_category="SELECT * FROM packagecategory WHERE status='Active'";
										$result_category=mysqli_query($con,$sql_category) or die("sql error in sql_category ".mysqli_error($con));
										while($row_category=mysqli_fetch_assoc($result_category))
										{
											echo '<li><a href="index.php?page=body_subcategory.php&category_id='.$row_category["category_id"].'">'.$row_category["name"].'</a></li>';
										}
										?>
									</ul>
								</li>
								<!-- <li><a href="">Booking</a>
									<ul class="sub-menu">
										<li><a href="shop.html">Shop</a></li>
										<li><a href="checkout.html">Check Out</a></li>
										<li><a href="single-product.html">Single Product</a></li>
										<li><a href="cart.html">Cart</a></li>
									</ul>
								</li> -->
								<li><a href="index.php?page=about.php">About</a></li>
								<li><a href="index.php?page=contact.php">Contact</a></li>
								<li><a href="index.php?page=login.php">Login</a></li>
								<li>
									<div class="header-icons">
										<!--<a class="shopping-cart" href="cart.html"><i class="fas fa-shopping-cart"></i></a>
										<a class="mobile-hide search-bar-icon" href="#"><i class="fas fa-search"></i></a>-->
									</div>
								</li>
							</ul>