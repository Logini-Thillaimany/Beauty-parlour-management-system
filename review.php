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
//insert code start
if(isset($_POST["btnsave"]))
{
	$sql_insert="INSERT INTO review(review_id,booking_id,package_id,date,comments,rate)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtreviewid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtbookingid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtpackageid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtdate"])."',
									'".mysqli_real_escape_string($con,$_POST["txtcomments"])."',
									'".mysqli_real_escape_string($con,$_POST["txtrate"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
	if($result_insert)
	{
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=review.php&option=add";</script>';
	}
}
//insert code end
?>
<body>
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
						<div class="card-title"> Form for Add Review</div>
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
											<label for="txtreviewid">Review ID</label>
											<?php
												$sql_generatedid="SELECT review_id FROM review ORDER BY review_id DESC LIMIT 1";
												$result_generatedid=mysqli_query($con,$sql_generatedid) or die("sql error in sql_generatedid ".mysqli_error($con));
												if(mysqli_num_rows($result_generatedid)==1)
												{// for  except from the first submission
													$row_generatedid=mysqli_fetch_assoc($result_generatedid);
													$generatedid=++$row_generatedid["review_id"];
												}
												else
												{//For first time submission
													$generatedid="RE00000001";
												}
											?>
											<input type="text" class="form-control" name="txtreviewid" id="txtreviewid" required placeholder="review ID" value="<?php echo $generatedid;?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtbookingid">Booking ID</label>
											<select class="form-control" name="txtbookingid" id="txtbookingid" required placeholder="Booking id">
												<option value="select">Select Booking </option>
												<?php
												$sql_load_booking="SELECT booking_id FROM booking ";
												$result_load_booking=mysqli_query($con,$sql_load_booking) or die("sql error in sql_load_booking".mysqli_error($con));
												while($row_load_booking=mysqli_fetch_assoc($result_load_booking))
												{
													echo'<option value="'.$row_load_booking["booking_id"].'">'.$row_load_booking["booking_id"].'</option>';
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
											<label for="txtpackageid">Package</label>
											<select class="form-control" name="txtpackageid" id="txtpackageid" required placeholder="Package id">
											<option value="select">Select Package </option>
												<?php
												$sql_load_package="SELECT package_id , name FROM package ";
												$result_load_package=mysqli_query($con,$sql_load_package) or die("sql error in sql_load_package".mysqli_error($con));
												while($row_load_package=mysqli_fetch_assoc($result_load_package))
												{
													echo'<option value="'.$row_load_package["package_id"].'">'.$row_load_package["booking_id"].'</option>';
												}
												?>
											</select>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtdate">Date</label>
											<input type="date" class="form-control" name="txtdate" id="txtdate" required placeholder="date"/>
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
											<label for="txtrate">Rate</label>
											<input type="number" onkeypress="return isNumberKey(event)"  class="form-control" name="txtrate" id="txtrate" required placeholder="Add Rate"></textarea>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtcomments">Comments</label>
											<textarea class="form-control" name="txtcomments" id="txtcomments" required placeholder="Your openions"></textarea>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- third row end -->
								
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=review.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
						<h4 class="card-title">Review Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=review.php&option=add"><button class="btn btn-primary">Add Review</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>Review ID</th>
										<th>booking_id</th>
										<th>package</th>
										<th>date</th>
										<th>rate</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql_view="SELECT review_id,booking_id,package_id,date,rate FROM review";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{	$sql_package="SELECT name from package WHERE package_id='$row_view[package_id]'";
										$result_package=mysqli_query($con,$sql_package) or die("sql error in sql_package ".mysqli_error($con));
										$row_package=mysqli_fetch_assoc($result_package);
										
										echo '<tr>';
											echo '<td>'.$row_view["review_id"].'</td>';
											echo '<td>'.$row_view["booking_id"].'</td>';
											echo '<td>'.$row_package["name"].'</td>';
											echo '<td>'.$row_view["date"].'</td>';
											echo '<td>'.$row_view["rate"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=review.php&option=fullview&pk_review_id='.$row_view["review_id"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
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
		$get_pk_review_id=$_GET["pk_review_id"];
		
		$sql_fullview="SELECT * FROM review WHERE review_id='$get_pk_review_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		$sql_package="SELECT name from package WHERE package_id='$row_fullview[package_id]'";
		$result_package=mysqli_query($con,$sql_package) or die("sql error in sql_package ".mysqli_error($con));
		$row_package=mysqli_fetch_assoc($result_package);
										
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Review Full Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table  class="display table table-striped table-hover">
								<tr><th>Review ID</th><td><?php echo $row_fullview["review_id"]; ?></td></tr>
								<tr><th>Booking ID</th><td><?php echo $row_fullview["booking_id"]; ?></td></tr>
								<tr><th>Package</th><td><?php echo $row_package["name"]; ?></td></tr>
								<tr><th>Date</th><td><?php echo $row_fullview["date"]; ?></td></tr>
								<tr><th>Rate</th><td><?php echo $row_fullview["rate"]; ?></td></tr>
								<tr><th>Comments</th><td><?php echo $row_fullview["comments"]; ?></td></tr>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=review.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
											</center>
									</td>
								</tr>
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
		//NO edit form
		
	}
	else if($_GET["option"]=="delete")
	{
		//NO NEED delete code
	}
}
?>
</body>