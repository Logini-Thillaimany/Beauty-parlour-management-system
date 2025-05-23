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
	$sql_insert="INSERT INTO package(package_id,name,duration,description,subcategory_id)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtpackageid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtname"])."',
									'".mysqli_real_escape_string($con,$_POST["txtduration"])."',
									'".mysqli_real_escape_string($con,$_POST["txtdescription"])."',
									'".mysqli_real_escape_string($con,$_POST["txtsubcategory"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));

	//insert code start
	$sql_insert="INSERT INTO packageprice(package_id,startdate,price,offer)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtpackageid"])."',
									'".mysqli_real_escape_string($con,date("Y-m-d"))."',
									'".mysqli_real_escape_string($con,$_POST["txtprice"])."',
									'".mysqli_real_escape_string($con,$_POST["txtoffer"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));

	//insert package product
	$totalLoop=$_POST["txtloop"];
	for($x=1;$x<$totalLoop;$x++)
	{
		if(isset($_POST["txtproductid_".$x]))
		{
			$sql_insert="INSERT INTO packageproduct(package_id,product_id,status)
								VALUES('".mysqli_real_escape_string($con,$_POST["txtpackageid"])."',
										'".mysqli_real_escape_string($con,$_POST["txtproductid_".$x])."',
										'".mysqli_real_escape_string($con,"Active")."')";
			$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
		}
	}
	
	if($result_insert)
	{
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=packagephoto.php&option=add&pk_package_id='.$_POST["txtpackageid"].'";</script>';
	}
}
//insert code end

//update code start
if(isset($_POST["btnsavechanges"]))
{
	$sql_update="UPDATE package SET
								name='".mysqli_real_escape_string($con,$_POST["txtname"])."',
								duration='".mysqli_real_escape_string($con,$_POST["txtduration"])."',
								description='".mysqli_real_escape_string($con,$_POST["txtdescription"])."',
								subcategory_id='".mysqli_real_escape_string($con,$_POST["txtsubcategory"])."'
								WHERE package_id='".mysqli_real_escape_string($con,$_POST["txtpackageid"])."'";
	$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
	if($result_update)
	{
		echo '<script>alert("Successfully update");
						window.location.href="index.php?page=package.php&option=fullview&pk_package_id='.$_POST["txtpackageid"].'";</script>';
	}
}
//update code end
?>
<body>
<script>
	function popup_product(imageName)
	{
		document.getElementById("popup_title").innerHTML='Package Image';
		document.getElementById("popup_body").innerHTML='<img src="file/package/'+imageName+'" width="100%" height="100%">';
	}
</script>
<script>
	function load_subcategory()
	{
		var category_id=document.getElementById("txtcategory").value;
		if(category_id!="")
		{
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() 
			{
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
				{
					var responsevalue = xmlhttp.responseText.trim();
					document.getElementById("txtsubcategory").innerHTML=responsevalue;
				}
			};
			xmlhttp.open("GET", "ajaxpage.php?frompage=package_subcategory&ajax_category_id=" + category_id, true);
			xmlhttp.send();
		}
		else{
			document.getElementById("txtsubcategory").innerHTML='<option value="" disabled selected>Select Sub category </option>';
		}
	}
</script>
<script>
	function check_checkbox()
	{
		var totalLoop=document.getElementById("txtloop").value;
		for(var x=1;x<totalLoop; x++)
		{
			if(document.getElementById("txtproductid_"+x).checked==true)
			{
				return true;
			}
		}
		alert("Please select Atleat one product!");
		return false;
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
						<div class="card-title"> Form for Package Addition</div>
					</div>
					<div class="card-body">
						<div class="row">
							<!-- form start -->
							<form method="POST" action="" onsubmit="return check_checkbox()">
								
								<!-- one row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtpackageid">Package ID</label>
											<?php
												$sql_generatedid="SELECT package_id FROM package ORDER BY package_id DESC LIMIT 1";
												$result_generatedid=mysqli_query($con,$sql_generatedid) or die("sql error in sql_generatedid ".mysqli_error($con));
												if(mysqli_num_rows($result_generatedid)==1)
												{// for  except from the first submission
													$row_generatedid=mysqli_fetch_assoc($result_generatedid);
													$generatedid=++$row_generatedid["package_id"];
												}
												else
												{//For first time submission
													$generatedid="PKG0001";
												}
											?>
											<input type="text" class="form-control" name="txtpackageid" id="txtpackageid" required placeholder="Package ID" value="<?php echo $generatedid;?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtsubcategory">category</label>
											<select class="form-control" name="txtcategory" id="txtcategory" onchange="load_subcategory()" required placeholder="category ID" >
												<option value="" disabled selected>Select  category </option>
													<?php
													$sql_load="SELECT  category_id, name FROM packagecategory ";
													$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
													while($row_load=mysqli_fetch_assoc($result_load))
													{
														echo'<option value="'.$row_load["category_id"].'">'.$row_load["name"].'</option>';
													}
													?>
											</select>
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
											<label for="txtsubcategory">Sub category</label>
											<select class="form-control" name="txtsubcategory" id="txtsubcategory" required placeholder="Sub category ID" >
												<option value="" disabled selected>Select Sub category </option>
											</select>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtname">Name</label>
											<input type="text" onkeypress="return isTextKey(event)" class="form-control" name="txtname" id="txtname" required placeholder="Name"/>
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
											<label for="txtduration">Duration</label>
											<input type="number" min="1" max="360" onkeypress="return isNumberKey(event)" class="form-control" name="txtduration" id="txtduration" required placeholder="time duration"/>
											<font color="#ED2939"> * Please enter the duration in minutes. </font>
										</div>
										<!-- column ine end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtdescription">description</label>
											<textarea class="form-control" name="txtdescription" id="txtdescription" required placeholder="Package description"></textarea>
										</div>
										<!-- column  two end -->
									</div>
								</div>
								<!-- third row end -->

								<hr>Price Details<br>
								<!--fifth  row start -->
								<div class="form-group">
									<div class="row">
									    <!-- column one start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtprice">Price</label>
											<input type="number"  step="0.01" min="1" onkeypress="return isNumberKey(event)" class="form-control" name="txtprice" id="txtprice" required placeholder="Price .Rs"/>
										</div>
										<!-- column one end --> 
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtoffer">offer</label>
											<input type="number" step="0.01" min="0" max="50"  class="form-control" onkeypress="return isNumberKey(event)" name="txtoffer" id="txtoffer" required placeholder="Offer %"/>
											<font color="#ED2939"> *Offer must be in percentage %</font>
										</div>
										<!-- column one end -->
									</div>
								</div>
								<!-- fifth row end -->

								<hr>Package product Details<br>
								<div class="row">
									<div class="col-md-12 col-lg-12">
										<table  class="table">
									<?php
										$sql_view="SELECT product_id,name FROM product  WHERE product_id IN (SELECT product_id FROM productprice WHERE enddate IS NULL)";
										$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
										$totalproduct=mysqli_num_rows($result_view);
										$x=0;
										$y=1;
										echo '<tr>';
										while($row_view=mysqli_fetch_assoc($result_view))
										{	
											echo '<td>';
												echo '<input type="checkbox" name="txtproductid_'.$y.'" id="txtproductid_'.$y.'" value="'.$row_view["product_id"].'"> '.$row_view["name"];
											echo '</td>';

											$x++;
											$y++;
											if($x==$totalproduct){
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
								
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=package.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
						<h4 class="card-title">package Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=package.php&option=add"><button class="btn btn-primary">Add package</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>package ID</th>
										<th>Name</th>
										<th>Duration</th>
										<th>Subcategory</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql_view="SELECT package_id,name,duration,subcategory_id FROM package";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{

										$sql_subcategoryname="SELECT name from packagesubcategory WHERE subcategory_id='$row_view[subcategory_id]'";
										$result_subcategoryname=mysqli_query($con,$sql_subcategoryname) or die("sql error in sql_subcategoryname ".mysqli_error($con));
										$row_subcategoryname=mysqli_fetch_assoc($result_subcategoryname);
										
										$sql_checkprice="SELECT startdate from packageprice WHERE package_id='$row_view[package_id]' AND enddate IS NULL";
										$result_checkprice=mysqli_query($con,$sql_checkprice) or die("sql error in sql_checkprice ".mysqli_error($con));

										$hrs=intdiv($row_view["duration"],60);
										$mins=$row_view["duration"]%60;

										if($mins>0){
											$visibleDuration=$hrs.'H '.$mins.'Mins';
										}
										else
										{
											$visibleDuration=$hrs.'H';
										}
										echo '<tr>';
											echo '<td>'.$row_view["package_id"].'</td>';
											echo '<td>'.$row_view["name"].'</td>';
											echo '<td>'.$visibleDuration.'</td>';
											echo '<td>'.$row_subcategoryname["name"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=package.php&option=fullview&pk_package_id='.$row_view["package_id"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
												if(mysqli_num_rows($result_checkprice)>0)
												{
													echo '<a href="index.php?page=package.php&option=edit&pk_package_id='.$row_view["package_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
													if($system_usertype=="Admin")
													{
														echo '<a onclick="return delete_confirm()" href="index.php?page=package.php&option=delete&pk_package_id='.$row_view["package_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
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
		$get_pk_package_id=$_GET["pk_package_id"];
		
		$sql_fullview="SELECT * FROM package WHERE package_id='$get_pk_package_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		$sql_subcategoryname="SELECT name from packagesubcategory WHERE subcategory_id='$row_fullview[subcategory_id]'";
		$result_subcategoryname=mysqli_query($con,$sql_subcategoryname) or die("sql error in sql_subcategoryname ".mysqli_error($con));
		$row_subcategoryname=mysqli_fetch_assoc($result_subcategoryname);
		
		$sql_checkprice="SELECT startdate from packageprice WHERE package_id='$row_fullview[package_id]' AND enddate IS NULL";
		$result_checkprice=mysqli_query($con,$sql_checkprice) or die("sql error in sql_checkprice ".mysqli_error($con));
		
		$hrs=intdiv($row_fullview["duration"],60);
		$mins=$row_fullview["duration"]%60;

		if($mins>0){
			$visibleDuration=$hrs.'H '.$mins.'Mins';
		}
		else
		{
			$visibleDuration=$hrs.'H';
		}
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">package Full Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="display table table-striped table-hover">
								<tr><th>Package ID</th><td><?php echo $row_fullview["package_id"]; ?></td></tr>
								<tr><th>Subcategory</th><td><?php echo $row_subcategoryname["name"]; ?></td></tr>
								<tr><th>Name</th><td><?php echo $row_fullview["name"]; ?></td></tr>
								<tr><th>Duration</th><td><?php echo $visibleDuration; ?></td></tr>
								<tr><th>Description</th><td><?php echo $row_fullview["description"]; ?></td></tr>
								
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=package.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
											<?php 
											if(mysqli_num_rows($result_checkprice)>0)
											{
											?>
											<a href="index.php?page=package.php&option=edit&pk_package_id=<?php echo $row_fullview["package_id"]; ?>"><button class="btn btn-info">Edit</button></a> 
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
		<!---Package Price view details -->
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Package price Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
						<?php 
							if(mysqli_num_rows($result_checkprice)==0)
							{
							?>
							<a href="index.php?page=packageprice.php&option=add&pk_package_id=<?php echo $row_fullview["package_id"]; ?>"><button class="btn btn-primary">Add Package price</button></a><br><br>
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
									$sql_view="SELECT package_id,startdate,enddate,price,offer FROM packageprice  WHERE package_id='$row_fullview[package_id]' ORDER BY startdate DESC";
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
											if($row_view["enddate"]=="")
											{
													echo '<a href="index.php?page=packageprice.php&option=edit&pk_package_id='.$row_view["package_id"].'&pk_startdate='.$row_view["startdate"].'"><button class="btn btn-info btn-sm"><i class="fa fa-pen"></i> Edit</button></a> ';
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


		<!---Package product view details -->
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">package product Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<?php 
							if(mysqli_num_rows($result_checkprice)>0)
							{
							?>
							<a href="index.php?page=packageproduct.php&option=add&pk_package_id=<?php echo $row_fullview["package_id"]; ?>"><button class="btn btn-primary">Add package product</button></a><br><br>
							<?php
							}
							?>
							
							<table id="basic-datatables1" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>product</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$x=1;
									$sql_view="SELECT package_id,product_id,status FROM packageproduct WHERE package_id='$row_fullview[package_id]'";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{	
										$sql_enterby_product="SELECT name from product WHERE product_id='$row_view[product_id]'";
										$result_enterby_product=mysqli_query($con,$sql_enterby_product) or die("sql error in sql_enterby_product ".mysqli_error($con));
										$row_enterby_product=mysqli_fetch_assoc($result_enterby_product);
										
										echo '<tr>';
											echo '<td>'.$x++.'</td>';
											echo '<td>'.$row_enterby_product["name"].'</td>';
											echo '<td>'.$row_view["status"].'</td>';
											echo '<td>';
											if(mysqli_num_rows($result_checkprice)>0 && $row_view["status"]=="Active")
											{	
												echo '<a onclick="return delete_confirm()" href="index.php?page=packageproduct.php&option=delete&pk_package_id='.$row_view["package_id"].'&pk_product_id='.$row_view["product_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
											}

											if(mysqli_num_rows($result_checkprice)>0 && $row_view["status"]=="Deleted")
											{	
												echo '<a onclick="return reactivate_confirm()" href="index.php?page=packageproduct.php&option=reactivate&pk_package_id='.$row_view["package_id"].'&pk_product_id='.$row_view["product_id"].'"><button class="btn btn-info btn-sm"><i class="fa fa-undo"></i> Re-activate</button></a> ';
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

		<!---Package photo view details -->
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">package photo Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
						<?php 
						if(mysqli_num_rows($result_checkprice)>0)
						{
						?>
						<a href="index.php?page=packagephoto.php&option=add&pk_package_id=<?php echo $row_fullview["package_id"]; ?>"><button class="btn btn-primary">Add package photo</button></a><br><br>
						<?php
						}
						?>
						<table id="basic-datatables" class="display table table-striped table-hover">
								
								<tbody>
									<?php
									$sql_view="SELECT photo_id,photo,package_id FROM packagephoto  WHERE package_id='$row_fullview[package_id]'";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									$totalImage=mysqli_num_rows($result_view);
									$x=0;
									echo '<tr>';
									while($row_view=mysqli_fetch_assoc($result_view))
									{	
										
										
										echo '<td><center>';
											?>
											<img src="file/package/<?php echo $row_view["photo"].'?'.date("h:i:s"); ?>" onClick="popup_product('<?php echo $row_view["photo"].'?'.date("h:i:s"); ?>')" data-bs-toggle="modal" data-bs-target="#system_popup" width="150" hight="150">
											<?php
											if(mysqli_num_rows($result_checkprice)>0)
											{
												echo '<br><br><a onclick="return delete_confirm()" href="index.php?page=packagephoto.php&option=delete&pk_photo_id='.$row_view["photo_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
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
		$get_pk_package_id=$_GET["pk_package_id"];
		
		$sql_edit="SELECT * FROM package WHERE package_id='$get_pk_package_id'";
		$result_edit=mysqli_query($con,$sql_edit) or die("sql error in sql_edit ".mysqli_error($con));
		$row_edit=mysqli_fetch_assoc($result_edit);
		
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Form for Package Edit</div>
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
											<label for="txtpackageid">Package ID</label>
											<input type="text" class="form-control" name="txtpackageid" id="txtpackageid" required placeholder="Package ID" value="<?php echo $row_edit["package_id"]; ?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtsubcategory">Sub category</label>
											<select class="form-control" name="txtsubcategory" id="txtsubcategory" required placeholder="Sub category ID">
												<?php
													$sql_load="SELECT subcategory_id, name FROM packagesubcategory WHERE subcategory_id='$row_edit[subcategory_id]'";
													$result_load=mysqli_query($con,$sql_load) or die("sql error in sql_load".mysqli_error($con));
													while($row_load=mysqli_fetch_assoc($result_load))
													{
														echo'<option value="'.$row_load["subcategory_id"].'">'.$row_load["name"].'</option>';
													}
													?>
											</select>
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
											<label for="txtname">Name</label>
											<input type="text" class="form-control" onkeypress="return isTextKey(event)" name="txtname" id="txtname" required placeholder="Name" value="<?php echo $row_edit["name"]; ?>" />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtduration">Duration</label>
											<input type="number" min="1" max="360" onkeypress="return isNumberKey(event)" class="form-control" value="<?php echo $row_edit["duration"]; ?>" name="txtduration" id="txtduration" required placeholder="time duration"/>
											<font color="#ED2939"> * Please enter the duration in minutes. </font>
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
											<label for="txtdescription">description</label>
											<textarea class="form-control" name="txtdescription" id="txtdescription" required placeholder="Package description"><?php echo $row_edit["description"]; ?></textarea>
										</div>
										<!-- column one end -->
									</div>
								</div>
								<!-- third row end -->
								
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=package.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
		$get_pk_package_id=$_GET["pk_package_id"];

		$sql_checkprice="SELECT startdate from packageprice WHERE package_id='$get_pk_package_id' AND enddate IS NULL";
		$result_checkprice=mysqli_query($con,$sql_checkprice) or die("sql error in sql_checkprice ".mysqli_error($con));
		$row_checkprice=mysqli_fetch_assoc($result_checkprice);

		$today=date("Y-m-d");
	
		$sql_delete="UPDATE  packageprice SET enddate='$today' WHERE package_id='$get_pk_package_id' AND startdate='$row_checkprice[startdate]'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete)
		{
			echo '<script>alert("Successfully Deleted");
						window.location.href="index.php?page=package.php&option=view";</script>';

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