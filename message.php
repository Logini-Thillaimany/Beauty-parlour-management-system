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
	$sql_insert="INSERT INTO message(message_id,from_id,to_id,date,time,subject,message,readstatus,inboxdelete,sentdelete)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtmessageid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtformid"])."',
									'".mysqli_real_escape_string($con,$_POST["txttoid"])."',
									'".mysqli_real_escape_string($con,$_POST["txtdate"])."',
									'".mysqli_real_escape_string($con,$_POST["txttime"])."',
									'".mysqli_real_escape_string($con,$_POST["txtsubject"])."',
									'".mysqli_real_escape_string($con,$_POST["txtmessage"])."',
									'".mysqli_real_escape_string($con,$_POST["txtreadstatus"])."',
									'".mysqli_real_escape_string($con,$_POST["txtinboxdelete"])."',
									'".mysqli_real_escape_string($con,$_POST["txtsentdelete"])."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
	if($result_insert)
	{
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=message.php&option=add";</script>';
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
						<div class="card-title"> Form for Messsage</div>
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
											<label for="txtmessageid">message ID</label>
											<?php
												$sql_generatedid="SELECT message_id FROM message ORDER BY message_id DESC LIMIT 1";
												$result_generatedid=mysqli_query($con,$sql_generatedid) or die("sql error in sql_generatedid ".mysqli_error($con));
												if(mysqli_num_rows($result_generatedid)==1)
												{// for  except from the first submission
													$row_generatedid=mysqli_fetch_assoc($result_generatedid);
													$generatedid=++$row_generatedid["message_id"];
												}
												else
												{//For first time submission
													$generatedid="MSG0000001";
												}
											?>
											<input type="text" class="form-control" name="txtmessageid" id="txtmessageid" required placeholder="message ID" value="<?php echo $generatedid;?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtformid">From id</label>
											<input type="text" class="form-control" name="txtformid" id="txtformid" required placeholder="From"/>
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
											<label for="txttoid">TO ID</label>
											<input type="text" class="form-control" name="txttoid" id="txttoid" required placeholder="TO"/>
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
											<label for="txtsubject">subject</label>
											<textarea class="form-control" name="txtsubject" id="txtsubject" required placeholder="subject"></textarea>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txttime">Time</label>
											<input type="time" class="form-control" name="txttime" id="txttime" required placeholder="time"/>
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
											<label for="txtmessage">message</label>
											<textarea class="form-control" name="txtmessage" id="txtmessage" required placeholder="message here"></textarea>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtreadstatus">Read Status</label>
											<input type="text" class="form-control" name="txtreadstatus" id="txtreadstatus" required placeholder="read status"/>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- fourth row end -->
								
								<!-- fifth row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtinboxdelete">inbox delete</label>
											<input type="text" class="form-control" name="txtinboxdelete" id="txtinboxdelete" required placeholder="inbox delete"/>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtsentdelete">sent delete</label>
											<input type="text" class="form-control" name="txtsentdelete" id="txtsentdelete" required placeholder="sent delete"/>
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
												<a href="index.php?page=message.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
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
						<h4 class="card-title">Message Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=message.php&option=add"><button class="btn btn-primary">Add Message</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>Message ID</th>
										<th>From</th>
										<th>To</th>
										<th>Date</th>
										<th>Subject</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql_view="SELECT message_id,from_id,to_id,date,subject FROM message";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{
										echo '<tr>';
											echo '<td>'.$row_view["message_id"].'</td>';
											echo '<td>'.$row_view["from_id"].'</td>';
											echo '<td>'.$row_view["to_id"].'</td>';
											echo '<td>'.$row_view["date"].'</td>';
											echo '<td>'.$row_view["subject"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=message.php&option=fullview&pk_message_id='.$row_view["message_id"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
												echo '<a onclick="return delete_confirm()" href="index.php?page=message.php&option=delete&pk_message_id='.$row_view["message_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
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
		$get_pk_message_id=$_GET["pk_message_id"];
		
		$sql_fullview="SELECT * FROM message WHERE message_id='$get_pk_message_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Message Full Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="display table table-striped table-hover">
								<tr><th>Message ID</th><td><?php echo $row_fullview["message_id"]; ?></td></tr>
								<tr><th>From</th><td><?php echo $row_fullview["from_id"]; ?></td></tr>
								<tr><th>TO </th><td><?php echo $row_fullview["to_id"]; ?></td></tr>
								<tr><th>Date</th><td><?php echo $row_fullview["date"]; ?></td></tr>
								<tr><th>Time</th><td><?php echo $row_fullview["time"]; ?></td></tr>
								<tr><th>Subject</th><td><?php echo $row_fullview["subject"]; ?></td></tr>
								<tr><th>Message</th><td><?php echo $row_fullview["message"]; ?></td></tr>
								<tr><th>Read Status</th><td><?php echo $row_fullview["readstatus"]; ?></td></tr>
								<tr><th>Inbox delete</th><td><?php echo $row_fullview["inboxdelete"]; ?></td></tr>
								<tr><th>Sent delete</th><td><?php echo $row_fullview["sentdelete"]; ?></td></tr>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=message.php&option=view"><button class="btn btn-primary">Go Back</button></a> 
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
		//No edit form
		
	}
	else if($_GET["option"]=="delete")
	{
		//delete code
		$get_pk_message_id=$_GET["pk_message_id"];
		
		$sql_delete="DELETE FROM message WHERE message_id='$get_pk_message_id'";
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete)
		{
				echo '<script>alert("Successfully Deleted");
						window.location.href="index.php?page=message.php&option=view";</script>';
	
		}	
	
	}
}
?>
</body>