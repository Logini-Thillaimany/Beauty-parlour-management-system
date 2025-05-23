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
if(isset($_POST["btnsave"]))
{
	$sql_insert="INSERT INTO product(product_id,name,description,servicetype,saletype,brand,minimumstock,expiretype)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtproductid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtname"])."',
									'".mysqli_real_escape_string($con,$_POST["txtdescription"])."',
									'".mysqli_real_escape_string($con,$_POST["txtservicetype"])."',
									'".mysqli_real_escape_string($con,$_POST["txtsalestype"])."',
									'".mysqli_real_escape_string($con,$_POST["txtbrand"])."',
									'".mysqli_real_escape_string($con,$_POST["txtminstock"])."',
									'".mysqli_real_escape_string($con,$_POST["txtexpiretype"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));

	//insert product price 
	$sql_insert="INSERT INTO productprice(product_id,startdate,price,offer)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtproductid"])."',
									'".mysqli_real_escape_string($con,date("Y-m-d"))."',
									'".mysqli_real_escape_string($con,$_POST["txtprice"])."',
									'".mysqli_real_escape_string($con,$_POST["txtoffer"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
	if($result_insert)
	{
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=productphoto.php&option=add&pk_product_id='.$_POST["txtproductid"].'";</script>';
	}
}
//insert code end

//update code start
if(isset($_POST["btnsavechanges"]))
{
	$sql_update="UPDATE product SET
								name='".mysqli_real_escape_string($con,$_POST["txtname"])."',
								description='".mysqli_real_escape_string($con,$_POST["txtdescription"])."',
								servicetype='".mysqli_real_escape_string($con,$_POST["txtservicetype"])."',
								saletype='".mysqli_real_escape_string($con,$_POST["txtsalestype"])."',
								brand='".mysqli_real_escape_string($con,$_POST["txtbrand"])."',
								minimumstock='".mysqli_real_escape_string($con,$_POST["txtminstock"])."',
								expiretype='".mysqli_real_escape_string($con,$_POST["txtexpiretype"])."'
								WHERE product_id='".mysqli_real_escape_string($con,$_POST["txtproductid"])."'";
	$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
	if($result_update)
	{
		echo '<script>alert("Successfully UPDATE");
						window.location.href="index.php?page=product.php&option=fullview&pk_product_id='.$_POST["txtproductid"].'";</script>';
	}
}
//update code end
?>
<body>
<script>
	function popup_product(imageName)
	{
		document.getElementById("popup_title").innerHTML='Product Image';
		document.getElementById("popup_body").innerHTML='<img src="file/product/'+imageName+'" width="100%" height="100%">';
	}
</script>
<?php
if(isset($_GET["option"]))
{
	if($_GET["option"]=="add")
	{
		//add form
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for product Addition</div>
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
											<label for="txtproductid">product ID</label>
											<?php
												$sql_generatedid="SELECT product_id FROM product ORDER BY product_id DESC LIMIT 1";
												$result_generatedid=mysqli_query($con,$sql_generatedid) or die("sql error in sql_generatedid ".mysqli_error($con));
												if(mysqli_num_rows($result_generatedid)==1)
												{// for  except from the first submission
													$row_generatedid=mysqli_fetch_assoc($result_generatedid);
													$generatedid=++$row_generatedid["product_id"];
												}
												else
												{//For first time submission
													$generatedid="PR0001";
												}
											?>
											<input type="text" class="form-control" name="txtproductid" id="txtproductid" required placeholder="Product ID" value="<?php echo $generatedid;?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtname">Name</label>
											<input type="text" onkeypress="return isTextKey(event)" class="form-control" name="txtname" id="txtname" required placeholder="Product Name"/>
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
											<label for="txtservicetype">Service type</label>
											<select class="form-control" name="txtservicetype" id="txtservicetype" required placeholder="service type">
												<option value="" disabled selected>Select Service type </option>
												<?php
												$sql_load="SELECT category_id, name FROM packagecategory ";
												$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
												while($row_load=mysqli_fetch_assoc($result_load))
												{
													echo'<option value="'.$row_load["category_id"].'">'.$row_load["name"].'</option>';
												}
												?>
												<option value="Both" >Both services</option>
											</select>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtsalestype">Sales type</label>
											<select class="form-control" name="txtsalestype" id="txtsalestype" required placeholder="sales/Not">
												<option value="" disabled selected>Select Sales type </option>
												<option value="Sale" >Sale</option>
												<option value="NotSale" >Not for sale</option>
											</Select>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- second row end -->
								
								<!-- third row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtbrand">Brand</label>
											<input type="text" class="form-control" name="txtbrand" id="txtbrand" required placeholder="Brand name"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtminstock">minimum stock</label>
											<input type="number" onkeypress="return isNumberKey(event)" min="1"  class="form-control" name="txtminstock" id="txtminstock" required placeholder="minimum strock"/>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- third row end -->
								
								<!-- fourth row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtexpiretype">Expire type</label> <br>
											<input type="radio"  name="txtexpiretype" id="txtexpiretype" required value="Yes" placeholder="Expired/Not"/> Yes 
											<input type="radio"  name="txtexpiretype" id="txtexpiretype" required value="No" placeholder="Expired/Not"/> No

										</div>
										<!-- column one end -->
										
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtdescription">Description</label>
											<textarea class="form-control" name="txtdescription" id="txtdescription" required placeholder="description"></textarea>
										</div>
										<!-- column one end -->
									</div>
								</div>
								<!-- fourth row end -->

								<hr>Price Details<br>
								<!-- fifth row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtprice">Price</label>
											<input type="number" step="0.01" min="1" onkeypress="return isNumberKey(event)" class="form-control" name="txtprice" id="txtprice" required placeholder="Price .Rs"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtoffer">Offer</label>
											<input type="number" step="0.01" min="0" max="50" class="form-control" onkeypress="return isNumberKey(event)" name="txtoffer" id="txtoffer" required placeholder="offer %"/>
											<font color="#ED2939"> *Offer must be in percentage %</font>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- fifth row end -->
								
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=product.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Product Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=product.php&option=add"><button class="btn btn-primary">Add Product</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>Product ID</th>
										<th>Name</th>
										<th>Service type</th>
										<th>Brand</th>
										<th>Minimum stock</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql_view="SELECT product_id,name,servicetype,brand,minimumstock FROM product";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{	
										
										if ($row_view["servicetype"]=="Both")
										{
											$service_type=$row_view["servicetype"];
										}
										else
										{
											$sql_servicetype="SELECT name from packagecategory WHERE category_id='$row_view[servicetype]'";
											$result_servicetype=mysqli_query($con,$sql_servicetype) or die("sql error in sql_servicetype ".mysqli_error($con));
											$row_servicetype=mysqli_fetch_assoc($result_servicetype);
											$service_type=$row_servicetype["name"];
										}

										$sql_checkprice="SELECT startdate from productprice WHERE product_id='$row_view[product_id]' AND enddate IS NULL";
										$result_checkprice=mysqli_query($con,$sql_checkprice) or die("sql error in sql_checkprice ".mysqli_error($con));
										echo '<tr>';
											echo '<td>'.$row_view["product_id"].'</td>';
											echo '<td>'.$row_view["name"].'</td>';
											echo '<td>'.$service_type.'</td>';
											echo '<td>'.$row_view["brand"].'</td>';
											echo '<td>'.$row_view["minimumstock"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=product.php&option=fullview&pk_product_id='.$row_view["product_id"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
												if(mysqli_num_rows($result_checkprice)>0)
												{
													echo '<a href="index.php?page=product.php&option=edit&pk_product_id='.$row_view["product_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
													if($system_usertype=="Admin")
													{
														echo '<a onclick="return delete_confirm()" href="index.php?page=product.php&option=delete&pk_product_id='.$row_view["product_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
													}
												}
											echo '</td>';
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
	else if($_GET["option"]=="fullview")
	{
		//fullview table
		$get_pk_product_id=$_GET["pk_product_id"];
		
		$sql_fullview="SELECT * FROM product WHERE product_id='$get_pk_product_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		if ($row_fullview["servicetype"]=="Both")
		{
			$service_type=$row_fullview["servicetype"];
		}
		else
		{
			$sql_servicetype="SELECT name from packagecategory WHERE category_id='$row_fullview[servicetype]'";
			$result_servicetype=mysqli_query($con,$sql_servicetype) or die("sql error in sql_servicetype ".mysqli_error($con));
			$row_servicetype=mysqli_fetch_assoc($result_servicetype);
			$service_type=$row_servicetype["name"];
		}

		$sql_checkprice="SELECT startdate from productprice WHERE product_id='$row_fullview[product_id]' AND enddate IS NULL";
		$result_checkprice=mysqli_query($con,$sql_checkprice) or die("sql error in sql_checkprice ".mysqli_error($con));
										
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Product Full Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="display table table-striped table-hover">
								<tr><th>product ID</th><td><?php echo $row_fullview["product_id"]; ?></td></tr>
								<tr><th>Name</th><td><?php echo $row_fullview["name"]; ?></td></tr>
								<tr><th>Description</th><td><?php echo $row_fullview["description"]; ?></td></tr>
								<tr><th>Service type</th><td><?php echo $service_type; ?></td></tr>
								<tr><th>Brand</th><td><?php echo $row_fullview["brand"]; ?></td></tr>
								<tr><th>Sale type</th><td><?php echo $row_fullview["saletype"]; ?></td></tr>
								<tr><th>Minimum Strock</th><td><?php echo $row_fullview["minimumstock"]; ?></td></tr>
								<tr><th>Expire type</th><td><?php echo $row_fullview["expiretype"]; ?></td></tr>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=product.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
											<?php 
											if(mysqli_num_rows($result_checkprice)>0)
											{
											?>
												<a href="index.php?page=product.php&option=edit&pk_product_id=<?php echo $row_fullview["product_id"]; ?>"><button class="btn btn-info">Edit</button></a> 
											<?php 
											}
											?>
										</center>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!---Produvt Price view details -->

		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">productprice Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
						<?php 
							if(mysqli_num_rows($result_checkprice)==0)
							{
							?>
							<a href="index.php?page=productprice.php&option=add&pk_product_id=<?php echo $row_fullview["product_id"]; ?>"><button class="btn btn-primary">Add productprice</button></a><br><br>
							<?php 
							}
							?>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>Start date</th>
										<th>End date</th>
										<th>Price</th>
										<th>Offer</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$x=1;
									$sql_view="SELECT product_id,startdate,enddate,price,offer FROM productprice WHERE product_id='$row_fullview[product_id]' ORDER BY startdate DESC";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{	
										
										echo '<tr>';
											echo '<td>'.$x++.'</td>';
											echo '<td>'.$row_view["startdate"].'</td>';
											echo '<td>'.$row_view["enddate"].'</td>';
											echo '<td>'.$row_view["price"].'</td>';
											echo '<td>'.$row_view["offer"].'</td>';
											echo '<td>';
											if($row_view["enddate"]==""){
												echo '<a href="index.php?page=productprice.php&option=edit&pk_product_id='.$row_view["product_id"].'&pk_startdate='.$row_view["startdate"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';

											}
											echo '</td>';
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

		<!---Produvt photo view details -->

		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Product photo Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
						<?php 
						if(mysqli_num_rows($result_checkprice)>0)
						{
						?>
							<a href="index.php?page=productphoto.php&option=add&pk_product_id=<?php echo $row_fullview["product_id"]; ?>"><button class="btn btn-primary">Add Product photo</button></a><br><br>
						<?php
						}
						?>
							<table  class="display table table-striped table-hover">
								
								<tbody>
									<?php
									$sql_view="SELECT productphoto_id,photo,product_id FROM productphoto WHERE product_id='$row_fullview[product_id]'";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									$totalImage=mysqli_num_rows($result_view);
									$x=0;
									echo '<tr>';
									while($row_view=mysqli_fetch_assoc($result_view))
									{
										
										echo '<td><center>';
										?>
										<img src="file/product/<?php echo $row_view["photo"].'?'.date("h:i:s"); ?>" onClick="popup_product('<?php echo $row_view["photo"].'?'.date("h:i:s"); ?>')" data-bs-toggle="modal" data-bs-target="#system_popup" width="150" hight="150">
										<?php
										if(mysqli_num_rows($result_checkprice)>0)
										{
											echo '<br><br><a onclick="return delete_confirm()" href="index.php?page=productphoto.php&option=delete&pk_productphoto_id='.$row_view["productphoto_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
										}
										echo '</center></td>';
										$x++;
										if($x==$totalImage){
											if($x%3==0){
												echo'</tr>';
											}
											else if($x%3==1){
												echo'<td></td><td></td></tr>';
											}
											else if($x%3==2){
												echo'<td></td></tr>';
											}
										}
										else
										{
											if($x%3==0){
												echo'</tr><tr>';
											}
										}
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
	else if($_GET["option"]=="edit")
	{
		//edit form
		$get_pk_product_id=$_GET["pk_product_id"];
		
		$sql_edit="SELECT * FROM product WHERE product_id='$get_pk_product_id'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit ".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Edit product </div>
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
											<label for="txtproductid">product ID</label>
											<input type="text" class="form-control" name="txtproductid" id="txtproductid" required placeholder="Product ID" value="<?php echo $row_edit["product_id"]; ?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtname">Name</label>
											<input type="text" onkeypress="return isTextKey(event)" class="form-control" name="txtname" id="txtname" required placeholder="Product Name" value="<?php echo $row_edit["name"]; ?>" />
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
											<label for="txtservicetype">Service type</label>
											<select class="form-control" name="txtservicetype" id="txtservicetype" required placeholder="service type">
												<?php
												$sql_load="SELECT category_id, name FROM packagecategory ";
												$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
												while($row_load=mysqli_fetch_assoc($result_load))
												{
													if($row_edit["servicetype"]==$row_load["category_id"])
													{
														echo'<option selected value="'.$row_load["category_id"].'">'.$row_load["name"].'</option>';
													}
													else
													{
														echo'<option value="'.$row_load["category_id"].'">'.$row_load["name"].'</option>';
													}
												}
												if($row_edit["servicetype"]=="Both")
												{
													echo'<option selected value="Both">Both</option>';
												}
												else
												{
													echo'<option value="Both">Both</option>';
												}
												?>
											</select>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtsalestype">Sales type</label>
											<select class="form-control" name="txtsalestype" id="txtsalestype" required placeholder="sales/Not"  >
												<?php  
												$salestype=array("Sale","NotSale");
												for($x=0;$x<count($salestype);$x++)
												{
													if($row_edit["saletype"]==$salestype[$x])
													{
														echo'<option selected value="'.$salestype[$x].'">'.$salestype[$x].'</option>';
													}
													else
													{
														echo'<option value="'.$salestype[$x].'">'.$salestype[$x].'</option>';
													}
												}
												?>
											</select>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- second row end -->
								
								<!-- third row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtbrand">Brand</label>
											<input type="text" class="form-control" name="txtbrand" id="txtbrand" required placeholder="Brand name" value="<?php echo $row_edit["brand"]; ?>" />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtminstock">minimum stock</label>
											<input type="number" min="1" onkeypress="return isNumberKey(event)" class="form-control" name="txtminstock" id="txtminstock" required placeholder="minimum strock" value="<?php echo $row_edit["minimumstock"]; ?>" />
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- third row end -->
								
								<!-- fourth row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtexpiretype">Expire type</label>
											<?php  
												$expiretype=array("Yes","No");
												for($x=0;$x<count($expiretype);$x++)
												{
													if($row_edit["expiretype"]==$expiretype[$x])
													{
														echo'<input type="radio" checked name="txtexpiretype" id="txtexpiretype" required value="'.$expiretype[$x].'" placeholder="Expired/Not"/>'.$expiretype[$x];
														
													}
													else
													{
														echo'<input type="radio"  name="txtexpiretype" id="txtexpiretype" required value="'.$expiretype[$x].'" placeholder="Expired/Not"/>'.$expiretype[$x];
													}
												}
												?>
										</div>
										<!-- column one end -->
										
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtdescription">Description</label>
											<textarea class="form-control" name="txtdescription" id="txtdescription" required placeholder="description"><?php echo $row_edit["description"]; ?></textarea>
										</div>
										<!-- column one end -->
									</div>
								</div>
								<!-- fourth row end -->
								
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=product.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
												<input type="reset" class="btn btn-danger" name="btncancel" id="btncancel"  value="Cancel"/>
												<input type="submit" class="btn btn-success" name="btnsavechanges" id="btnsavechanges"  value="Save changes"/>
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
	else if($_GET["option"]=="delete")
	{
		//delete code
		$get_pk_product_id=$_GET["pk_product_id"];

		$sql_checkprice="SELECT startdate from productprice WHERE product_id='$get_pk_product_id' AND enddate IS NULL";
		$result_checkprice=mysqli_query($con,$sql_checkprice) or die("sql error in sql_checkprice ".mysqli_error($con));
		$row_checkprice=mysqli_fetch_assoc($result_checkprice);

		$today=date("Y-m-d");
		
		$sql_delete="UPDATE  productprice SET enddate='$today' WHERE product_id='$get_pk_product_id' AND startdate='$row_checkprice[startdate]'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete)
		{
			echo '<script>alert("Successfully Deleted");
						window.location.href="index.php?page=product.php&option=view";</script>';
		}		
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