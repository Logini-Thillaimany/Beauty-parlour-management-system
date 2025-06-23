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
if($system_usertype=="Admin" || $system_usertype=="Clerk")
{//allow these users to access this page 
include("connection.php");
//insert code start
if(isset($_POST["btnsavene"]))
{
    $sql_generatedid="SELECT dispose_id FROM productdispose ORDER BY dispose_id DESC LIMIT 1";
    $result_generatedid=mysqli_query($con,$sql_generatedid) or die("sql error in sql_generatedid ".mysqli_error($con));
    if(mysqli_num_rows($result_generatedid)==1)
    {// for  except from the first submission
        $row_generatedid=mysqli_fetch_assoc($result_generatedid);
        $generatedid=++$row_generatedid["dispose_id"];
    }
    else
    {//For first time submission
        $generatedid="DIS0000001";
    }
	$sql_insert="INSERT INTO productdispose(dispose_id,product_id,quantity,disposedate,reason)
							VALUES('".mysqli_real_escape_string($con,$generatedid)."',
									'".mysqli_real_escape_string($con,$_POST["txtproductid"])."',
                                    '".mysqli_real_escape_string($con,$_POST["txtquantity"])."',
                                    '".mysqli_real_escape_string($con,date("Y-m-d"))."',
                                    '".mysqli_real_escape_string($con,$_POST["txtreason"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));

    $sql_stock="SELECT quantity from stockne WHERE product_id='".mysqli_real_escape_string($con,$_POST["txtproductid"])."'";
    $result_stock=mysqli_query($con,$sql_stock) or die("sql error in sql_stock ".mysqli_error($con));
    $row_stock=mysqli_fetch_assoc($result_stock);
    $new_quantity=$row_stock["quantity"]-$_POST["txtquantity"];

    $sql_update="UPDATE stockne SET quantity='$new_quantity' WHERE product_id='".mysqli_real_escape_string($con,$_POST["txtproductid"])."'";
    $result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
	if($result_insert)
	{
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=stockne.php&option=view";</script>';
	}
}
//insert code end

//insert code start
if(isset($_POST["btnsave"]))
{
    $sql_generatedid="SELECT dispose_id FROM productdispose ORDER BY dispose_id DESC LIMIT 1";
    $result_generatedid=mysqli_query($con,$sql_generatedid) or die("sql error in sql_generatedid ".mysqli_error($con));
    if(mysqli_num_rows($result_generatedid)==1)
    {// for  except from the first submission
        $row_generatedid=mysqli_fetch_assoc($result_generatedid);
        $generatedid=++$row_generatedid["dispose_id"];
    }
    else
    {//For first time submission
        $generatedid="DIS0000001";
    }
	$sql_insert="INSERT INTO productdispose(dispose_id,purchase_id,product_id,quantity,disposedate,reason)
							VALUES('".mysqli_real_escape_string($con,$generatedid)."',
									'".mysqli_real_escape_string($con,$_POST["txtpurchaseid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtproductid"])."',
                                    '".mysqli_real_escape_string($con,$_POST["txtquantity"])."',
                                    '".mysqli_real_escape_string($con,date("Y-m-d"))."',
                                    '".mysqli_real_escape_string($con,$_POST["txtreason"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));

    $sql_stock="SELECT quantity from stock WHERE product_id='".mysqli_real_escape_string($con,$_POST["txtproductid"])."' AND purchase_id='".mysqli_real_escape_string($con,$_POST["txtpurchaseid"])."'";
    $result_stock=mysqli_query($con,$sql_stock) or die("sql error in sql_stock ".mysqli_error($con));
    $row_stock=mysqli_fetch_assoc($result_stock);
    $new_quantity=$row_stock["quantity"]-$_POST["txtquantity"];

    $sql_update="UPDATE stock SET quantity='$new_quantity' WHERE product_id='".mysqli_real_escape_string($con,$_POST["txtproductid"])."' AND purchase_id='".mysqli_real_escape_string($con,$_POST["txtpurchaseid"])."'";
    $result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
	if($result_insert)
	{
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=stock.php&option=view";</script>';
	}
}
//insert code end
?>
<body>
<?php
if(isset($_GET["option"]))
{
	if($_GET["option"]=="addne")
	{
		//add form
        $get_pk_product_id=$_GET["pk_product_id"];

        $sql_product="SELECT product_id,name,minimumstock,expiretype FROM product WHERE product_id='$get_pk_product_id'";
        $result_product=mysqli_query($con,$sql_product) or die("sql error in sql_product ".mysqli_error($con));
        $row_product=mysqli_fetch_assoc($result_product);
        if($row_product["expiretype"]=="No")
        {
            $backpage="stockne";
        }
        else
        {
            $backpage="stock";
        }

        $sql_stock="SELECT quantity from stockne WHERE product_id='$get_pk_product_id'";
        $result_stock=mysqli_query($con,$sql_stock) or die("sql error in sql_stock ".mysqli_error($con));
        $row_stock=mysqli_fetch_assoc($result_stock);
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Product Dispose</div>
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
											<label for="txtproductid">Product</label>
											<select class="form-control" name="txtproductid" id="txtproductid" required placeholder="Product">
												
												<?php
												$sql_load="SELECT product_id, name FROM product WHERE product_id='$get_pk_product_id'";
												$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
												while($row_load=mysqli_fetch_assoc($result_load))
												{
													echo'<option value="'.$row_load["product_id"].'">'.$row_load["name"].'</option>';
												}
												?>
											</select>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtquantity">Dispose Quantity</label>
											<input type="number" min="1" max="<?php echo $row_stock["quantity"]; ?>" onkeypress="return isNumberKey(event)" class="form-control" name="txtquantity" id="txtquantity" required placeholder="Quantity"/>
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
											<label for="email2">Reason</label>
												<textarea class="form-control" name="txtreason" id="txtreason" required placeholder="Type the reason"></textarea>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- second row end -->
                                
                                
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=<?php echo $backpage; ?>.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
												<input type="reset" class="btn btn-danger" name="btnclear" id="btnclear"  value="Clear"/>
												<input type="submit" class="btn btn-success" name="btnsavene" id="btnsavene"  value="Save"/>
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
		<?php
	}
	else if($_GET["option"]=="add")
	{
		//add form
        $get_pk_product_id=$_GET["pk_product_id"];
        $get_pk_purchase_id=$_GET["pk_purchase_id"];

        $sql_product="SELECT product_id,name,minimumstock,expiretype FROM product WHERE product_id='$get_pk_product_id'";
        $result_product=mysqli_query($con,$sql_product) or die("sql error in sql_product ".mysqli_error($con));
        $row_product=mysqli_fetch_assoc($result_product);
        if($row_product["expiretype"]=="No")
        {
            $backpage="stockne";
        }
        else
        {
            $backpage="stock";
        }

        $sql_stock="SELECT quantity from stock WHERE product_id='$get_pk_product_id' AND purchase_id='$get_pk_purchase_id'";
        $result_stock=mysqli_query($con,$sql_stock) or die("sql error in sql_stock ".mysqli_error($con));
        $row_stock=mysqli_fetch_assoc($result_stock);

		$today=date("Y-m-d");
		$sql_expire="SELECT expiredate from purchaseproduct WHERE purchase_id='$get_pk_purchase_id' AND product_id='$get_pk_product_id' AND expiredate>'$today'";
		$result_expire=mysqli_query($con,$sql_expire) or die("sql error in sql_expire ".mysqli_error($con));
		if(mysqli_num_rows($result_expire)==0)
		{
			$dispose_value=$row_stock["quantity"];
			$dispose_readonly="readonly";
			$dispose_max=$row_stock["quantity"];
			$dispose_reason="Product is expired";
		}
		else
		{	
			$dispose_value="";
			$dispose_readonly="";
			$dispose_max=$row_stock["quantity"];
			$dispose_reason="";	
		}


		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Product Dispose</div>
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
											<label for="txtproductid">Product</label>
											<input type="hidden" class="form-control" name="txtpurchaseid" id="txtpurchaseid" required value="<?php echo $get_pk_purchase_id; ?>" readonly/>
											<select class="form-control" name="txtproductid" id="txtproductid" required placeholder="Product">
												
												<?php
												$sql_load="SELECT product_id, name FROM product WHERE product_id='$get_pk_product_id'";
												$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
												while($row_load=mysqli_fetch_assoc($result_load))
												{
													echo'<option value="'.$row_load["product_id"].'">'.$row_load["name"].'</option>';
												}
												?>
											</select>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtquantity">Dispose Quantity</label>
											<input type="number" min="1" max="<?php echo $dispose_max; ?>" value="<?php echo $dispose_value; ?>" <?php echo $dispose_readonly; ?>  onkeypress="return isNumberKey(event)" class="form-control" name="txtquantity" id="txtquantity" required placeholder="Quantity"/>
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
											<label for="email2">Reason</label>
												<textarea class="form-control" name="txtreason" id="txtreason" required placeholder="Type the reason" <?php echo $dispose_readonly; ?>> <?php echo $dispose_reason; ?></textarea>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- second row end -->
                                
                                
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=<?php echo $backpage; ?>.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
												<input type="reset" class="btn btn-danger" name="btnclear" id="btnclear"  value="Clear"/>
												<input type="submit" class="btn btn-success" name="btnsave" id="btnsave"  value="Save"/>
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
		<?php
	}
	else if($_GET["option"]=="view")
	{
		//view table
        $get_pk_product_id=$_GET["pk_product_id"];

        $sql_product="SELECT product_id,name,minimumstock,expiretype FROM product WHERE product_id='$get_pk_product_id'";
        $result_product=mysqli_query($con,$sql_product) or die("sql error in sql_product ".mysqli_error($con));
        $row_product=mysqli_fetch_assoc($result_product);
        if($row_product["expiretype"]=="No")
        {
            $backpage="stockne";
        }
        else
        {
            $backpage="stock";
        }
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Dispose Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
                            <a href="index.php?page=<?php echo $backpage; ?>.php&option=view"><button class="btn btn-primary">Back to stock</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>Dispose ID</th>
                                        <?php
                                        if($row_product["expiretype"]=="Yes")
                                        {
                                            echo '<th>purchase ID</th>';
                                        }
                                        ?>
										<th>Product ID</th>
                                        <th>Quantity</th>
										<th>Date</th>										
										<th>Reason</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$x=1;									
									$sql_view="SELECT * FROM productdispose WHERE product_id='$get_pk_product_id' ORDER BY disposedate DESC";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{
										echo '<tr>';
											echo '<td>'.$x++.'</td>';
											echo '<td>'.$row_view["dispose_id"].'</td>';
                                            if($row_product["expiretype"]=="Yes")
                                            {
                                                echo '<td>'.$row_view["purchase_id"].'</td>';
                                            }
                                            echo '<td>'.$row_product["name"].'</td>';
											echo '<td>'.$row_view["quantity"].'</td>';
                                            echo '<td>'.$row_view["disposedate"].'</td>';
                                            echo '<td>'.$row_view["reason"].'</td>';											
										echo '</tr>';
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
?>
</body>
<?php 
}
else{// other users redirect to index page
	echo '<script>window.location.href="index.php";</script>';
}
?>